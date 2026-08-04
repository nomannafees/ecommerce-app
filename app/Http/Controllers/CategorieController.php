<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        // Query start karein
        $query = Categorie::query()->with(['parent.parent']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Duplicate rows hone se bachane ke liye aur clean 5 items paginate karne ke liye
        $record = $query->latest('id')->paginate(5);

        return view('categorie.index', compact('record'));
    }

    public function create()
    {
        $parent_data = Categorie::where('parent_id', 0)->with('allChildren')->get();
        return view('categorie.create-edit', compact('parent_data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Categorie::create([
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?? 0,
            'slug'      => Str::slug($request->name),
        ]);

        return redirect()->route('categorie.index')->with('success', 'Category created successfully!');
    }

    public function edit(Categorie $categorie)
    {
        $parent_data = Categorie::where('parent_id', 0)->with('allChildren')->get();
        return view('categorie.create-edit', compact('parent_data', 'categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorie->update([
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?? 0,
            'slug'      => Str::slug($request->name),
        ]);

        return redirect()->route('categorie.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return redirect()->route('categorie.index')->with('success', 'Category deleted successfully!');
    }
}
