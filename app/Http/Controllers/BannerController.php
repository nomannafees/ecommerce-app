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
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_title' => 'nullable|boolean',
            'is_image' => 'nullable|boolean',
            'is_description' => 'nullable|boolean',
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
            'sort_order' => $request->sort_order ?? 1,
            'image' => $imagePath,
            'is_title' => $request->has('is_title') ? $request->is_title : 1,
            'is_image' => $request->has('is_image') ? $request->is_image : 1,
            'is_description' => $request->has('is_description') ? $request->is_description : 1,
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
//        dd($request);
        $request->validate([
            'name'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_title' => 'nullable|boolean',
            'is_image' => 'nullable|boolean',
            'is_description' => 'nullable|boolean',
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

        // Prepare update data array (Include name and description so they don't get lost)
        $updateData = [
            'name'  => $request->input('name', $banner->name),
            'description' => $request->input('description', $banner->description),
            'image' => $imagePath,
            'is_title' => $request->input('is_title', 0),
            'is_image' => $request->input('is_image', 0),
            'is_description' => $request->input('is_description', 0),
        ];

        // Agar form mein sort_order aa raha hai toh update karein warna purana rakhein
        if ($request->filled('sort_order')) {
            $updateData['sort_order'] = $request->sort_order;
        }

        $banner->update($updateData);

        return redirect()
            ->route('banners.index')
            ->with('success', 'Banner display settings updated successfully!');
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