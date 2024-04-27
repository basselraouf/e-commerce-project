<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use App\Models\category;

class productController extends Controller
{
    // public function __construct(){
    //     $this->middleware('AuthGuard');
    // }
    
    public function getAllProductsByPageID($page){
        $perpage= 10;
        $offset= ($page-1)*$perpage ;
        $products = Product::offset($offset)->limit($perpage)->get();
        return response()->json($products);
    }



    public function getSpecificProduct($id){
        $product=product::where('id',$id)->get();
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
    

 
    
}


