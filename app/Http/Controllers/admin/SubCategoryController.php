<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Category; 
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{

    public function index(Request $request){
        $subcategories = SubCategory::latest('id');  
    
        if(!empty($request->get('keyword'))){
            $subcategories = $subcategories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        // Instead, apply the orderBy and paginate on the existing $categories
        $subcategories = $subcategories->orderBy('id', 'asc')->paginate(10);
    
        return view('admin.subcategory.list', compact('subcategories'));
    }

   public function create(){
    $categories = Category::orderBy('name','ASC')->get();
    $data['categories']= $categories;

    return view('admin.subcategory.create',$data);

   }
   public function store(Request $request){
    
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:sub-categories',
        'category'=>'required',
        'status' => 'required',
    ]);
   
    if($validator->passes()){
        echo "hello"; die;
    }
}
}
