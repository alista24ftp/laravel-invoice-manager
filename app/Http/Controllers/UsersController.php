<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['login']
        ]);
        $this->middleware('auth')->except(['login', 'loginUser']);
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

    public function update(Request $request, User $user, ImageUploadHandler $uploadHandler)
    {
        $oldUserName = $user->username;
        $oldEmail = $user->email;
        Validator::make($request->all(), [
            'username' => [
                'required',
                'max:255',
                function($attribute, $value, $fail) use ($oldUserName){
                    if($value !== $oldUserName && User::where('username', $value)->count()){
                        $fail('Username is already taken');
                    }
                }
            ],
            'firstname' => 'required|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'lastname' => 'nullable|max:30|regex:/^[A-Z][A-Za-z \-\'\.]*$/i',
            'email' => [
                'required',
                'max:255',
                'email',
                function($attribute, $value, $fail) use ($oldEmail){
                    if($value !== $oldEmail && User::where('email', $value)->count()){
                        $fail('Email is already taken');
                    }
                }
            ],
            'profile_pic' => 'nullable|file|mimes:jpg,jpeg,png,gif'
        ], [
            'username.required' => 'Username cannot be empty',
            'username.max' => 'Username cannot be longer than 255 characters',
            'username.unique' => 'Username already exists',
            'firstname.required' => 'First name cannot be empty',
            'firstname.max' => 'First name cannot be longer than 30 characters',
            'firstname.regex' => 'Incorrect first name format',
            'lastname.max' => 'Last name cannot be longer than 30 characters',
            'lastname.regex' => 'Incorrect last name format',
            'email.required' => 'Email cannot be empty',
            'email.max' => 'Email cannot be longer than 255 characters',
            'email.unique' => 'Email already exists',
            'email.email' => 'Incorrect email format',
            'profile_pic.file' => 'Profile pic must be a valid file',
            'profile_pic.mimes' => 'Profile pic must be a valid image'
        ])->validate();

        $userData = [
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email
        ];
        if($profile_pic = $request->profile_pic){
            $uploadResult = $uploadHandler->save($profile_pic, 'profile_pics/' . $user->id, [
                'user_id' => $user->id
            ]);
            if($uploadResult){
                $userData['profile_pic'] = $uploadResult['path'];
                if($oldProfilePic = $user->profile_pic){
                    unlink(public_path() . $oldProfilePic);
                }
            }else{
                return redirect()->route('users.edit', $user->id)
                    ->with('danger', 'Update failed: Unable to upload profile pic')->withInput();
            }
        }
        $user->update($userData);
        return redirect()->to(route('users.edit', $user->id))->with('success', 'User profile updated successfully');
    }
}
