<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(Request $request)
    {

        $query = Slider::query();

        // Wishlists interface ke mutabik search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('heading', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }

        $sliders = $query->latest()->paginate(5);
        return $sliders;
    }
}
