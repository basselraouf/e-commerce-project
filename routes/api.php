<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function (){
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
});

Route::group(['prefix' => 'products'], function (){
    route::get('/categories/all',[ProductController::class, 'getAllCategorires']);
    route::get('/categories/{category_id}',[ProductController::class,'getProductsByCategoryID']);
    route::get('/relevant/{page}',[ProductController::class, 'getAllProductsByPageID']);
    route::get('/product/{id}',[ProductController::Class, 'getSpecificProduct']);   
});

Route::group(['prefix' => 'cart'], function () {
    route::post('/add-to-cart/{productId}',[CartController::class, 'addToCart']);
    route::post('/remove-from-cart/{productId}',[CartController::class, 'removeFromCart']);
    route::get('/cart-items',[CartController::class, 'getCartItems']);
});

Route::group(['prefix' => 'checkout'], function () {
    route::get('/place-order',[CheckoutController::class, 'checkout']);
});