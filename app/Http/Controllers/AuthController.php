<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
