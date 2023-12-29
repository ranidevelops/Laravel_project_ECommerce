<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function create(){
        return view('admin.brands.create');
    }
    public function store(Request $request){
         $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' =>'required|unique:brands',

        ]);
        if($validator->passess()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->save();

            return response()->json([
                'status'=> true,
                'message'=> 'Brand added successfully.'
            ]);


        }else{
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors()
            ]);
        }

    }
}
