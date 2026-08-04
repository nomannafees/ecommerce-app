<?php

namespace App\Providers;

use App\Models\Categorie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\AdminStore; // <-- Store Model Import Kiya
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

            // 1. Cart Count Logic
            $cartCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())
                    ->sum('quantity');
            }

            // 2. Store Data Logic
            $store = AdminStore::first();

            $categories = Categorie::where('parent_id',0)
                ->with('children.children')
                ->get();

            // Both variables passed to all Blade views
            $view->with([
                'cartCount' => $cartCount,
                'store'     => $store,
                'categories'     => $categories,
            ]);
        });
    }
}
