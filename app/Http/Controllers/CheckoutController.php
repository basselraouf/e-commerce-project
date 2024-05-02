<?php

namespace App\Http\Controllers;

use App\Http\traits\GeneralTraits;
use Illuminate\Http\Request;
use App\Models\order;
use App\Models\order_item;
use Auth;

class CheckoutController extends Controller
{
    use GeneralTraits;
    public function __construct(){
        $this->middleware('AuthGuard');
    }
    
    public function checkout(Request $request){
        $user=Auth::user();
        $cart=auth()->user()->cart;
        if ($cart->products->isEmpty()) {
            return $this->returnError(errNum:'E400' , msg:'Cart is empty');
        }
        
        $totalAmount=0;
        foreach($cart->products as $product){
            $totalAmount+=($product->price * $product->pivot->quantity);
        }

        $order=new order([
            'user_id'=> $user->id ,
            'total_amount'=> $totalAmount,
            'status'=> 'pending',
            'payment_method'=>$request->input('payment_method'),
            'payment_status'=>'unpaid',
            'shipping_address'=>$request->input('shipping_address'),
        ]);
        $order->save();

        foreach ($cart->products as $product) {
            $orderItem = new order_item([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->price,
            ]);
            $orderItem->save();
        }

        $cart->products()->detach();
        return $this->returnData(key:'order_id', value:$order->id, msg:'Order placed successfully');
    }
}
