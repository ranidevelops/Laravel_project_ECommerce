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
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id');  
    
        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');

            $subCategories = $subCategories->orwhere('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');

        }
    
        // Instead, apply the orderBy and paginate on the existing $categories
        $subCategories = $subCategories->orderBy('id', 'asc')->paginate(10);
    
        return view('admin.sub_category.list', compact('subCategories'));
    }

   public function create(){
    $categories = Category::orderBy('name','ASC')->get();
    $data['categories']= $categories;

    return view('admin.sub_category.create',$data);

   }
   public function store(Request $request){
       $validator = Validator::make($request->all(),[
        'name'=>'required',
        'slug'=>'required|unique:sub_categories',
        'category'=>'required',
        'status' => 'required'
    ]);
    if($validator->passes()){
        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->status = $request->status;
        $subCategory->category_id = $request->category;
        $subCategory->save();

        $request->session()->flash('success','SubCategory added successfully');

        return response()->json([
            'status' => true,
            'message' => 'SubCategory added successfully'
        ]);


    }else{
        return response([
            'status'=>false,
            'errors'=>$validator->errors()
        ]);
    }
    
   
   }
}
