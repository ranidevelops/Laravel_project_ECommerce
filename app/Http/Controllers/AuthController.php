<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Hash;
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

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:user',
            'password' =>'required|min:6',
            'phone' => 'required',

        ]);
        if($validator->passes()){

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }

    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))){

            }else{
                // session()->flash('error','Either email/password is incorrect.');
                return redirect()->route('account.login')->withInput($request->only('email'))
                ->with('error','Either email/password is incorrect.');


            }

        }else{
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
