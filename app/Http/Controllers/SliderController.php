<?php

namespace App\Http\Controllers;

use App\Models\Slider;
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

        $sliders = $query->latest()->paginate(10);
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
    public function store(Request $request)
    {
        $request->validate([
            'heading'        => 'nullable|string',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imagePath = str_replace('\\', '/', $file->store('sliders', 'public'));
        }

        Slider::create([
            'heading'        => $request->heading,
            'description'    => $request->description,
            'image'          => $imagePath,
            'is_title'       => $request->has('is_title') ? 1 : 0,
            'is_image'       => $request->has('is_image') ? 1 : 0,
            'is_description' => $request->has('is_description') ? 1 : 0,
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
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'heading'        => 'nullable|string',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $slider->image;

        if ($request->hasFile('image')) {
            if (!empty($slider->image) && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $file = $request->file('image');
            $imagePath = str_replace('\\', '/', $file->store('sliders', 'public'));
        }

        $slider->update([
            'heading'        => $request->heading,
            'description'    => $request->description,
            'image'          => $imagePath,
            'is_title'       => $request->input('is_title', 0),
            'is_image'       => $request->input('is_image', 0),
            'is_description' => $request->input('is_description', 0),
        ]);

        return redirect()->route('sliders.index')->with('success', 'Slider visibility settings updated successfully.');
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
