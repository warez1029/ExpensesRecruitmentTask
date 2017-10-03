<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Expense;
use App\Payment;
use Illuminate\Http\Request;
use Session;

class ExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if(!Auth::user()->hasRole('admin') && !empty($keyword)){
            $expenses = Auth::user()->expenses()
                ->where('name', 'LIKE', '%'.$keyword.'%')
                ->paginate($perPage);
        } else if (!Auth::user()->hasRole('admin')) {
            $expenses = Auth::user()->expenses()
                ->paginate($perPage);
        } else if (!empty($keyword)) {
            $expenses = Expense::where('name', 'LIKE', '%'.$keyword.'%')
                ->paginate($perPage);
        } else {
            $expenses = Expense::paginate($perPage);
        }

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validateArray = [
			'name' => 'min:6|required',
			'payment.*.name' => 'min:6|required',
			'payment.*.value' => 'numeric|required',
		];
        if(Auth::user()->hasRole('admin')){
            $validateArray = array_add($validateArray, 'payment.*.status', ['required', Rule::in(['Un-approved', 'Approved', 'Rejected'])]);
        }
        $this->validate($request, $validateArray, $this->getValidationMessages());
        $requestData = $request->all();
        
        $expense = new Expense();
        $expense->name = $requestData['name'];
        Auth::user()->expenses()->save($expense);

        $payments = $request['payment'];
        if (isset($payments)) {
            foreach ($payments as $id => $payment) {
                $expense->payments()->create($payment);
            }
        }

        Session::flash('flash_message', 'Expense added!');

        return redirect('expenses');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if(Auth::user()->hasRole('admin')){
            $expense = Expense::findOrFail($id);
        } else {
            $expense = Auth::user()->expenses()->findOrFail($id);
        }

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if(Auth::user()->hasRole('admin')){
            $expense = Expense::findOrFail($id);
        } else {
            $expense = Auth::user()->expenses()->findOrFail($id);
        }

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $validateArray = [
			'name' => 'min:6|required',
			'payment.*.name' => 'min:6|required',
			'payment.*.value' => 'numeric|required',
        ];

        $isAdmin = Auth::user()->hasRole('admin');
        if($isAdmin){
            $validateArray = array_add($validateArray, 'payment.*.status', ['required', Rule::in(['Un-approved', 'Approved', 'Rejected'])]);
        } else if(!Auth::user()->expenses()->where('id', '=', $id)->exists()){
            Session::flash('flash_message', "Expense doesn't exist!");
            return redirect('expenses');
        }

        $this->validate($request, $validateArray, $this->getValidationMessages());
        $requestData = $request->all();
        
        $expense = Expense::findOrFail($id);
        // $data = $request->only(['name']);
        $expense->update($requestData);
        
        $payments = $request['payment'];
        $old_payments = $expense->payments();
        $blocked_payments = [];
        $payments_not_to_delete = [];
        foreach ($payments as $id => $payment){
            if(!empty($payment['id'])){
                array_push($payments_not_to_delete, $payment['id']);
            }
        }
        if(!$isAdmin){
            foreach($old_payments as $i => $payment){
                if($payment->status == 'Approved'){
                    array_push($blocked_payments, $payment->id);
                }
            }
        }
        $payments_not_to_delete = array_unique($payments_not_to_delete);
        $payments_to_destroy = $old_payments->whereNotIn('id', $payments_not_to_delete);

        if(!empty($payments_to_destroy)){
            if(!$isAdmin){
                $payments_to_destroy->where('status', '<>', 'Approved')->delete();
            } else {
                $payments_to_destroy->delete();
            }
            $expense->save();
        }

        if (isset($payments)) {
            foreach ($payments as $id => $payment){
                if(!empty($payment['id'])){
                    if(!in_array($payment['id'], $blocked_payments)){
                        $expense->payments()->find($payment['id'])->update($payment);
                    }
                } else {
                    $expense->payments()->create($payment);
                }
            }
        }

        Session::flash('flash_message', 'Expense updated!');

        return redirect('expenses');
    }

    // do osobnego pliku
    public function getValidationMessages()
    {
        return [
            'payment.*.name.min' => 'Payment name should be minimum 6 characters long.', 
            'payment.*.name.required' => 'Payment name is required.',
            'payment.*.value.numeric' => 'Payment value should be number.', 
            'payment.*.value.required' => 'Payment value is required.',
            'payment.*.status.required' => 'Payment status is required.', 
            'payment.*.status.in' => 'Payment status should be Un-approved, Approved or Rejected.', 
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        if(Auth::user()->hasRole('admin') || (Auth::user()->expenses()->where('id', '=', $id)->exists()
        && !Auth::user()->expenses()->where('id', '=', $id)->payments()->where('status', '=', 'Approved')->exists())){
            Expense::destroy($id);
            $expense = Expense::findOrFail($id);
            Session::flash('flash_message', 'Expense deleted!');
        } else {
            Session::flash('flash_message', "Expense doesn't exist!");
        }

        return redirect('expenses');
    }
}
