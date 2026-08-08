<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
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
}
