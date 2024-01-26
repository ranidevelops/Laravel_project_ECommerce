<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');

    }
    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'phone' => 'required',
    ]);

    if ($validator->passes()) {
        // Form data is valid, insert the user into the database
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Hash the password
        $user->phone = $request->phone;
        $user->save();

        // Optionally, you can log the user in after registration
        // Auth::login($user);

        return response()->json(['status' => true, 'message' => 'Registration successful']);
    } else {
        // Form validation failed
        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }
}
    public function profile(){
        echo"hello";
        
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success','You successfully logged out!');


    }
}
