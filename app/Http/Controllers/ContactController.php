<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Contact;
use App\Models\Reply_Message;

use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function contact(){
        return view('front.layouts.contact');
    }

    public function index(Request $request){
        $contact = Contact::oldest('id');
        if(!empty($request->get('keyword'))){
            $contact = $contact->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $contact = $contact->paginate(10);


        return view('admin.inquiry.list',compact('contact'));
    }

    public function edit($id,Request $request){
        $contact = Contact::find($id);
        if(empty($contact)){
            $request->session()->flash('error','Record not found.');
            return redirect()->route('inquiry.index');
        }
        $data['contact'] = $contact;


        return view('admin.inquiry.edit',$data);
    }

    public function update($id,Request $request){
        $contact = Contact::find($id);

        if(empty($contact)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'message' => 'Inquiry not found'


            ]);

        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required',


        ]);
        if($validator->passes()){
            
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone =$request->phone;
            $contact->message = $request->message;
            $contact->save();

            $request->session()->flash('success','Inquiry updated successfully');


            return response()->json([
                'status'=> true,
                'message'=> 'Inquiry updated successfully.'
            ]);


        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }

    }

    public function about(){
        return view('front.layouts.about');
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
    public function destroy($Id,Request $request){
        $contact = Contact::find($Id);
        if(empty($contact)){
        $request->session()->flash('error','inquiry not found');


            return response()->json([
                'status'=>true,
                'message'=>'inquiry not found.'
    
            ]);
    
        }


       
        $contact->delete();

        $request->session()->flash('success','inquiry deleted successfully');


        return response()->json([
            'status'=>true,
            'message'=>'inquiry deleted successfully.'

        ]);



    }  

    public function reply($id,Request $request){ 
        $contact = Contact::find($id);
        $data['contact'] = $contact;
        return view('admin.inquiry.Reply',$data);

    }

    public function store($id,Request $request){
        $validator = Validator::make($request->all(),[
            'reply_message' => 'required',

        ]);

        if($validator->passes()){
            //echo($request->)
            // echo "hello"; die;

            $reply_message = new Reply_Message();
            $reply_message->name = $request->name;
            $reply_message->userId = $request->userId;
            $reply_message->email = $request->email;
            $reply_message->message = $request->reply_message;

            $reply_message->save();

            return response()->json([
                'status'=> true,
                'message'=> ' Reply sent successfully.'
            ]);


        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }


        
    }
    
}

