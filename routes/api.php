<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sliders', [\App\Http\Controllers\Api\SliderController::class,'index']);
Route::get('/all-products', [\App\Http\Controllers\Api\ProductController::class,'index']);
Route::get('/product/{slug}', [\App\Http\Controllers\Api\ProductController::class,'productDetail']);
Route::get('/products/bestselling', [\App\Http\Controllers\Api\ProductController::class, 'bestsellingProducts']);
Route::get('/products/featured', [\App\Http\Controllers\Api\ProductController::class, 'featuredProducts']);
Route::get('/products/for-you', [\App\Http\Controllers\Api\ProductController::class, 'forYouProducts']);

Route::get('/brands', [\App\Http\Controllers\Api\BrandController::class,'index']);
Route::post('/add-to-cart', [\App\Http\Controllers\Api\OrderController::class,'addToCart']);
Route::get('/cart', [\App\Http\Controllers\Api\CartController::class,'cart']);
Route::post('/cart-remove-item/{id}', [\App\Http\Controllers\Api\CartController::class,'cartRemove']);
Route::put('/cart-update-quantity/{id}', [\App\Http\Controllers\Api\CartController::class, 'updateQuantity']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/wish-list', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
Route::post('/wish-list-add', [\App\Http\Controllers\Api\WishlistController::class, 'storeWishList']);
Route::post('/place-order', [\App\Http\Controllers\Api\CheckoutController::class, 'checkoutStore']);
Route::get('/user-orders', [\App\Http\Controllers\Api\OrderController::class, 'orders']);
Route::get('/orders-detail/{id}', [\App\Http\Controllers\Api\OrderController::class, 'orderDetail']);
Route::get('/checkout', [\App\Http\Controllers\Api\CheckoutController::class, 'checkout']);
Route::get('/cities/{id}', [\App\Http\Controllers\Api\CheckoutController::class, 'getCitiesByState']);



