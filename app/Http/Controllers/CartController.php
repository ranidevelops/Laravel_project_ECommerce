<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCharge;

use App\Models\Country; 


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
    public function checkout()
    {
    // Check if the cart is empty
    if (Cart::count() == 0) {
        return redirect()->route('front.cart');
    }

    // Initialize variables
    $customerAddress = null;
    $countries = Country::orderBy('name','ASC')->get();
    $totalShippingCharge = 0; 
    $grandTotal = 0;

    // Check if the user is logged in
    if (Auth::check()) {
        // User is already logged in
        // Redirect to intended URL if set
        if (session()->has('url.intended')) {
            $intendedUrl = session()->get('url.intended');
            session()->forget('url.intended');
            return redirect($intendedUrl);
        } else {
            // Retrieve customer address
            $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

            if ($customerAddress) {
                $userCountry = $customerAddress->country_id;
                $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

                if ($shippingInfo) {
                    $totalQty = Cart::count();
                    $totalShippingCharge = $totalQty * $shippingInfo->amount;
                    $grandTotal = Cart::subtotal(2,'.','') + $totalShippingCharge;
                }
            }
            // dd($totalShippingCharge);
        }
    } else {
        // User is not logged in
        // Set intended URL and redirect to login page
        session(['url.intended' => url()->current()]);
        return redirect()->route('account.login');
    }

    // Load the checkout view with necessary data
    return view('front.layouts.checkout', [
        'countries' => $countries,
        'customerAddress' => $customerAddress,
        'totalShippingCharge' => $totalShippingCharge,
        'grandTotal' => $grandTotal
    ]);
    }

    public function getOrderSummary(Request $request){
        if($request->country_id > 0){
            $subTotal = Cart::subtotal(2,'.','');
            $shippingInfo = ShippingCharge::where('country_id',$request->country_id)->first();

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
             foreach(Cart::content() as $item){
                $totalQty += $item->qty;
             }

            if($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = $subTotal + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => $grandTotal,
                    'shippingCharge' => $shippingCharge,

                ]);
            }else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = $subTotal + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'shippingCharge' => number_format($shippingCharge,2),

                ]);

            }




        }else{
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal,2),
                'shippingCharge' => number_format(0,2),

            ]);

        }


    }

    public function processCheckout(Request $request){
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state'=>'required',
            'zip' =>'required',
            'mobile'=>'required'

        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }

        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [   
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,

            ]
        );
        if($request->payment_method == 'cod'){


            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            $grandTotal = $subTotal+$shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->country_id = $request->country;
            $order->save();

            // store order items in orderItems table
            $orderItem = new OrderItem;

            foreach(Cart::content() as $item){
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

            }
            session()->flash('success','You have successfully placed your order.');
            Cart::destroy();

            return response()->json([
                'message' => 'Order saved successfully.',
                'orderId' => $order->id,
                'status' => true,
                'errors' => $validator->errors()

            ]);
            

        }else{

        }




    }
    public function thankyou(){
        return view('front.layouts.thanks');
    }
}
