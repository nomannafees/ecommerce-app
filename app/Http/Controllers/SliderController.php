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

        // Wishlists interface ke mutabik search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('heading', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        }

        $sliders = $query->latest()->paginate(5);
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
            'heading' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = storage_path('app/public/sliders');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);

            $imagePath = 'sliders/' . $filename;
        }

        Slider::create([
            'heading' => $request->heading,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
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
            'heading' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $slider->image;


        if ($request->hasFile('image')) {


            // Delete old image
            if (!empty($slider->image)) {

                $oldImage = storage_path('app/public/' . $slider->image);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }


            // Upload new image
            $file = $request->file('image');

            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = storage_path('app/public/sliders');

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file->move($folder, $filename);

            $imagePath = 'sliders/' . $filename;
        }


        $slider->update([
            'heading' => $request->heading,
            'description' => $request->description,
            'image' => $imagePath,
        ]);


        return redirect()
            ->route('sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        if (!empty($slider->image)) {
            $image = storage_path('app/public/' . $slider->image);
            if (file_exists($image)) {
                unlink($image);
            }
        }

        $slider->delete();

        return redirect()
            ->route('sliders.index');
    }
}
