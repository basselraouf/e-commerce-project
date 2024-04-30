<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\category;
use Auth;

class productController extends Controller
{
    public function __construct(){
        $this->middleware('AuthGuard');
    }
    
    public function getAllProductsByPageID($page){
        $perpage= 10;
        $offset= ($page-1)*$perpage ;
        $products = Product::offset($offset)->limit($perpage)->get();
        return response()->json($products);
    }



    public function getSpecificProduct($id){
        $product=product::find($id);
        return response()->json($product);
    }




    public function getAllCategorires(){
        $allCategories=category::select('id','name')->get();
        return response()->json($allCategories);
    }



    public function getProductsByCategoryID(Request $request ,$category_id){
        $perpage=10;
        $page=$request->input('page');
        $offset=($page-1)*10;
        $products=product::where('category_id',$category_id)->offset($offset)->limit($perpage)->get();
        return response()->json($products);
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

        return response()->json('This item added to cart successfully');

    }


    public function removeFromCart($producId){
        $user=auth()->user();
        
        $cart = $user->cart;
        
        if($cart->products->contains($producId)){
            $cart->products()->detach($producId);
        
        return response()->json(['message' => 'Product removed from cart successfully']);

        } else {
        return response()->json(['message' => 'Product not found in cart'], 404);
        }
    }


    public function checkout(Request $request){
        $user=Auth::user();
        $cart=auth()->user()->cart;
        if ($cart->products->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
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
        return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id]);
    }

} 




