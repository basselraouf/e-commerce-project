<?php

namespace App\Http\Controllers;
use App\Http\traits\GeneralTraits;
use App\Models\product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use GeneralTraits;
    public function __construct(){
        $this->middleware('AuthGuard');
    }

    public function addToCart($productId){
        $product = Product::find($productId);
        $user=auth()->user();

        if (!$user->cart) {
            $cart = $user->cart()->create();
        } else {
            $cart = $user->cart;
        }
    
        if($cart->products->contains($productId)){
            $pivotrow=$cart->products()->where('product_id',$productId)->first()->pivot;
            $pivotrow->update(['quantity'=>$pivotrow->quantity+1]);
        }else{
            $cart->products()->attach($productId,['quantity'=>1]);
        }
        return $this->returnSucessMessage( msg:'This item added to cart successfully');
    }

    public function removeFromCart($producId){
        try{
            $user=auth()->user();
            $cart = $user->cart;
            $pivotrow=$cart->products()->where('product_id',$producId)->first()->pivot;

            if($cart->products->contains($producId)){
                if($pivotrow->quantity  > 1){
                    $pivotrow->update(['quantity'=>$pivotrow->quantity-1]);
                }else{
                    $cart->products()->detach($producId);
            }
            return $this->returnSucessMessage( msg:'Product removed from cart successfully');
            } 
        }catch(\Exception $e){
            return $this->returnError(errNum:'E404' , msg:'Product not found in cart');
        }
    }

    public function getCartItems(Request $request){
        $cart = auth()->user()->cart;

        if ($cart->products->isEmpty()) {
            return $this->returnError(errNum:'E400', msg:'Cart is empty');
        }
        $cartItems = [];
        foreach($cart->products as $product){
            $cartItems[] = [
                'product_id'=>$product->id,
                'quantity'=>$product->pivot->quantity,
            ];
        }
        return $this->returnData(key:'cart_items', value:$cartItems, msg:'All cart items');
    }

}
