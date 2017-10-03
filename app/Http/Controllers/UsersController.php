<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

use App\User;
use Illuminate\Http\Request;
use Session;
use Hash;

class UsersController extends Controller
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
        if(Auth::user()->hasRole('admin')){
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $users = User::where('name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('email', 'LIKE', '%'.$keyword.'%')
                    ->paginate($perPage);
            } else {
                $users = User::paginate($perPage);
            }

            return view('users.index', compact('users'));
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(Auth::user()->hasRole('admin')){
            $roles = Role::get();

            return view('users.create', ['roles' => $roles]);
        } else {
            return redirect()->route('login');
        }
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
        if(Auth::user()->hasRole('admin')){
            $this->validate($request, $this->getValidateArray());
            $requestData = $request->all();
            $requestData['password'] =  Hash::make($requestData['password']);
            
            $user = User::create($requestData);

            $roles = $request['roles'];
            if (isset($roles)) {
                foreach ($roles as $role) {
                    $found_role = Role::where('id', '=', $role)->firstOrFail();            
                    $user->assignRole($found_role);
                }
            }

            Session::flash('flash_message', 'User added!');

            return redirect('users');
        } else {
            return redirect()->route('login');
        }
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
            $user = User::findOrFail($id);

            return view('users.show', compact('user'));
        } else {
            return redirect()->route('login');
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
        if(Auth::user()->hasRole('admin')){
            $user = User::findOrFail($id);
            $user->password = '';
            $roles = Role::get();

            return view('users.edit', compact('user', 'roles'));
        } else {
            return redirect()->route('login');
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
        if(Auth::user()->hasRole('admin')){
            $this->validate($request, $this->getValidateArray());
            $requestData = $request->all();
            $requestData['password'] =  Hash::make($requestData['password']);
            
            $user = User::findOrFail($id);
            $user->update($requestData);

            $roles = $request['roles'];
            if (isset($roles)) {        
                $user->roles()->sync($roles);        
            }        
            else {
                $user->roles()->detach();
            }

            Session::flash('flash_message', 'User updated!');

            return redirect('users');
        } else {
            return redirect()->route('login');
        }
    }

    private function getValidateArray(){
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
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
        if(Auth::user()->hasRole('admin')){
            User::destroy($id);

            Session::flash('flash_message', 'User deleted!');

            return redirect('users');
        } else {
            return redirect()->route('login');
        }
    }
}
