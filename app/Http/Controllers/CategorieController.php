<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $query = Categorie::with('parent');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('categorie.index', compact('categories'));
    }

    public function create()
    {
        $parent_data = Categorie::whereNull('parent_id')->get();

        return view('categorie.create-edit', compact('parent_data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Categorie::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categorie.index');
    }

    public function edit(Categorie $categorie)
    {
        $edit_data = Categorie::whereNull('parent_id')->get();

        return view('categorie.create-edit', compact('edit_data', 'categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorie->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categorie.index');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('categorie.index');
    }
}
