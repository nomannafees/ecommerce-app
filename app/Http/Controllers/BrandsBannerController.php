<?php

namespace App\Http\Controllers;

use App\Models\BrandsBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandsBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = BrandsBanner::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('button_name', 'like', "%{$search}%");
        }

        $brandsBanners = $query->latest()->paginate(10);

        return view('brandbanner.index', compact('brandsBanners'));
    }

    public function create()
    {
        return view('brandbanner.create_edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'button_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands_banners', 'public');
            $data['image'] = $imagePath;
        }

        BrandsBanner::create($data);

        return redirect()->route('brandbanners.index')->with('success', 'Brands Banner created successfully.');
    }

    public function show(BrandsBanner $brandsBanner)
    {
        //
    }

    public function edit(BrandsBanner $brandsBanner)
    {
        return view('brandbanner.create_edit', compact('brandsBanner'));
    }

    public function update(Request $request, BrandsBanner $brandsBanner)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'button_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($brandsBanner->image && Storage::disk('public')->exists($brandsBanner->image)) {
                Storage::disk('public')->delete($brandsBanner->image);
            }

            $imagePath = $request->file('image')->store('brands_banners', 'public');
            $data['image'] = $imagePath;
        }

        $brandsBanner->update($data);

        return redirect()->route('brandbanners.index')->with('success', 'Brands Banner updated successfully.');
    }

    public function destroy($id)
    {
        $brandsBanner = BrandsBanner::findOrFail($id);

        if ($brandsBanner->image && Storage::disk('public')->exists($brandsBanner->image)) {
            Storage::disk('public')->delete($brandsBanner->image);
        }

        $brandsBanner->delete();

        return redirect()->route('brandbanners.index')->with('success', 'Brands Banner deleted successfully.');
    }
}
