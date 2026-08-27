<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BrandsBanner;
use App\Models\FeaturedBanner;
use App\Models\Banner;
use App\Models\Slider;

class BannerController extends Controller
{
    // 1. Sab banners aik sath get karne ke liye


    // 2. Sirf Brand Banner get karne ke liye
    public function getBrandBanner()
    {
        $brandBanner = BrandsBanner::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $brandBanner
        ], 200);
    }

    // 3. Sirf Featured Banner get karne ke liye
    public function getFeaturedBanner()
    {
        $featuredBanner = FeaturedBanner::latest()->first();

        return response()->json([
            'success' => true,
            'data' => $featuredBanner
        ], 200);
    }

    // 4. Sirf General Banners get karne ke liye
    public function getGeneralBanners()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->take(3)->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ], 200);
    }

    // 5. Sirf Sliders get karne ke liye
    public function getSliders()
    {
        $sliders = Slider::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $sliders
        ], 200);
    }
}