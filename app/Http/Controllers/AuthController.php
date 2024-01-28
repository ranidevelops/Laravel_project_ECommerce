<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

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
            'password' => 'required|min:6',
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
    
            return response()->json(['status' => true, 'message' => 'Registration successful']);
        } else {
            // Form validation failed
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
    public function authenticate(Request $request){
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    
        // Attempt authentication
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // echo "hello"; die;
            // Authentication successful
            return redirect()->route('account.profile');
        }
    
        // Authentication failed
        return redirect()->route('account.login')->with('error', 'Invalid email or password.');
    }
    public function dashboard(){
        return view('front.account.profile');
    }

    public function profile(){
        echo"hello";
        
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success','You successfully logged out!');

    }
}
