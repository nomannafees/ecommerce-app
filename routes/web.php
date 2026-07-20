<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/product/{slug}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/product', [FrontendController::class, 'frontendProduct'])->name('frontendProduct');
Route::get('/categories', [FrontendController::class, 'allCategories'])->name('categories');
Route::get('/category/{slug}', [FrontendController::class, 'categoryProducts']);
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/wishlist', [FrontendController::class, 'storeWishlist'])->name('wishlists.store');
Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist');
Route::delete('/wishlist/{id}', [FrontendController::class, 'deleteWishlist'])->name('wishlist.delete');
Route::post('/add-to-cart', [FrontendController::class, 'addToCart'])->name('frontend.cart.add');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::delete('/cart/{id}', [FrontendController::class, 'deleteCart'])->name('cart.delete');
Route::post('/cart/update', [FrontendController::class, 'update'])->name('cart.update');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [FrontendController::class, 'checkoutStore'])->name('checkout.store');
Route::get('/thank-you', [FrontendController::class, 'thankYou'])->name('thankyou');
Route::middleware('auth')->group(function () {

    Route::get('/my-orders', [FrontendController::class, 'orders'])
        ->name('frontend.orders.index');

    Route::get('/my-orders/{id}', [FrontendController::class, 'orderDetail'])
        ->name('frontend.orders.show');

    Route::post('/my-orders/{id}/cancel', [FrontendController::class, 'cancelOrder'])
        ->name('order.cancel')
        ->middleware('auth');

    Route::post('/my-orders/{id}/restore', [FrontendController::class, 'restoreOrder'])
        ->name('order.restore')
        ->middleware('auth');
    Route::post('/buy-now', [App\Http\Controllers\FrontendController::class, 'buyNow'])->name('buy.now');
});



Auth::routes();
Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('categorie', CategorieController::class);
    Route::resource('products', ProductController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('wishlists', WishlistController::class);
    Route::resource('carts', CartController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('brands', BrandController::class);
});
