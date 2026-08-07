<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Wishlist;
use App\Models\UserProductInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $records = Product::with('variants', 'mainVariantImage')->get();
        return response()->json([
            'status' => true,
            'data' => $records
        ], 200);
    }

    public function productDetail($slug)
    {

        $product = Product::with(['images', 'variants.variantImage'])
            ->where('slug', $slug)
            ->first();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);

    }

    public function bestsellingProducts()
    {
        $products = Product::with(['variants', 'mainVariantImage', 'reviews'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function featuredProducts()
    {
        $products = Product::where('product_type', 'featured')
            ->with(['variants', 'mainVariantImage', 'reviews'])
            ->latest()
            ->take(12)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function forYouProducts(Request $request)
    {
        // Guest token handle karna agar request mein header/cookie se aaye
        $guestToken = $request->cookie('guest_unique_token') ?? $request->header('X-Guest-Token');

        $recentInteractions = collect();

        if (Auth::check()) {
            $recentInteractions = UserProductInteraction::where('user_id', Auth::id())
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->take(20)
                ->get();
        } elseif ($guestToken) {
            $recentInteractions = UserProductInteraction::where('session_id', $guestToken)
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->take(20)
                ->get();
        }

        $products = collect();

        if ($recentInteractions->isNotEmpty()) {
            $viewedProductIds = $recentInteractions->pluck('product_id')->filter()->unique()->toArray();
            $categoryIds = $recentInteractions->pluck('category_id')->filter()->unique()->toArray();
            $brandIds = $recentInteractions->pluck('brand_id')->filter()->unique()->toArray();

            if (!empty($viewedProductIds)) {
                $productListString = implode(',', $viewedProductIds);
                $interactedProducts = Product::whereIn('id', $viewedProductIds)
                    ->with(['variants', 'mainVariantImage', 'reviews'])
                    ->orderByRaw("FIELD(id, $productListString)")
                    ->get();

                $products = $products->concat($interactedProducts);
            }

            $recommendedQuery = Product::with(['variants', 'mainVariantImage', 'reviews'])
                ->whereNotIn('id', $products->pluck('id')->toArray())
                ->where(function ($query) use ($categoryIds, $brandIds) {
                    if (!empty($categoryIds)) {
                        $query->whereIn('category_id', $categoryIds);
                    }
                    if (!empty($brandIds)) {
                        $query->orWhereIn('brand_id', $brandIds);
                    }
                });

            if (!empty($categoryIds)) {
                $catList = implode(',', $categoryIds);
                $recommendedQuery->orderByRaw("FIELD(category_id, $catList) DESC");
            }
            if (!empty($brandIds)) {
                $brandList = implode(',', $brandIds);
                $recommendedQuery->orderByRaw("FIELD(brand_id, $brandList) DESC");
            }

            $recommendedProducts = $recommendedQuery->take(20)->get();
            $products = $products->concat($recommendedProducts);
        }

        // Fallback: Agar koi interaction nahi mili toh latest products return hongi
        if ($products->isEmpty()) {
            $products = Product::with(['variants', 'mainVariantImage', 'reviews'])
                ->latest()
                ->take(20)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

}
