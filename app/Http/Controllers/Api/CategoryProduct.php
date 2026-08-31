<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categorie;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class CategoryProduct extends Controller
{
    /**
     * 1. Separate API for Sidebar Filters & Categories
     */
    public function getSidebarData(Request $request, $slug = null)
    {
        $currentCategory = null;
        $categorySlug = $request->get('category', $slug);

        // 1. Nested slug ko trace karna taake exact target category mil jaye
        if (!empty($categorySlug)) {
            $slugs = explode('/', $categorySlug);
            $parent = null;
            foreach ($slugs as $s) {
                $parent = Categorie::where('slug', $s)
                    ->when($parent, fn($q) => $q->where('parent_id', $parent->id), fn($q) => $q->where('parent_id', 0))
                    ->first();
                if (!$parent) break;
            }
            $currentCategory = $parent;
        }

        // 2. Categories tree ko request ke mutabiq restrict karna
        if ($currentCategory) {
            // Agar koi child ya sub-category khuli hai, toh sab se pehle uska top-level parent (main category) dhoondhein
            $rootCategory = $currentCategory;
            while ($rootCategory->parent_id != 0) {
                $rootCategory = Categorie::find($rootCategory->parent_id);
                if (!$rootCategory) break;
            }

            // Ab sirf us main parent ko load karein, aur `allChildren` ki jagah sirf wahi tree rakhein jo current path ya uske children ko represent kare
            $categories = Categorie::where('id', $rootCategory->id)->with('allChildren')->get();
        } else {
            // Agar koi category URL mein nahi hai (All Products page), toh sirf root main categories (`parent_id = 0`) aayengi
            $categories = Categorie::where('parent_id', 0)->with('allChildren')->get();
        }

        // 3. Category ke hisaab se product IDs nikalna (taake colors/sizes/brands sirf isi category ke hon)
        $categoryIds = null;
        if ($currentCategory) {
            $currentCategory->load('children.children');

            $getSubIds = function ($cat) use (&$getSubIds) {
                $ids = [];
                foreach ($cat->children as $child) {
                    $ids[] = $child->id;
                    if ($child->children->isNotEmpty()) {
                        $ids = array_merge($ids, $getSubIds($child));
                    }
                }
                return $ids;
            };

            $categoryIds = array_merge([$currentCategory->id], $getSubIds($currentCategory));
        }

        // 4. Base product query (category ke mutabiq scoped) — multi-select filters yahan apply honge
        $productQuery = Product::query();

        if ($categoryIds) {
            $productQuery->whereIn('category_id', $categoryIds);
        }

        if ($request->filled('min_price')) {
            $productQuery->whereHas('variants', fn($q) => $q->where('price', '>=', $request->min_price));
        }
        if ($request->filled('max_price')) {
            $productQuery->whereHas('variants', fn($q) => $q->where('price', '<=', $request->max_price));
        }

        // MULTIPLE COLORS FILTER LOGIC
        if ($request->filled('color')) {
            $colors = is_array($request->color) ? $request->color : [$request->color];
            $productQuery->whereHas('variants', fn($q) => $q->whereIn('color_name', $colors));
        }

        // MULTIPLE SIZES FILTER LOGIC
        if ($request->filled('size')) {
            $sizes = is_array($request->size) ? $request->size : [$request->size];
            $productQuery->whereHas('variants', fn($q) => $q->whereIn('size', $sizes));
        }

        // MULTIPLE BRANDS FILTER LOGIC
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $productQuery->whereHas('prod_brand', fn($q) => $q->whereIn('slug', $brands));
        }

        $productIds = $productQuery->pluck('id');

        // 5. Ab sirf inhi (filtered) products ke variants/brand se available options nikalein
        $availableColors = ProductVariant::whereIn('product_id', $productIds)
            ->whereNotNull('color_name')
            ->where('color_name', '!=', '')
            ->distinct()
            ->pluck('color_name')
            ->toArray();

        $availableSizes = ProductVariant::whereIn('product_id', $productIds)
            ->whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->toArray();

        $availableBrands = Brand::whereNotNull('name')
            ->where('name', '!=', '')
            ->whereHas('products', fn($q) => $q->whereIn('id', $productIds))
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'current_category' => $currentCategory ? $currentCategory->load('children.children') : null,
            'available_colors' => $availableColors,
            'available_sizes' => $availableSizes,
            'available_brands' => $availableBrands,
        ]);
    }

    /**
     * 2. Separate API for Products (Pagination, Filtering, Sorting)
     */
    public function getProducts(Request $request, $slug = null)
    {
        $query = Product::with([
            'variants',
            'prod_brand',
            'mainVariantImage',
            'mainVariant',
            'variant_images',
            'reviews'
        ]);

        // Search Filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $currentCategory = null;

        // Category Filter via Nested Slug
        $categorySlug = $request->get('category', $slug);

        if (!empty($categorySlug)) {
            $slugs = explode('/', $categorySlug);
            $parent = null;
            foreach ($slugs as $s) {
                $parent = Categorie::where('slug', $s)
                    ->when($parent, fn($q) => $q->where('parent_id', $parent->id), fn($q) => $q->where('parent_id', 0))
                    ->first();
                if (!$parent) break;
            }

            $currentCategory = $parent;

            if ($currentCategory) {
                $getSubIds = function ($cat) use (&$getSubIds) {
                    $ids = [];
                    $children = $cat->relationLoaded('children') ? $cat->children : $cat->children()->get();
                    foreach ($children as $child) {
                        $ids[] = $child->id;
                        $ids = array_merge($ids, $getSubIds($child));
                    }
                    return $ids;
                };

                $categoryIds = array_merge([$currentCategory->id], $getSubIds($currentCategory));
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Filters (Price, Color, Size, Brand)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '>=', $request->min_price));
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '<=', $request->max_price));
        }

        // MULTIPLE COLORS FILTER LOGIC
        if ($request->filled('color')) {
            $colors = is_array($request->color) ? $request->color : [$request->color];
            $query->whereHas('variants', fn($q) => $q->whereIn('color_name', $colors));
        }

        // MULTIPLE SIZES FILTER LOGIC
        if ($request->filled('size')) {
            $sizes = is_array($request->size) ? $request->size : [$request->size];
            $query->whereHas('variants', fn($q) => $q->whereIn('size', $sizes));
        }

        // MULTIPLE BRANDS FILTER LOGIC
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereHas('prod_brand', fn($q) => $q->whereIn('slug', $brands));
        }

        // Sorting Logic
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low_high') {
                $query->orderBy(ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'asc')->limit(1), 'asc');
            } elseif ($request->sort === 'price_high_low') {
                $query->orderBy(ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'desc')->limit(1), 'desc');
            } else {
                $query->latest('products.created_at');
            }
        } else {
            $query->latest();
        }

        // Custom Pagination (1st page: 15 items, Subsequent pages: 10 items)
        $page = max(1, (int) $request->get('page', 1));
        $perPage = ($page == 1) ? 15 : 10;
        $totalRecords = $query->count();

        if ($page == 1) {
            $offset = 0;
            $limit = 10;
        } else {
            $offset = 15 + (($page - 2) * 10);
            $limit = 10;
        }

        $cloneQuery = clone $query;
        $recordsCollection = $cloneQuery->offset($offset)->limit($limit)->get();

        $records = new \Illuminate\Pagination\LengthAwarePaginator(
            $recordsCollection,
            $totalRecords,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // MULTIPLE COLORS: ab selected colors ek array hain (single color nahi)
        $selectedColors = $request->filled('color')
            ? array_map(fn($c) => strtolower(trim($c)), (array) $request->color)
            : [];

        $records->getCollection()->transform(function ($product) use ($selectedColors) {
            $variant = null;
            if (!empty($selectedColors)) {
                $variant = $product->variants->first(fn($v) => in_array(strtolower(trim($v->color_name)), $selectedColors));
            }

            $product->active_variant = $variant ?? ($product->mainVariant ?? $product->variants->first());

            $matchedImage = null;
            if ($product->active_variant) {
                $matchedImage = $product->variant_images->first(fn($img) => $img->id == $product->active_variant->variant_image_id);
            }

            $product->custom_image_path = $matchedImage
                ? asset('storage/' . $matchedImage->image_path)
                : ($product->mainVariantImage ? asset('storage/' . $product->mainVariantImage->image_path) : asset('images/no-image.png'));

            $product->avgRating = $product->reviews->isNotEmpty() ? $product->reviews->avg('rating') : 0;

            return $product;
        });

        $wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];

        return response()->json([
            'success' => true,
            'current_page' => $records->currentPage(),
            'last_page' => $records->lastPage(),
            'per_page' => $records->perPage(),
            'total' => $records->total(),
            'wishlist_product_ids' => $wishlistProductIds,
            'products' => $records->items()
        ]);
    }
}