<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class ProductController extends Controller
{   public function index(){

    }
    public function create(){
        $data =[];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories']= $categories;
        $data['brands'] = $brands;
        return view('admin.products.create',$data);

    }
    public function store(Request $request){
        $rules =[
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'track_qty' =>'required|in:Yes,No',
            'is_featured' => 'required|in:Yes,No',
        ];
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required';
        }
         $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }


    }
}
