<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * User ke sabhi orders fetch karne ke liye API
     */
    public function orders(Request $request)
    {
        // Auth token se ID uthayen, agar nahi mila toh query parameter se ($request->user_id)
        $user_id = Auth::id() ?? $request->query('user_id') ?? $request->user_id;

        if (!$user_id) {
            return response()->json([
                'status'  => false,
                'message' => 'User ID is required or user is not authenticated.'
            ], 401);
        }

        // User ke orders fetch karein (latest first)
        $orders = Order::where('user_id', $user_id)
            ->latest()
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Orders retrieved successfully',
            'data'    => $orders
        ], 200);
    }

    /**
     * Add to cart API
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|integer|exists:product_variants,id',
            'qty'        => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $user_id = Auth::id() ?? $request->query('user_id') ?? $request->user_id;

        if (!$user_id) {
            return response()->json([
                'status'  => false,
                'message' => 'User ID is required or user is not authenticated.'
            ], 401);
        }

        $variant = ProductVariant::find($request->variant_id);

        if (!$variant) {
            return response()->json([
                'status'  => false,
                'message' => 'Variant not found'
            ], 404);
        }

        $cart = Cart::where('user_id', $user_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        $currentQty = $cart ? $cart->quantity : 0;
        $newQty = $currentQty + $request->qty;

        if ($newQty > $variant->stock) {
            return response()->json([
                'status'  => false,
                'message' => 'Only ' . $variant->stock . ' items available in stock'
            ], 400);
        }

        if ($cart) {
            $cart->quantity = $newQty;
            $cart->save();
        } else {
            Cart::create([
                'user_id'    => $user_id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->qty,
            ]);
        }

        $cartCount = Cart::where('user_id', $user_id)->sum('quantity');

        return response()->json([
            'status'    => true,
            'message'   => 'Added to cart successfully',
            'cartCount' => $cartCount,
        ], 200);
    }

    /**
     * Order Details Fetch karne ke liye API
     */
    /**
     * Order Details Fetch karne ke liye API
     */
    public function orderDetail(Request $request, $id)
    {
        // 1. User ID check (Token support + Query Param support - FIXED)
        $user_id = Auth::id() ?? $request->query('user_id') ?? $request->user_id;

        if (!$user_id) {
            return response()->json([
                'status'  => false,
                'message' => 'User ID is required or user is not authenticated.'
            ], 401);
        }

        // 2. Fetch order with items, product, images, and variant details
        $order = Order::with([
            'items.product.images'              // Variant details
        ])
            ->where('user_id', $user_id)
            ->find($id);

        // 3. Agar Order na mile ya yeh Order kisi dusre user ka ho
        if (!$order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found for this user.'
            ], 404);
        }

        // 4. Return complete response with images
        return response()->json([
            'status'  => true,
            'message' => 'Order details retrieved successfully.',
            'data'    => $order
        ], 200);
    }
}
