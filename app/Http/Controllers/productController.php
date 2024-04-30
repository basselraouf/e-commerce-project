<?php

namespace App\Http\Controllers;

use App\Models\cart;
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

} 




