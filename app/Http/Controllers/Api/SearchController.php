<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Product;

class SearchController extends Controller
{
    public function liveSearch(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return response()->json([
                'success' => true,
                'categories' => [],
                'products' => []
            ], 200);
        }

        // Matching Categories
        $categories = Categorie::with('parent.parent')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'name', 'slug', 'parent_id')
            ->limit(5)
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'url' => route('collection', ['category' => $cat->category_path]),
                ];
            });

        $searchTerm = $query;
        $wildcardTerm = '%' . $searchTerm . '%';

        // Matching Products
        $products = Product::with('category.parent.parent')
            ->where(function ($q) use ($searchTerm, $wildcardTerm) {
                $q->where('name', 'LIKE', $wildcardTerm)
                    ->orWhereRaw("LOWER(?) LIKE CONCAT('%', LOWER(name), '%')", [$searchTerm])
                    ->orWhereHas('category', function ($catQuery) use ($wildcardTerm) {
                        $catQuery->where('name', 'LIKE', $wildcardTerm);
                    });
            })
            ->select('id', 'name', 'slug', 'category_id')
            ->orderByRaw("CASE WHEN name LIKE ? THEN 0 ELSE 1 END", [$wildcardTerm])
            ->limit(8)
            ->get()
            ->map(function ($product) {
                $categoryPath = $product->category_path ?? '';

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'url' => route('categories', [
                        'category' => $categoryPath,
                        'product' => $product->slug,
                    ]),
                ];
            });

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'products' => $products,
        ], 200);
    }
}
