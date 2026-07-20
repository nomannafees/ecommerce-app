<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function addToCart(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|integer|exists:product_variants,id',
            'qty' => 'required|integer|min:1',
        ]);
        // Agar validation fail ho jaye to error response return karein
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // Pehla error message dikhane ke liye
                'errors' => $validator->errors() // Tamam errors ki list
            ], 422); // 422 Unprocessable Entity status code
        }


        $user_id = 1;

        $variant = ProductVariant::find($request->variant_id);


        if (!$variant) {
            return response()->json([
                'status' => false,
                'message' => 'Variant not found'
            ]);
        }


        $cart = Cart::where('user_id', $user_id)
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
                'user_id' => $user_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->qty,
            ]);
        }

        $cartCount = Cart::where('user_id', $user_id)->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => 'Added to cart successfully',
            'cartCount' => $cartCount,
        ]);
    }
}
