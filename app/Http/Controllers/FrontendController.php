<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Categorie;
use App\Models\CustomerInfo;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserProductInteraction;
use App\Models\Wishlist;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Slider;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


class FrontendController extends Controller
{

    public function index(Request $request)
    {
        // 1. TOP 12 MOST ORDERED PRODUCTS (Bestsellers)
        $topOrderedProducts = Product::with(['variants', 'mainVariantImage', 'reviews'])
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(12)
            ->get();

        // 2. TOP 12 FEATURED PRODUCTS (Based on product_type column)
        $featuredProducts = Product::where('product_type', 'featured')
            ->with(['variants', 'mainVariantImage', 'reviews'])
            ->latest()
            ->take(12)
            ->get();

        // 3. SLIDERS
        $sliders = Slider::latest()->get();

        // 4. BRANDS
        $brands = Brand::latest()->get();

        // --- SIDEBAR VARIABLES ---
        $availableColors = ProductVariant::whereNotNull('color_name')
            ->where('color_name', '!=', '')
            ->distinct()
            ->pluck('color_name')
            ->toArray();

        $availableSizes = ProductVariant::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->toArray();

        $availableBrands = Brand::whereNotNull('name')
            ->where('name', '!=', '')
            ->get();
        // -------------------------

        // 5. WISHLIST IDS
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        // 5.1. GUEST UNIQUE COOKIE HANDLING (30 Days persistent token)
        $guestToken = $request->cookie('guest_unique_token');
        if (!$guestToken && !auth()->check()) {
            $guestToken = (string)\Str::uuid();
            cookie()->queue('guest_unique_token', $guestToken, 60 * 24 * 30);
        }

        // 6. "FOR YOU" PERSONALIZED PRODUCTS LOGIC
        if (auth()->check()) {
            $recentInteractions = UserProductInteraction::where('user_id', auth()->id())
                ->orderBy('weight', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            $recentInteractions = collect();
            if ($guestToken) {
                $recentInteractions = UserProductInteraction::where('session_id', $guestToken)
                    ->orderBy('weight', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }

        $products = collect();

        if ($recentInteractions->isNotEmpty()) {
            $viewedProductIds = $recentInteractions->pluck('product_id')->filter()->unique()->toArray();
            $categoryIds = $recentInteractions->pluck('category_id')->filter()->unique()->toArray();
            $brandIds = $recentInteractions->pluck('brand_id')->filter()->unique()->toArray();

            $productListString = !empty($viewedProductIds) ? implode(',', $viewedProductIds) : '0';
            $catList = !empty($categoryIds) ? implode(',', $categoryIds) : '0';
            $brandList = !empty($brandIds) ? implode(',', $brandIds) : '0';

            if (!empty($viewedProductIds)) {
                $interactedProducts = Product::whereIn('id', $viewedProductIds)
                    ->with(['variants', 'mainVariantImage', 'reviews'])
                    ->orderByRaw("FIELD(id, $productListString)")
                    ->get();

                $products = $products->concat($interactedProducts);
            }

            $recommendedProducts = Product::with(['variants', 'mainVariantImage', 'reviews'])
                ->whereNotIn('id', $products->pluck('id')->toArray())
                ->where(function ($query) use ($categoryIds, $brandIds) {
                    $query->whereIn('category_id', $categoryIds)
                        ->orWhereIn('brand_id', $brandIds);
                })
                ->orderByRaw("FIELD(category_id, $catList) DESC")
                ->orderByRaw("FIELD(brand_id, $brandList) DESC")
                ->get();

            $products = $products->concat($recommendedProducts);
        }

        if ($products->isEmpty()) {
            $products = Product::with(['variants', 'mainVariantImage', 'reviews'])
                ->latest()
                ->get();
        }

        // --- COLLECTION TO PAGINATOR CONVERSION FOR AJAX SCROLLING ---
        $page = $request->get('page', 1);
        $perPage = 12;
        $offset = ($page * $perPage) - $perPage;

        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->slice($offset, $perPage)->values(),
            $products->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Variable ko $products mein assign kar diya hai taake partial view mein error na aaye
        $products = $paginatedProducts;

        // Agar AJAX request ho toh partial view return karein
        if ($request->ajax()) {
            return view('frontend.partials.for-you-cards', compact('products', 'wishlistProductIds'))->render();
        }

        return view('frontend.index', compact(
            'topOrderedProducts',
            'featuredProducts',
            'sliders',
            'wishlistProductIds',
            'brands',
            'products',
            'availableColors',
            'availableSizes',
            'availableBrands'
        ));
    }

    public function productDetail($slug)
    {
        $product = Product::with([
            'images',
            'variants.variantImage',
            'prod_brand',
            'reviews' => function ($query) {
                $query->where('is_approved', true)->with(['user', 'images'])->latest();
            }
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // --- TRACK USER INTERACTION ---
        if (auth()->check()) {
            UserProductInteraction::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ],
                [
                    'category_id' => $product->category_id ?? null,
                    'brand_id' => $product->brand_id ?? ($product->prod_brand->id ?? null),
                    'weight' => \DB::raw('weight + 1')
                ]
            );
        } else {
            $guestSessionId = session()->getId();
            UserProductInteraction::updateOrCreate(
                [
                    'session_id' => $guestSessionId,
                    'product_id' => $product->id
                ],
                [
                    'category_id' => $product->category_id ?? null,
                    'brand_id' => $product->brand_id ?? ($product->prod_brand->id ?? null),
                    'weight' => \DB::raw('weight + 1')
                ]
            );
        }
        // ------------------------------

        // Dynamically calculate average rating and count
        $avgRating = round($product->reviews->avg('rating'), 1) ?: 0;
        $totalReviews = $product->reviews->count();

        return view('frontend.product-detail', compact('product', 'avgRating', 'totalReviews'));
    }

    public function frontendProduct(Request $request)
    {
        // 1. Products with variants, images, and reviews
        $products = Product::with(['variants', 'mainVariantImage', 'reviews'])->latest()->paginate(12);

        // Agar requested page par products hi nahi hain (misal ke taur par page 3 khali hai)
        if ($request->ajax()) {
            if ($products->isEmpty()) {
                return response()->json(''); // Bilkul khali response bhejein taake AJAX mein append na ho
            }

            $wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];
            return view('frontend.partials.product-cards', compact('products', 'wishlistProductIds'))->render();
        }

        // --- Baaki ka purana code wese hi rahega ---
        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        $availableColors = ProductVariant::whereNotNull('color_name')
            ->where('color_name', '!=', '')
            ->distinct()
            ->pluck('color_name')
            ->toArray();

        $availableSizes = ProductVariant::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->toArray();

        $availableBrands = Brand::whereNotNull('name')
            ->where('name', '!=', '')
            ->get();

        $brands = Brand::latest()->get();

        return view('frontend.product', compact(
            'products',
            'wishlistProductIds',
            'availableColors',
            'availableSizes',
            'availableBrands',
            'brands'
        ));
    }

    public function categoriesProduct(Request $request, $category = null)
    {
        $categories = Categorie::where('parent_id', 0)->with('children')->get();

        $currentCategory = null;
        $query = Product::with(['variants', 'prod_brand', 'mainVariantImage', 'mainVariant', 'variant_images', 'reviews'])->latest();

        if (!empty($category)) {
            $slugs = explode('/', $category);

            // Nested slugs ko sahi se trace karne ke liye parent-child chain check karein
            $parent = null;
            foreach ($slugs as $slug) {
                $parent = Categorie::where('slug', $slug)
                    ->when($parent, function($q) use ($parent) {
                        return $q->where('parent_id', $parent->id);
                    }, function($q) {
                        return $q->where('parent_id', 0);
                    })->first();

                if (!$parent) break;
            }

            $currentCategory = $parent;

            if ($currentCategory) {
                $getAllIds = function ($cat) use (&$getAllIds) {
                    $ids = [];
                    // Naye Laravel/Eloquent relations ke mutabiq load children check karein
                    $children = $cat->relationLoaded('children') ? $cat->children : $cat->children()->get();
                    foreach ($children as $child) {
                        $ids[] = $child->id;
                        $ids = array_merge($ids, $getAllIds($child));
                    }
                    return $ids;
                };

                $categoryIds = array_merge([$currentCategory->id], $getAllIds($currentCategory));
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->paginate(12);
        $wishlistProductIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->toArray() : [];

        if ($request->ajax()) {
            return view('frontend.partials.category-products-loop', compact('products', 'wishlistProductIds'))->render();
        }

        return view('frontend.category-products', compact(
            'categories',
            'currentCategory',
            'products',
            'wishlistProductIds'
        ));
    }

    public function allCategories(Request $request, $category = null)
    {
        // 1. Default main categories
        $categories = Categorie::where('parent_id', 0)->with('allChildren')->get();

        $availableColors = ProductVariant::whereNotNull('color_name')
            ->where('color_name', '!=', '')
            ->distinct()
            ->pluck('color_name')
            ->toArray();

        $availableSizes = ProductVariant::whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->pluck('size')
            ->toArray();

        $availableBrands = Brand::whereNotNull('name')
            ->where('name', '!=', '')
            ->get();

        $query = Product::with([
            'variants',
            'prod_brand',
            'mainVariantImage',
            'mainVariant',
            'variant_images',
            'reviews'
        ]);

        // 2. Search Filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $currentCategory = null;
        $activeCategory = null;

        // 3. Category Filter (Clean Route Parameter Handling)
        if (!empty($category)) {
            $slugs = explode('/', $category);
            $targetSlug = end($slugs);

            $currentCategory = Categorie::where('slug', $targetSlug)->with('children.children')->first();

            if ($currentCategory) {
                $activeCategory = $currentCategory;

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
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // 4. Filters (Price, Color, Size, Brand)
        if ($request->filled('min_price')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '>=', $request->min_price));
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '<=', $request->max_price));
        }
        if ($request->filled('color')) {
            $query->whereHas('variants', fn($q) => $q->where('color_name', $request->color));
        }
        if ($request->filled('size')) {
            $query->whereHas('variants', fn($q) => $q->where('size', $request->size));
        }
        if ($request->filled('brand')) {
            $query->whereHas('prod_brand', fn($q) => $q->where('slug', $request->brand));
        }

        // 5. Sorting Logic
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low_high') {
                $query->orderBy(\App\Models\ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'asc')->limit(1), 'asc');
            } elseif ($request->sort === 'price_high_low') {
                $query->orderBy(\App\Models\ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'desc')->limit(1), 'desc');
            } else {
                $query->latest('products.created_at');
            }
        } else {
            $query->latest();
        }

        // 6. Custom Pagination Logic (1st page: 15 items, Subsequent pages: 10 items)
        $page = max(1, (int) $request->get('page', 1));
        $perPage = ($page == 1) ? 15 : 10;
        $totalRecords = $query->count();

        if ($page == 1) {
            $offset = 0;
            $limit = 15;
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

        $selectedColor = $request->filled('color') ? strtolower(trim($request->color)) : null;

        $records->getCollection()->transform(function ($product) use ($selectedColor) {
            $variant = null;
            if ($selectedColor) {
                $variant = $product->variants->first(fn($v) => strtolower(trim($v->color_name)) === $selectedColor);
            }

            $product->active_variant = $variant ?? ($product->mainVariant ?? $product->variants->first());

            $matchedImage = null;
            if ($product->active_variant) {
                $matchedImage = $product->variant_images->first(fn($img) => $img->id == $product->active_variant->variant_image_id);
            }

            $product->custom_image_path = $matchedImage
                ? $matchedImage->image_path
                : ($product->mainVariantImage ? $product->mainVariantImage->image_path : '');

            $product->avgRating = $product->reviews->isNotEmpty() ? $product->reviews->avg('rating') : 0;

            return $product;
        });

        // 7. Wishlist Check
        $wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];

        if ($request->ajax()) {
            // Agar records ka collection khali ho jaye (yani mazeed products na hon)
            if ($recordsCollection->isEmpty()) {
                return response()->json([
                    'products' => '',
                    'sidebar' => ''
                ]);
            }

            $productHtml = view('frontend.partials.category-product-cards', compact('records', 'wishlistProductIds'))->render();

            $sidebarHtml = view('frontend.partials.category-sidebar', compact(
                'categories',
                'activeCategory',
                'currentCategory',
                'availableColors',
                'availableSizes',
                'availableBrands'
            ))->render();

            return response()->json([
                'products' => $productHtml,
                'sidebar' => $sidebarHtml
            ]);
        }

        return view('frontend.categories', compact(
            'categories',
            'records',
            'wishlistProductIds',
            'activeCategory',
            'currentCategory',
            'availableColors',
            'availableSizes',
            'availableBrands'
        ));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function storeWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first'
            ]);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        // REMOVE FROM WISHLIST
        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'status' => true,
                'action' => 'removed',
                'message' => 'Removed from wishlist'
            ]);
        }

        // ADD TO WISHLIST
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => true,
            'action' => 'added',
            'message' => 'Added to wishlist'
        ]);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::with(['product.variants'])
            ->where('user_id', Auth::id())
            ->get();

        return view('frontend.wishlist', compact('wishlists'));
    }

    public function deleteWishlist($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Login required'
            ]);
        }

        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ]);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed from wishlist'
        ]);
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }
        $request->validate([
            'variant_id' => 'required',
            'qty' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::find($request->variant_id);

        if (!$variant) {
            return response()->json([
                'status' => false,
                'message' => 'Variant not found'
            ]);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('variant_id', $request->variant_id)
            ->first();

        $currentQty = $cart ? $cart->quantity : 0;

        $newQty = $currentQty + $request->qty;

        if ($newQty > $variant->stock) {
            return response()->json([
                'status' => false,
                'message' => 'Only ' . $variant->stock . ' items available in stock'
            ]);
        }

        if ($cart) {

            $cart->quantity = $newQty;
            $cart->save();
        } else {

            Cart::create([
                'user_id' => Auth::id(),
                'variant_id' => $request->variant_id,
                'quantity' => $request->qty,
            ]);
        }

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => 'Added to cart successfully',
            'cartCount' => $cartCount,
        ]);
    }

    public function buyNow(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Please login first.'
            ]);
        }

        // Detect correct payload key ('quantity' or 'qty')
        $qtyKey = $request->has('quantity') ? 'quantity' : 'qty';

        $request->validate([
            'variant_id' => 'required',
            $qtyKey => 'required|integer|min:1'
        ]);

        $qtyInput = intval($request->input($qtyKey));
        $variant = ProductVariant::find($request->variant_id);

        if (!$variant) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Variant not found'
            ]);
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('variant_id', $request->variant_id)
            ->first();

        $currentQty = $cart ? $cart->quantity : 0;
        $newQty = $currentQty + $qtyInput;

        if ($newQty > $variant->stock) {
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => 'Only ' . $variant->stock . ' items available in stock'
            ]);
        }

        if ($cart) {
            $cart->quantity = $newQty;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'variant_id' => $request->variant_id,
                'quantity' => $qtyInput,
            ]);
        }

        // 🌟 Checkout Page ka direct URL response mein bhej rahe hain taake AJAX redirect kar sake
        return response()->json([
            'status' => true,
            'success' => true,
            'redirect_url' => route('checkout'),
            'message' => 'Redirecting to checkout...',
        ]);
    }

    public function cart()
    {
        // Invalid cart records remove kar do
        Cart::where('user_id', Auth::id())->doesntHave('variant')->delete();

        $carts = Cart::with(['variant.product'])
            ->where('user_id', Auth::id())
            ->get()
            ->filter(function ($cart) {
                return $cart->variant && $cart->variant->product;
            });

        return view('frontend.cart', compact('carts'));
    }

    public function deleteCart($id)
    {
        $cartItem = Cart::find($id);
        if (!$cartItem) {
            return response()->json(['status' => false, 'message' => 'Item not found']);
        }

        $cartItem->delete();

        // Delete hone ke baad bache hue items ka naya total nikalen
        $carts = Cart::with('variant.product')->where('user_id', auth()->id())->get();

        $totalAmount = $carts->sum(function ($c) {
            $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
            return $c->quantity * $price;
        });

        return response()->json([
            'status' => true,
            'message' => 'Removed from cart',
            'total_items' => $carts->count(),
            'total_quantity' => $carts->sum('quantity'),
            'total_amount' => number_format($totalAmount)
        ]);
    }

    public function update(Request $request, $id = null)
    {

        // Agar route se $id nahi aayi to request se utha lein (dono tarah safe)
        $cartId = $id ?? $request->id;

        $cart = Cart::with('variant.product')
            ->where('id', $cartId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Agar request dynamic input quantity la rahi hai (Naya method)
        if ($request->has('quantity')) {
            $requestedQty = intval($request->quantity);
            if ($requestedQty > $cart->variant->stock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stock limit reached. Only ' . $cart->variant->stock . ' available.'
                ], 422);
            }
            $cart->quantity = $requestedQty;
        } else {
            // Purana Type (plus/minus click) fallback logic
            if ($request->type === 'plus') {
                if ($cart->quantity >= $cart->variant->stock) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Stock limit reached'
                    ], 422);
                }
                $cart->quantity += 1;
            }
            if ($request->type === 'minus' && $cart->quantity > 1) {
                $cart->quantity -= 1;
            }
        }

        $cart->save();

        // Variant price check karein, fallback base_price
        $price = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);
        $subtotal = $price * $cart->quantity;

        // CART totals nikalne ke liye saare items dobara calculation mein dalein
        $userCarts = Cart::with('variant.product')
            ->where('user_id', Auth::id())
            ->get();

        $totalItems = $userCarts->count();
        $totalQuantity = $userCarts->sum('quantity');

        $totalAmount = $userCarts->sum(function ($c) {
            $itemPrice = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
            return $c->quantity * $itemPrice;
        });

        // Response keys ko frontend script ke mutabik merge kar diya
        return response()->json([
            'status' => 'success',
            'quantity' => $cart->quantity,
            'new_qty' => $cart->quantity,
            'subtotal' => number_format($subtotal, 0),
            'item_subtotal' => number_format($subtotal, 0),
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'total_qty' => $totalQuantity,
            'total_amount' => number_format($totalAmount, 0),
        ]);
    }

    public function checkout()
    {
        $user_id=Auth::id();
        $carts = Cart::with('variant.product')
            ->where('user_id', $user_id)
            ->get();

        // 1. Pakistan (ID: 167) aur uski states fetch karein
        $country = Country::find(167);
        $states = State::where('country_id', 167)->orderBy('name', 'asc')->get();

        // 2. By default pehli state ki cities bhi get kar lein
        $defaultState = $states->first();
        $cities = collect();

        if ($defaultState) {
            $cities = City::where('state_id', $defaultState->id)->orderBy('name', 'asc')->get();
        }

        $customer_info=CustomerInfo::where('user_id',$user_id)->first();
        if($customer_info){
            if(isset($customer_info->state_id)){
                $cities = City::where('state_id', $customer_info->state_id)->orderBy('name', 'asc')->get();
            }
        }

        return view('frontend.checkout', compact('carts', 'country', 'states', 'cities','customer_info'));
    }

    // AJAX ke liye yeh function add karein
    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function checkoutStore(Request $request)
    {
        // 1️⃣ VALIDATION (Normal validation with integer/numeric checks)
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'postal_code' => 'required|integer',
            'shipping_address' => 'required|string',
            'shipping_method' => 'required|in:standard,express,sameday',
        ]);

        $user_id = Auth::id();

        // Sabhi cart items ko variant aur product ke sath load karein
        $carts = Cart::with('variant.product')
            ->where('user_id', $user_id)
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        // Subtotal calculation
        $subtotal = $carts->sum(function ($cart) {
            $itemPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);
            return $cart->quantity * $itemPrice;
        });

        // 2️⃣ DYNAMIC SHIPPING COST LOGIC
        $shippingRates = [
            'standard' => 0,    // Free Delivery
            'express' => 250,  // 3 Days Delivery
            'sameday' => 500,  // Same Day Delivery
        ];

        $shipping = $shippingRates[$request->shipping_method] ?? 0;
        $discount = 0;
        $total = $subtotal + $shipping - $discount;

        // 3️⃣ CREATE ORDER (IDs aur Postal Code ko strictly integer mein cast kar diya hai)
        $order = Order::create([
            'order_number' => 'ORD-' . time(),
            'user_id' => $user_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'postal_code' => $request->postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_cost' => $shipping,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // 4️⃣ SAVE ORDER ITEMS & DEDUCT STOCK
        foreach ($carts as $cart) {
            $finalPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);

            $variant = ProductVariant::find($cart->variant_id);
            if ($variant) {
                if ($variant->stock < $cart->quantity) {
                    return back()->with('error', ($variant->product->name ?? 'Product') . ' stock not available');
                }
                $variant->decrement('stock', $cart->quantity);
            }

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->variant->product_id ?? $cart->variant->product->id,
                'variant_id' => $cart->variant_id,
                'quantity' => $cart->quantity,
                'price' => $finalPrice,
            ]);
        }

        // 5️⃣ CLEAR CART
        Cart::where('user_id', $user_id)->delete();

        $record = CustomerInfo::where('user_id', $user_id)->first();
        if ($record) {
            $record->update([
                'phone' => $request->phone,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'shipping_address' => $request->shipping_address,
            ]);
        }


        return redirect()->route('thankyou')->with('success', 'Order placed successfully!');
    }

    function thankYou()
    {
        return view('frontend.thankyou');
    }

    public function orders()
    {
        $orders = collect();

        if (Auth::check()) {
            $orders = Order::where('user_id', Auth::user()->id)
                ->latest()
                ->get();
        }

        return view('frontend.orders.index', compact('orders'));
    }

    public function orderDetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('frontend.orders.show', compact('order'));
    }

    public function cancelOrder($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'You can only cancel pending orders');
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Order cancelled successfully');
    }

    public function restoreOrder($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be restored');
        }

        $order->status = 'pending';
        $order->save();

        return back()->with('success', 'Order restored successfully');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        $userId = auth()->id();

        // 1. Check duplicate review
        if (Review::where('user_id', $userId)->where('order_id', $request->order_id)->where('product_id', $request->product_id)->exists()) {
            return response()->json(['message' => 'Aap is product ka review pehle hi de chuke hain.'], 422);
        }

        // 2. Check order eligibility
        $orderExists = Order::where('id', $request->order_id)
            ->where('user_id', $userId)
            ->whereIn('status', ['delivered', 'completed'])
            ->exists();

        if (!$orderExists) {
            return response()->json(['message' => 'Review sirf delivered/completed orders par diya ja sakta hai.'], 403);
        }

        // 3. Save Review & Images
        try {
            DB::transaction(function () use ($request, $userId) {
                $review = Review::create([
                    'user_id' => $userId,
                    'order_id' => $request->order_id,
                    'product_id' => $request->product_id,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'is_approved' => true,
                ]);

                // Simple Direct Loop (createMany ki jagah safely create use karein)
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = $file->store('reviews', 'public');

                        ReviewImage::create([
                            'review_id' => $review->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            });

            return response()->json(['message' => 'Thank you! Your review has been submitted successfully.'], 200);

        } catch (\Exception $e) {
            // Debugging ke liye temporary exact message return karein
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function profile()
    {
        $user = Auth::user();

        // Customer info ko user_id ke zariye fetch karna taake phone aur shipping address show ho sakein
        $userInfo = \App\Models\CustomerInfo::where('user_id', $user->id)->first();

        return view('frontend.user_info', compact('user', 'userInfo'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['nullable', 'string', 'max:1000'],
        ]);

        // 1. Update user name
        $user->name = $request->name;
        $user->save();

        // 2. Update or create customer info (phone & shipping address)
        \App\Models\CustomerInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
            ]
        );

        return redirect()->back()->with('success', 'Profile details updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('password_success', 'Password updated successfully!');
    }

}
