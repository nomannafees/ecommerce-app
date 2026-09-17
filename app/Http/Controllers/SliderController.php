<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource with Search.
     */
    public function index(Request $request)
    {
        $query = Slider::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('heading', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }

        $sliders = $query->orderBy('sort_order', 'asc')->latest()->paginate(10);
        return view('slider.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('slider.create-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        $request->validate([
            'heading'        => 'nullable|string',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order'     => 'nullable|integer',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $imageService->processAndStoreWithoutCrop($request->file('image'), 'sliders', 1900, 475, 80);
        }

        Slider::create([
            'heading'        => $request->heading,
            'description'    => $request->description,
            'image'          => $imagePath,
            'is_title'       => $request->has('is_title') ? 1 : 0,
            'is_image'       => $request->has('is_image') ? 1 : 0,
            'is_description' => $request->has('is_description') ? 1 : 0,
            'is_active'      => $request->has('is_active') ? 1 : 0,
            'sort_order'     => $request->input('sort_order', 0),
        ]);

        return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return view('slider.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('slider.create-edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, ImageService $imageService)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'heading'        => 'nullable|string',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'sort_order'     => 'nullable|integer',
        ]);

        $imagePath = $slider->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($slider->image)) {
                $oldImage = storage_path('app/public/' . $slider->image);
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            // Process new image
            $imagePath = $imageService->processAndStoreWithoutCrop($request->file('image'), 'sliders', 1900, 475, 80);
        }

        // Sirf wahi data update hoga jo form se aayega, baaki purana data secure rahega
        // Sirf wahi data update hoga jo form se aayega, baaki purana data secure rahega
        $slider->update([
            'heading'        => $request->has('heading') ? $request->heading : $slider->heading,
            'description'    => $request->has('description') ? $request->description : $slider->description,
            'image'          => $imagePath,
            'is_title'       => $request->has('is_title') ? $request->input('is_title') : $slider->is_title,
            'is_image'       => $request->has('is_image') ? $request->input('is_image') : $slider->is_image,
            'is_description' => $request->has('is_description') ? $request->input('is_description') : $slider->is_description,

            // Yahan badlaav kiya hai: Checkbox ke liye boolean method use karein
            'is_active'      => $request->boolean('is_active') ? 1 : 0,

            'sort_order'     => $request->has('sort_order') ? $request->input('sort_order') : $slider->sort_order,
        ]);

        return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Delete image from public storage
        if (!empty($slider->image) && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()
            ->route('sliders.index')
            ->with('success', 'Slider deleted successfully.');
    }
}