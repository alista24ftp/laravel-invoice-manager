<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['login']
        ]);
    }

    public function login()
    {
        return view('users.login');
    }

    public function loginUser(Request $request)
    {
        $credentials = $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)){
            session()->flash('success', 'Login Successful');
            $fallback = route('invoices.index');
            return redirect()->intended($fallback);
        }else{
            session()->flash('danger', 'Login failed. Please check your login credentials');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('success', 'You have successfully logged out');
        return redirect('login');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
}
