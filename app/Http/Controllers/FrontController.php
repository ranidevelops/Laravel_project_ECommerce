<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request){


        $products = Product::where('is_featured','Yes')->orderBy('id','DESC')->where('status', 1)->take(8)->get();
        // dd($products);
        $data['featuredproducts'] = $products; 
        $latestProducts = Product::orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['latestproducts'] = $latestProducts;

        return view('front.layouts.home',$data);
    }
}
