<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        $brands = $query->latest()->paginate(5);

        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create_edit');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = storage_path('app/public/brands');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);

            $imagePath = 'brands/' . $filename;
        }

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand created successfully!');
    }




    public function show(Brand $brand)
    {
        return view('brand.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brand.create_edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $brand->image;

        if ($request->hasFile('image')) {

            // Delete old image
            if (!empty($brand->image)) {
                $oldImage = storage_path('app/public/' . $brand->image);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Upload new image
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = storage_path('app/public/brands');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);

            $imagePath = 'brands/' . $filename;
        }


        $slug = Str::slug($request->name);

        $count = Brand::where('slug', 'LIKE', "{$slug}%")
            ->where('id', '!=', $brand->id)
            ->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }


        $brand->update([
            'name'  => $request->name,
            'slug'  => $slug,
            'image' => $imagePath,
        ]);


        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        if (!empty($brand->image)) {

            $image = storage_path('app/public/' . $brand->image);

            if (file_exists($image)) {
                unlink($image);
            }
        }

        $brand->delete();

        return redirect()
            ->route('brands.index')
            ->with('success', 'Brand deleted successfully!');
    }
}
