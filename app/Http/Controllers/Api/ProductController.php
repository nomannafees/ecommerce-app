<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $records = Product::with('variants', 'mainVariantImage')->get();
        return response()->json([
            'status' => true,
            'data' => $records
        ], 200);
    }

    public function productDetail($slug)
    {


        $product = Product::with(['images', 'variants.variantImage'])
            ->where('slug', $slug)
            ->first();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);

    }
}
