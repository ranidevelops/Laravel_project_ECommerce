<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);
        if($product == null){
            return response()->json([
                'status' => false,
                'message' =>'Product not found'

            ]);
        }
        if(Cart::count() > 0){

        }else{
            Cart::add($product->id,$product->title,1,$product->price,['productImage'=> $product->product_images->first()]);

        }
        // Cart::add('29ad','Product 1',1,9.99);

    }
    public function cart(){
        return view('front.layouts.cart');
    }
    public function checkout(){
        return view('front.layouts.checkout');
    }
}
