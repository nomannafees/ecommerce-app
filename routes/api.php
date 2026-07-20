<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sliders', [\App\Http\Controllers\Api\SliderController::class,'index']);
Route::get('/products', [\App\Http\Controllers\Api\ProductController::class,'index']);
Route::get('/product/{slug}', [\App\Http\Controllers\Api\ProductController::class,'productDetail']);

Route::get('/brands', [\App\Http\Controllers\Api\BrandController::class,'index']);
Route::post('/add-to-cart', [\App\Http\Controllers\Api\OrderController::class,'addToCart']);
