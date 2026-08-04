<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart(Request $request)
    {
//        dd($request->all());
        // Invalid cart records remove kar do
        $user_id = $request->user_id;
//        Cart::where('user_id', $user_id)->doesntHave('variant')->delete();

        $carts = Cart::where('user_id', $user_id)->with(['variant.variantImage'])
            ->get()
            ->filter(function ($cart) {
                return $cart->variant && $cart->variant->product;
            });

        return $carts;
    }

    function cartRemove($id)
    {
        $cartItem = Cart::find($id);

        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Removed from cart',
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
//        dd($request->quantity);
        // 1. Validation (Quantity kam se kam 1 honi chahiye)
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user_id = $request->user_id;

        // 2. Cart item find karein
        $cartItem = Cart::where('user_id', $user_id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        // 3. Quantity update karein
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // 4. Updated totals calculate karein (Bilkul delete wale logic ki tarah)
        $carts = Cart::with('variant.product')->where('user_id', $user_id)->get();

        $totalAmount = $carts->sum(function ($c) {
            $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
            return $c->quantity * $price;
        });

        // 5. Response send karein
        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully',
            'item' => [
                'id' => $cartItem->id,
                'item_quantity' => $cartItem->quantity,
                'item_total' => ($cartItem->variant->price ?? ($cartItem->variant->product->base_price ?? 0)) * $cartItem->quantity
            ],
            'total_items' => $carts->count(),
            'total_quantity' => $carts->sum('quantity'),
            'total_amount' => number_format($totalAmount, 2)
        ]);
    }

}
