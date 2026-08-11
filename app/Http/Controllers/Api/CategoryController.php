<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categorie::where('parent_id', 0)
            ->with('children.children')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories
        ], 200);
    }

    public function categoriesProduct(Request $request, $id = null)
    {
        // Parent categories with their children
        $categories = Categorie::where('parent_id', 0)->with('children')->get();

        $currentCategory = null;
        $query = Product::with([
            'variants',
            'prod_brand',
            'mainVariantImage',
            'mainVariant',
            'variant_images',
            'reviews'
        ])->latest();

        // Yahan $category ki jagah $id use hoga aur slug ki jagah id se search hoga
        if (!empty($id)) {
            $currentCategory = Categorie::where('id', $id)->with('children.children')->first();

            if ($currentCategory) {
                $getAllIds = function ($cat) use (&$getAllIds) {
                    $ids = [];
                    foreach ($cat->children as $child) {
                        $ids[] = $child->id;
                        if ($child->children->isNotEmpty()) {
                            $ids = array_merge($ids, $getAllIds($child));
                        }
                    }
                    return $ids;
                };

                $categoryIds = array_merge([$currentCategory->id], $getAllIds($currentCategory));
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->get();

        // JSON response return karwana API ke liye
        return response()->json([
            'success' => true,
            'categories' => $categories,
            'current_category' => $currentCategory,
            'products' => $products
        ], 200);
    }
}
