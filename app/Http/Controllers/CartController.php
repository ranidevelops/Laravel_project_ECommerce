<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
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

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach($cartContent as $item){
                if($item->id == $product->id){
                    $productAlreadyExist = true;
                }
                
            }
            if($productAlreadyExist == false){
                Cart::add($product->id,$product->title,1,$product->price,['productImage'=> (!empty($product->product_images)) ? $product->product_images->first() : ""]);
                $status = true;
                $message = $product->title. 'added in  your cart successfully.';
                session()->flash('success',$message);


            }else{
                $status = false;
                $message = $product->title. 'already added in cart';
            }


        }else{
            Cart::add($product->id,$product->title,1,$product->price,['productImage'=> (!empty($product->product_images)) ? $product->product_images->first() : ""]);
            $status = true;
            $message = 'Product added in cart';
            session()->flash('success',$message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message

        ]);
        

    }
    public function cart() {
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.layouts.cart', $data);
    }
    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;
    
        $itemInfo = Cart::get($rowId);
    
        // Retrieve the product
        $product = Product::find($itemInfo->id);
    
        // Check if the product exists and if it tracks quantity
        if($product && $product->track_qty == 'Yes'){
            // Check if requested quantity is available in stock
            if($qty <= $product->qty){
                // Update cart
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
                session()->flash('success', $message);

            } else {
                // Quantity not available in stock
                $message = 'Requested quantity ('.$qty.') not available in stock.';
                $status = false;
                session()->flash('error', $message);

            }
        } else {
            // Update cart for products that don't track quantity
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
            session()->flash('success', $message);

        }
    
        // Flash the message to the session
    
        // Return response
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function deleteItem(Request $request){
        $errorMessage ='Item not found in cart';
        $itemInfo = Cart::get($request->rowId);
        if($itemInfo == null){
            session()->flash('error', $errorMessage);


            return response()->json([
                'status' => false,
                'message' => $errorMessage

            ]);
        }

        Cart::remove($request->rowId);
        $message = 'Item removed from cart successfully.';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);


    }
 }
