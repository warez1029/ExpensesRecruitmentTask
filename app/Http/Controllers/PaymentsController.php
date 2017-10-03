<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Payment;
use Illuminate\Http\Request;
use Session;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if(Auth::user()->hasRole('admin')){
            if (!empty($keyword)) {
                $payments = Payment::where('name', 'LIKE', '%'.$keyword.'%')
                    ->paginate($perPage);
            } else {
                $payments = Payment::paginate($perPage);
            }
        } else {
            if (!empty($keyword)) {
                $payments = Auth::user()->payments()
                    ->where('name', 'LIKE', '%'.$keyword.'%')
                    ->paginate($perPage);
            } else {
                $payments = Auth::user()->payments()->paginate($perPage);
            }
        }

        return view('payments.index', compact('payments'));
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
        if(Auth::user()->hasRole('admin') || Auth::user()->payments()->where('payments.id', '=', $id)->exists()){
            $payment = Payment::findOrFail($id);
            return view('payments.show', compact('payment'));
        } else {
            Session::flash('flash_message', "Payment doesn't exist!");
            return redirect('payments');
        }
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
        if(Auth::user()->hasRole('admin') || Auth::user()->payments()->where([
        ['payments.id', '=', $id],['payments.status', '<>', 'Approved']])->exists()){
            $payment = Payment::findOrFail($id);
            return view('payments.edit', compact('payment'));
        } else {
            Session::flash('flash_message', "Payment doesn't exist!");
            return redirect('payments');
        }
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
            'value' => 'required|numeric'
        ];
        if(Auth::user()->hasRole('admin')){
            $validateArray = array_add($validateArray, 'status', ['required', Rule::in(['Un-approved', 'Approved', 'Rejected'])]);
        }
        $this->validate($request, $validateArray);
        $requestData = $request->all();
        
        if(Auth::user()->hasRole('admin') || Auth::user()->payments()->where([
        ['payments.id', '=', $id],['payments.status', '<>', 'Approved']])->exists()){
            $payment = Payment::findOrFail($id);
            $payment->update($requestData);
            Session::flash('flash_message', 'Payment updated!');
        } else {
            Session::flash('flash_message', "Payment doesn't exist!");
        }

        return redirect('payments');
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
        if(Auth::user()->hasRole('admin') || Auth::user()->payments()->where([
        ['payments.id', '=', $id],['payments.status', '<>', 'Approved']])->exists()){
            Payment::destroy($id);
            Session::flash('flash_message', 'Payment deleted!');
        } else {
            Session::flash('flash_message', "Payment doesn't exist!");
        }

        return redirect('payments');
    }
}
