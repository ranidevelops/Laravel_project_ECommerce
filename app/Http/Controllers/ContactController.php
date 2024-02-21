<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Contact;

use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function contact(){
        return view('front.layouts.contact');
    }

    public function processContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|numeric',
            'message' => 'required',
        ]);
    
        if ($validator->passes()) {
            // // Form data is valid, insert the user into the database
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->save();
    
            return response()->json(['status' => true, 'message' => 'Message sent successfully.']);
        } else {
            // Form validation failed
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
    
}

