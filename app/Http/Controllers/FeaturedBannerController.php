<?php

namespace App\Http\Controllers;

use App\Models\FeaturedBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeaturedBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FeaturedBanner::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('button_name', 'like', "%{$search}%");
        }

        $featuredBanners = $query->latest()->paginate(10);

        return view('featuredbanner.index', compact('featuredBanners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('featuredbanner.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'button_name'    => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'sort_order'     => 'nullable|integer',
            'image'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_title'       => 'nullable|boolean',
            'is_image'       => 'nullable|boolean',
            'is_description' => 'nullable|boolean',
        ]);

        $data = $request->except('image');

        // Set default values for checkboxes if not provided
        $data['is_title']       = $request->input('is_title', 0);
        $data['is_image']       = $request->input('is_image', 0);
        $data['is_description'] = $request->input('is_description', 0);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('featured_banners', 'public');
            $data['image'] = $imagePath;
        }

        FeaturedBanner::create($data);

        return redirect()->route('featuredbanners.index')->with('success', 'Featured Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeaturedBanner $featuredBanner)
    {
        return view('featuredbanner.show', compact('featuredBanner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeaturedBanner $featuredBanner)
    {
        return view('featuredbanner.create_edit', compact('featuredBanner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeaturedBanner $featuredBanner)
    {
        $request->validate([
            'name'           => 'nullable|string|max:255',
            'button_name'    => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'sort_order'     => 'nullable|integer',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_title'       => 'nullable|boolean',
            'is_image'       => 'nullable|boolean',
            'is_description' => 'nullable|boolean',
        ]);

        $data = $request->except('image');

        // Handle visibility switches explicitly so 0 / unchecked values are properly updated
        $data['is_title']       = $request->input('is_title', 0);
        $data['is_image']       = $request->input('is_image', 0);
        $data['is_description'] = $request->input('is_description', 0);

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($featuredBanner->image && Storage::disk('public')->exists($featuredBanner->image)) {
                Storage::disk('public')->delete($featuredBanner->image);
            }

            $imagePath = $request->file('image')->store('featured_banners', 'public');
            $data['image'] = $imagePath;
        }

        $featuredBanner->update($data);

        return redirect()->route('featuredbanners.index')->with('success', 'Banner display settings updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeaturedBanner $featuredBanner)
    {
        // Delete image file from storage
        if ($featuredBanner->image && Storage::disk('public')->exists($featuredBanner->image)) {
            Storage::disk('public')->delete($featuredBanner->image);
        }

        $featuredBanner->delete();

        return redirect()->route('featuredbanners.index')->with('success', 'Featured Banner deleted successfully.');
    }
}