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
    public function index(Request $request)
    {
        $perPage = 12; // Aap apni marzi se items per page change kar sakte hain (jaise 12)
        $page = (int) $request->get('page', 1);

        // 1. Sare products with relations fetch karein
        $products = Product::with([
            'variants',
            'mainVariantImage',
            'reviews' => function ($query) {
                $query->where('is_approved', true);
            }
        ])->latest()->get();

        // 2. Har product ke liye average rating aur total reviews calculate karna
        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews->count();

            // Agar aap JSON mein reviews ki list nahi bhejna chahte to yeh line uncomment kar sakte hain:
            // unset($product->reviews);
        });

        // 3. Manual Collection Pagination for API Response (Jaise forYouProducts mein kiya hai)
        $total = $products->count();
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $products->slice($offset, $perPage)->values();

        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json([
            'status' => true,
            'data' => $paginatedData->items(),
            'meta' => [
                'current_page' => $paginatedData->currentPage(),
                'last_page'    => $paginatedData->lastPage(),
                'per_page'     => $paginatedData->perPage(),
                'total'        => $paginatedData->total(),
                'has_more'     => $paginatedData->hasMorePages()
            ]
        ], 200);
    }

    public function productDetail(Request $request, $slug)
    {
        $product = Product::with([
            'images',
            'variants.variantImage',
            'prod_brand',
            'reviews' => function ($query) {
                // Yahan par with(['user', 'images']) add kiya hai
                $query->where('is_approved', true)
                    ->with(['user', 'images'])
                    ->latest();
            }
        ])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!'
            ], 404);
        }

        // --- TRACK USER INTERACTION ---
        $user = auth('sanctum')->user() ?? auth()->user();

        if ($user) {
            UserProductInteraction::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ],
                [
                    'category_id' => $product->category_id ?? null,
                    'brand_id' => $product->brand_id ?? ($product->prod_brand->id ?? null),
                    'weight' => \DB::raw('weight + 1')
                ]
            );
        } else {
            $guestToken = $request->cookie('guest_unique_token') ?? $request->header('X-Guest-Token');

            if ($guestToken) {
                UserProductInteraction::updateOrCreate(
                    [
                        'session_id' => $guestToken,
                        'product_id' => $product->id
                    ],
                    [
                        'category_id' => $product->category_id ?? null,
                        'brand_id' => $product->brand_id ?? ($product->prod_brand->id ?? null),
                        'weight' => \DB::raw('weight + 1')
                    ]
                );
            }
        }
        // ------------------------------

        // --- RATING & REVIEWS CALCULATION ---
        $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
        $product->total_reviews = $product->reviews->count();
        // ------------------------------------

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
        $perPage = 6;
        $page = (int) $request->get('page', 1);

        // 1. Pehle API Guard ke zariye check karein ke user logged in hai ya nahi
        // (Agar aap Sanctum use kar rahe hain toh 'sanctum' guard dein)
        $user = auth('sanctum')->user() ?? auth()->user();

        // 2. Guest token handle (Cookie ya Header se)
        $guestToken = $request->cookie('guest_unique_token') ?? $request->header('X-Guest-Token');

        $recentInteractions = collect();

        // 3. Agar User Login hai (Bearer token ke through)
        if ($user) {
            $recentInteractions = UserProductInteraction::where('user_id', $user->id)
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        // 4. Agar User Login nahi hai lekin Guest Token mojood hai
        elseif ($guestToken) {
            $recentInteractions = UserProductInteraction::where('session_id', $guestToken)
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        $products = collect();

        if ($recentInteractions->isNotEmpty()) {
            $viewedProductIds = $recentInteractions->pluck('product_id')->filter()->unique()->toArray();
            $categoryIds = $recentInteractions->pluck('category_id')->filter()->unique()->toArray();
            $brandIds = $recentInteractions->pluck('brand_id')->filter()->unique()->toArray();

            // 1. Interacted Products
            if (!empty($viewedProductIds)) {
                $productListString = implode(',', $viewedProductIds);
                $interactedProducts = Product::whereIn('id', $viewedProductIds)
                    ->with(['variants', 'mainVariantImage', 'reviews'])
                    ->orderByRaw("FIELD(id, $productListString)")
                    ->get();

                $products = $products->concat($interactedProducts);
            }

            // 2. Recommended Products
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

            $recommendedProducts = $recommendedQuery->get();
            $products = $products->concat($recommendedProducts);
        }

        // Fallback: Agar interactions na milen
        if ($products->isEmpty()) {
            $products = Product::with(['variants', 'mainVariantImage', 'reviews'])
                ->latest()
                ->get();
        }

        // Manual Collection Pagination for API Response
        $total = $products->count();
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $products->slice($offset, $perPage)->values();

        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json([
            'success' => true,
            'data' => $paginatedData->items(),
            'meta' => [
                'current_page' => $paginatedData->currentPage(),
                'last_page'    => $paginatedData->lastPage(),
                'per_page'     => $paginatedData->perPage(),
                'total'        => $paginatedData->total(),
                'has_more'     => $paginatedData->hasMorePages()
            ]
        ], 200);
    }

    public function flashSaleProducts(Request $request)
    {
        $products = Product::whereHas('flashSale', function ($query) {
            $query->whereDate('start_time', '<=', now())
                ->whereDate('end_time', '>=', now());
        })
            ->with(['variants', 'mainVariantImage', 'reviews', 'flashSale'])
            ->latest()
            ->get();

        // Har product ke liye average rating aur total reviews calculate karna
        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews->count();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function adminStore()
    {
        $setting = AdminStore::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $setting
        ], 200);
    }

}
