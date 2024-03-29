<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;

use Illuminate\Support\Facades\Validator;
use Image;


use Illuminate\Http\Request;

class ProductController extends Controller
{   public function index(Request $request){
    $products = Product::latest('id')->with('product_images');

    if($request->get('keyword')!= ""){
        $products = $products->where('title','like','%'.$request->keyword.'%');
    }
    
    $products = $products->paginate();
    
    $data['products'] = $products;
    return view('admin.products.list',$data);

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
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
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
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            // save Gallery pics
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);
        
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
        
                    // Generate a unique image name
                    $imageName = $product->id . '-' . time() . '-' . $temp_image_id . '.' . $ext;
        
                    $productImage->image = $imageName;
                    $productImage->save();
        
                    // Move the image from temp to the product image directory
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/large/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);
        
                    // Small Image
                    $destPath = public_path() . '/uploads/product/small/' . $imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }
        

            return response()->json([
                'status' => true,
                'message' => 'product added succussfully.'

            ]);
    


        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }


    }
    public function edit($id, Request $request){
        $product = Product::find($id);
        $data =[];
        $data['product'] = $product;
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories']= $categories;
        $data['brands'] = $brands;

        return view('admin.products.edit',$data);
    }
}
