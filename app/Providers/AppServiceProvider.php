<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\AdminStore;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            // 1. Cart Count Logic (FIXED: Unique items count instead of total sum of quantity)
            $cartCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())
                    ->has('variant') // Sirf un items ko count karega jin ka variant exist karta hai
                    ->count();      // <-- sum('quantity') ki jagah count() kar diya hai
            }

            // 1b. Wishlist Count Logic (same pattern as Cart)
            $wishlistCount = 0;

            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }

            // 2. Store Data Logic
            $store = AdminStore::first();

            $categories = Categorie::where('parent_id', 0)
                ->with('children.children')
                ->get();

            // All variables passed to all Blade views
            $view->with([
                'cartCount'      => $cartCount,
                'wishlistCount'  => $wishlistCount,
                'store'          => $store,
                'categories'     => $categories,
            ]);
        });
    }
}