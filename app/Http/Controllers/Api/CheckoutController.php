<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function checkoutStore(Request $request)
    {
        $user_id = $request->user_id;
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255',
            'shipping_address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }



        // 2. Fetch User Cart
        $carts = Cart::with('variant.product')
            ->where('user_id', $user_id)
            ->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // 3. Stock Check
            foreach ($carts as $cart) {
                if (!$cart->variant || $cart->variant->stock < $cart->quantity) {
                    return response()->json([
                        'status'  => false,
                        'message' => ($cart->variant->product->name ?? 'Product') . ' is out of stock.'
                    ], 400);
                }
            }

            // 4. Calculate Subtotal & Total
            $subtotal = $carts->sum(function ($cart) {
                $itemPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);
                return $cart->quantity * $itemPrice;
            });

            $shipping = 0;
            $discount = 0;
            $total = $subtotal + $shipping - $discount;

            // 5. Create Order
            $order = Order::create([
                'order_number'     => 'ORD-' . time(),
                'user_id'          => $user_id,
                'name'             => $request->name,
                'phone'            => $request->phone,
                'email'            => $request->email,
                'shipping_address' => $request->shipping_address,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'shipping_cost'    => $shipping,
                'total'            => $total,
                'status'           => 'pending',
                'payment_status'   => 'pending',
            ]);

            // 6. Create Order Items & Decrement Stock
            foreach ($carts as $cart) {
                $finalPrice = $cart->variant->price ?? ($cart->variant->product->base_price ?? 0);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cart->variant->product->id ?? null,
                    'variant_id' => $cart->variant_id,
                    'quantity'   => $cart->quantity,
                    'price'      => $finalPrice,
                ]);

                // Stock Deduct
                $cart->variant->decrement('stock', $cart->quantity);
            }

            // 7. Clear Cart
            Cart::where('user_id', $user_id)->delete();

            DB::commit();

            return response()->json([
                'status'       => true,
                'message'      => 'Order placed successfully!',
                'order_id'     => $order->id,
                'order_number' => $order->order_number
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Failed to process order.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
