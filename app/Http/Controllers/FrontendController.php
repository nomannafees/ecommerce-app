<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Categorie;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;


class FrontendController extends Controller
{

    public function index()
    {
        $records = Product::with('variants', 'mainVariantImage')->get();

        // SLIDERS
        $sliders = Slider::latest()->get();

        // 👇 BRANDS DATA FETCH KIYA
        $brands = Brand::latest()->get(); // ya Brand::where('status', 'active')->get(); agar status check karna ho

        $wishlistProductIds = [];

        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }
//dd($records);
        // 👇 compact() ke andar 'brands' variable pass kar diya
        return view('frontend.index', compact(
            'records',
            'sliders',
            'wishlistProductIds',
            'brands'
        ));
    }

    public function productDetail($slug)
    {

        $product = Product::with(['images', 'variants.variantImage'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('frontend.product-detail', compact('product'));
    }

    public function frontendProduct()
    {

        $products = Product::with('variants')->latest()->get();

        $wishlistProductIds = [];

        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.product', compact(
            'products',
            'wishlistProductIds'
        ));
    }


    public function allCategories(Request $request)
    {
        $categories = Categorie::whereNull('parent_id')->get();

        $availableColors = ProductVariant::whereNotNull('color_name')->where('color_name', '!=', '')->distinct()->pluck('color_name')->toArray();
        $availableSizes = ProductVariant::whereNotNull('size')->where('size', '!=', '')->distinct()->pluck('size')->toArray();

        // Brand table se saare brands lekar aayenge dropdown/sidebar ke liye
        $availableBrands = Brand::whereNotNull('name')->where('name', '!=', '')->get();

        // 💡 Eager loading mein 'variant_images' ko bhi shamil kiya taake performance behtar ho
        $query = Product::with(['variants', 'prod_brand', 'mainVariantImage', 'mainVariant', 'variant_images']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $category = null;
        if ($request->filled('category')) {
            $category = Categorie::where('slug', $request->category)->first();
            if ($category) {
                $subCategoryIds = Categorie::where('parent_id', $category->id)->pluck('id')->toArray();
                $categoryIds = array_merge([$category->id], $subCategoryIds);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Price Filters ab 'product_variants' table par chalenge
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        if ($request->filled('color')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('color_name', $request->color);
            });
        }

        if ($request->filled('size')) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('size', $request->size);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('prod_brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Sorting ke liye Join laga kar variants ki price use karenge
        if ($request->filled('sort')) {
            $query->select('products.*');

            if ($request->sort === 'price_low_high') {
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->groupBy('products.id')
                    ->orderBy(DB::raw('MIN(product_variants.price)'), 'asc');

            } elseif ($request->sort === 'price_high_low') {
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->groupBy('products.id')
                    ->orderBy(DB::raw('MAX(product_variants.price)'), 'desc');

            } else {
                $query->latest('products.created_at');
            }
        } else {
            $query->latest();
        }

        $records = $query->paginate(9);

        // 👇 DYNAMIC VARIANT AND IMAGE LOGIC FOR FRONTEND
        $selectedColor = $request->filled('color') ? strtolower(trim($request->color)) : null;

        $records->getCollection()->transform(function ($product) use ($selectedColor) {
            // 1. Matching variant nikalen agar color select ho
            $variant = null;
            if ($selectedColor) {
                $variant = $product->variants->first(function($v) use ($selectedColor) {
                    return strtolower(trim($v->color_name)) === $selectedColor;
                });
            }

            // Fallback variants (agar color select na ho ya match na mile)
            $product->active_variant = $variant ?? ($product->mainVariant ?? $product->variants->first());

            // 2. Image Path logic jo select kiye variant ki image nikalega
            $matchedImage = null;
            if ($product->active_variant) {
                $matchedImage = $product->variant_images->first(function($img) use ($product) {
                    return $img->id == $product->active_variant->variant_image_id;
                });
            }

            $product->custom_image_path = $matchedImage
                ? $matchedImage->image_path
                : ($product->mainVariantImage ? $product->mainVariantImage->image_path : '');

            return $product;
        });

        $wishlistProductIds = [];
        if (Auth::check()) {
            $wishlistProductIds = Wishlist::where('user_id', \Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return view('frontend.categories', compact(
            'categories',
            'records',
            'wishlistProductIds',
            'category',
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
            ]);
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

        $totalAmount = $carts->sum(function($c) {
            $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
            return $c->quantity * $price;
        });

        return response()->json([
            'status'         => true,
            'message'        => 'Removed from cart',
            'total_items'    => $carts->count(),
            'total_quantity' => $carts->sum('quantity'),
            'total_amount'   => number_format($totalAmount)
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
            'status'         => 'success',
            'quantity'       => $cart->quantity,
            'new_qty'        => $cart->quantity,
            'subtotal'       => number_format($subtotal, 0),
            'item_subtotal'  => number_format($subtotal, 0),
            'total_items'    => $totalItems,
            'total_quantity' => $totalQuantity,
            'total_qty'      => $totalQuantity,
            'total_amount'   => number_format($totalAmount, 0),
        ]);
    }

    public function checkout()
    {
        $carts = Cart::with('variant.product')
            ->where('user_id', Auth::id())
            ->get();

        return view('frontend.checkout', compact('carts'));
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'name'             => 'required',
            'phone'            => 'required',
            'email'            => 'required|email',
            'shipping_address' => 'required',
        ]);

        // Sabhi cart items ko variant aur product ke sath load karein
        $carts = Cart::with('variant.product')
            ->where('user_id', Auth::id())
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        // 🔥 FIX: Total aur Subtotal calculation variant price ke mutabik ki
        $subtotal = $carts->sum(function ($cart) {
            $itemPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);
            return $cart->quantity * $itemPrice;
        });

        $shipping = 0;
        $discount = 0;
        $total = $subtotal + $shipping - $discount;

        // 1️⃣ CREATE ORDER (Ab total perfect numeric value jayegi)
        $order = Order::create([
            'order_number'     => 'ORD-' . time(),
            'user_id'          => Auth::id(),
            'name'             => $request->name,
            'phone'            => $request->phone,
            'email'            => $request->email,
            'shipping_address' => $request->shipping_address,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping_cost'    => $shipping,
            'total'            => $total, // Yeh database table me total save karega
            'status'           => 'pending',
            'payment_status'   => 'pending',
        ]);

        // 2️⃣ SAVE ORDER ITEMS
        foreach ($carts as $cart) {
            // Variant price with base price fallback
            $finalPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);

            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cart->variant->product->id,
                'variant_id' => $cart->variant_id,
                'quantity'   => $cart->quantity,
                'price'      => $finalPrice, // Sahi variant price save hogi order details me
            ]);

            // Stock deduct logic
            $variant = ProductVariant::find($cart->variant_id);
            if ($variant) {
                if ($variant->stock < $cart->quantity) {
                    return back()->with('error', $variant->product->name . ' stock not available');
                }
                $variant->stock = $variant->stock - $cart->quantity;
                $variant->save();
            }
        }

        // 3️⃣ CLEAR CART
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('thankyou');
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
}
