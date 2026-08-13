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
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminLoginController;


Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/product/{slug}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/all-product', [FrontendController::class, 'frontendProduct'])->name('frontendProduct');
Route::get('/product/{category?}', [FrontendController::class, 'allCategories'])
    ->where('category', '.*')
    ->name('categories');
// Agar aap categoriesProduct ke liye alag route rakhna chahte hain:
Route::get('/categories/{category?}', [FrontendController::class, 'categoriesProduct'])
    ->where('category', '.*')
    ->name('categoriesProduct');

// Ya agar allCategories ke liye nested slug use karna hai:
Route::get('/category/{category?}', [FrontendController::class, 'allCategories'])
    ->where('category', '.*')
    ->name('category.slug');

Route::get('/collection/{category?}', [FrontendController::class, 'allCategories'])
    ->where('category', '.*')
    ->name('collection');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/wishlist', [FrontendController::class, 'storeWishlist'])->name('wishlists.store');
Route::get('/wishlist', [FrontendController::class, 'wishlist'])->name('wishlist');
Route::delete('/wishlist/{id}', [FrontendController::class, 'deleteWishlist'])->name('wishlist.delete');
Route::post('/add-to-cart', [FrontendController::class, 'addToCart'])->name('frontend.cart.add');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::delete('/cart/{id}', [FrontendController::class, 'deleteCart'])->name('cart.delete');
Route::post('/cart/update', [FrontendController::class, 'update'])->name('cart.update');

// AJAX Route for Cities
Route::get('/get-cities/{stateId}', [FrontendController::class, 'getCitiesByState']);
Route::post('/checkout/store', [FrontendController::class, 'checkoutStore'])->name('checkout.store');
Route::get('/thank-you', [FrontendController::class, 'thankYou'])->name('thankyou');
Route::post('/contact-us', [\App\Http\Controllers\ContactUsController::class, 'store'])->name('contact-us.store');
Route::get('/load-more-products', [\App\Http\Controllers\HomeController::class, 'loadMoreProducts'])->name('load.more.products');

// Admin Login Routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'adminLogin'])->name('admin.login.submit');

Route::middleware('auth')->group(function () {

    Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');

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

    Route::middleware(['auth'])->group(function () {
        Route::post('/reviews/store', [FrontendController::class, 'storeReview'])->name('reviews.store');
    });

    // ✅ Route Names Unique Honay Chahiye

    // Profile Routes
    Route::get('/profile', [FrontendController::class, 'profile'])->name('frontend.user_info.index');
    Route::post('/profile/update', [FrontendController::class, 'updateProfile'])->name('frontend.user_info.update');

    // Password Routes
    Route::get('/profile/password', function() {
        return redirect()->route('frontend.user_info.index');
    });
    Route::post('/profile/password', [FrontendController::class, 'updatePassword'])->name('frontend.user_info.password');

});


Auth::routes();
Route::middleware(['auth', 'admin'])->prefix('/admin')->group(function () {

    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('/categorie', CategorieController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/variants', ProductVariantController::class);
    Route::resource('/coupons', CouponController::class);
    Route::resource('/wishlists', WishlistController::class);
    Route::resource('/carts', CartController::class);
    Route::resource('/orders', OrderController::class);
    Route::resource('/sliders', SliderController::class);
    Route::resource('/brands', BrandController::class);
    Route::resource('/reviews', ReviewController::class);
    Route::resource('/admin-info', \App\Http\Controllers\AdminInfoController::class);
    Route::resource('/admin-store', \App\Http\Controllers\AdminStoreController::class);
    Route::resource('/contacts', ContactUsController::class);
});
