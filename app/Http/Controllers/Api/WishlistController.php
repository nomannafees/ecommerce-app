<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $wishlists = Wishlist::with(['product.variants.variantImage'])
            ->where('user_id', $user_id)
            ->get();

        return $wishlists;
    }

    public function storeWishList(Request $request)
    {
        $user_id = $request->user_id;

        $wishlist = Wishlist::where('user_id', $user_id)
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
            'user_id' => $user_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => true,
            'action' => 'added',
            'message' => 'Added to wishlist'
        ]);
    }
}
