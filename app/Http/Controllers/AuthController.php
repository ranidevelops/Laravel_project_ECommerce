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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->passes()) {
            $credentials = $request->only('email','password');
    
            if (Auth::attempt($credentials, $request->get('remember'))) {
                // Authentication was successful
                return redirect()->route('account.profile');
            } else {
                // Authentication failed
                session()->flash('error', 'Either email/password is incorrect.');
                return redirect()->route('account.login');
            }
        } else {
            // Validation failed
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
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
