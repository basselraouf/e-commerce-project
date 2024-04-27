<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\productController;
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



Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
});





Route::group([],function(){
    route::get('/products/{page}',productController::class.'@getAllProductsByPageID');
    route::get('/product/{id}',productController::Class.'@getSpecificProduct');
    route::get('/all-categories',productController::class.'@getAllCategorires');
    route::get('/products/all-categories/{category_id}',productController::class.'@getProductsByCategoryID');
    
});




