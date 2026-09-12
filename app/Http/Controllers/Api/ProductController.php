<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminStore;
use App\Models\Product;
use App\Models\UserProductInteraction;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Flash sale active products ko filter karne ke liye closure
    private function excludeFlashSale()
    {
        return function ($query) {
            $query->where('start_time', '<=', now())
                ->where('end_time', '>=', now());
        };
    }

    public function index(Request $request)
    {
        $perPage = 12;
        $page = (int) $request->get('page', 1);

        $products = Product::whereDoesntHave('flashSale', $this->excludeFlashSale())
            ->with([
                'variants.variantImage',
                'variantImages',
                'mainVariantImage',
                'reviews' => function ($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->withCount(['orderItems', 'reviews'])
            ->latest()
            ->get();

        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews_count ?? $product->reviews->count();
        });

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
            'variantImages',
            'mainVariantImage',
            'prod_brand',
            'reviews' => function ($query) {
                $query->where('is_approved', true)
                    ->with(['user', 'images'])
                    ->latest();
            }
        ])
            ->withCount(['orderItems', 'reviews'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!'
            ], 404);
        }

        // Track user interaction
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

        $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
        $product->total_reviews = $product->reviews_count ?? $product->reviews->count();

        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    }

    public function bestsellingProducts()
    {
        $products = Product::whereDoesntHave('flashSale', $this->excludeFlashSale())
            ->with([
                'variants.variantImage',
                'variantImages',
                'mainVariantImage',
                'reviews' => function ($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->withCount(['orderItems', 'reviews'])
            ->orderBy('order_items_count', 'desc')
            ->take(12)
            ->get();

        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews_count ?? $product->reviews->count();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    public function featuredProducts()
    {
        $products = Product::where('product_type', 'featured')
            ->whereDoesntHave('flashSale', $this->excludeFlashSale())
            ->with([
                'variants.variantImage',
                'variantImages',
                'mainVariantImage',
                'reviews' => function ($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->withCount(['orderItems', 'reviews'])
            ->latest()
            ->take(12)
            ->get();

        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews_count ?? $product->reviews->count();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    public function forYouProducts(Request $request)
    {
        $perPage = 12;
        $page = (int) $request->get('page', 1);

        $user = auth('sanctum')->user() ?? auth()->user();
        $guestToken = $request->cookie('guest_unique_token') ?? $request->header('X-Guest-Token');

        $recentInteractions = collect();

        if ($user) {
            $recentInteractions = UserProductInteraction::where('user_id', $user->id)
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        } elseif ($guestToken) {
            $recentInteractions = UserProductInteraction::where('session_id', $guestToken)
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        $products = collect();
        $relations = [
            'variants.variantImage',
            'variantImages',
            'mainVariantImage',
            'reviews' => function ($query) {
                $query->where('is_approved', true);
            }
        ];

        if ($recentInteractions->isNotEmpty()) {
            $viewedProductIds = $recentInteractions->pluck('product_id')->filter()->unique()->toArray();
            $categoryIds = $recentInteractions->pluck('category_id')->filter()->unique()->toArray();
            $brandIds = $recentInteractions->pluck('brand_id')->filter()->unique()->toArray();

            if (!empty($viewedProductIds)) {
                $productListString = implode(',', $viewedProductIds);
                $interactedProducts = Product::whereIn('id', $viewedProductIds)
                    ->whereDoesntHave('flashSale', $this->excludeFlashSale())
                    ->with($relations)
                    ->withCount(['orderItems', 'reviews'])
                    ->orderByRaw("FIELD(id, $productListString)")
                    ->get();

                $products = $products->concat($interactedProducts);
            }

            $recommendedQuery = Product::whereDoesntHave('flashSale', $this->excludeFlashSale())
                ->with($relations)
                ->withCount(['orderItems', 'reviews'])
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

        if ($products->isEmpty()) {
            $products = Product::whereDoesntHave('flashSale', $this->excludeFlashSale())
                ->with($relations)
                ->withCount(['orderItems', 'reviews'])
                ->latest()
                ->get();
        }

        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews_count ?? $product->reviews->count();
        });

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
            $query->where('start_time', '<=', now())
                ->where('end_time', '>=', now());
        })
            ->with([
                'variants.variantImage',
                'variantImages',
                'mainVariantImage',
                'flashSale',
                'reviews' => function ($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->withCount(['orderItems', 'reviews'])
            ->take(6)
            ->latest()
            ->get();

        $products->each(function ($product) {
            $product->avg_rating = round($product->reviews->avg('rating'), 1) ?: 0;
            $product->total_reviews = $product->reviews_count ?? $product->reviews->count();
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
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