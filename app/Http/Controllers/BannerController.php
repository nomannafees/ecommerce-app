<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Sort order ya latest ke mutabik list fetch karna
        $banners = $query->orderBy('sort_order', 'asc')->latest()->paginate(5);

        return view('banner.index', compact('banners'));
    }

    public function create()
    {
        return view('banner.create_edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|in:1,2,3', // Sort order validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = storage_path('app/public/banners');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);
            $imagePath = 'banners/' . $filename;
        }

        Banner::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order, // Save sort order
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner created successfully!');
    }

    public function show(Banner $banner)
    {
        return view('banner.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('banner.create_edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|in:1,2,3', // Sort order validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imagePath = $banner->image;

        if ($request->hasFile('image')) {
            // Delete old image
            if (!empty($banner->image)) {
                $oldImage = storage_path('app/public/' . $banner->image);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Upload new image
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = storage_path('app/public/banners');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);
            $imagePath = 'banners/' . $filename;
        }

        $banner->update([
            'name'  => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order, // Update sort order
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        if (!empty($banner->image)) {
            $image = storage_path('app/public/' . $banner->image);

            if (file_exists($image)) {
                unlink($image);
            }
        }

        $banner->delete();

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner deleted successfully!');
    }
}
