<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\FrontendController;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
 
Route::get('getCategory', [FrontendController::class, 'category']);
Route::get('fetchproducts/{slug}', [FrontendController::class, 'product']);
Route::get('viewproductdetail/{category_slug}/{product_slug}',[FrontendController::class, 'viewproduct']);
Route::post('add-to-cart', [CartController::class, 'addtocart']);
Route::get('cart', [CartController::class, 'viewcart']);
Route::put('cart-updatequantity/{cart_id}/{scope}',[CartController::class, 'updatequantity']);
Route::delete('delete-cartitem/{cart_id}',[CartController::class, 'deleteCartitem']);
Route::post('validate-order',[CheckoutController::class, 'validateOrder']);

Route::post('place-order', [CheckoutController::class, 'placeorder']);



Route::middleware(['auth:sanctum','isAdmin'])->group(function () {
    Route::get('/checkLogin', function(){
        return response()->json(['message' => 'You have been logged in', 'status' => 200], 200);
    });
    // Category
    Route::get('view_category',[CategoryController::class, 'index']);
    Route::post('add_Category', [CategoryController::class, 'store']);
    Route::get('edit_category/{id}', [CategoryController::class, 'edit']);
    Route::put('update_category/{id}', [CategoryController::class, 'update']);
    Route::put('delete_category/{id}', [CategoryController::class, 'delete']);
    Route::put('restore_category/{id}', [CategoryController::class, 'restore']);
    Route::put('destroy_category/{id}', [CategoryController::class, 'destroy']);
    Route::get('all_category', [CategoryController::class, 'getAll']);
    Route::get('garbage_category',[CategoryController::class, 'garbageView']);

    // Orders
    Route::get('admin/orders', [OrderController::class, 'index']);
    


    // product
    Route::post('add_product', [ProductController::class, 'store']);
    Route::get('view_product', [ProductController::class, 'index']);
    Route::get('edit_product/{id}', [ProductController::class, 'edit']);
    Route::put('update_product/{id}', [ProductController::class, 'update']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
