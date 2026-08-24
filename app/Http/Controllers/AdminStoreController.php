<?php

namespace App\Http\Controllers;

use App\Models\AdminStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = AdminStore::first();

        return view('admin_store.index', compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store or Update Store Settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'                => 'required|string|max:255',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:500',
            'logo'                 => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'is_logo'              => 'nullable|boolean',
            'is_title'             => 'nullable|boolean',
            'is_sliders'           => 'nullable|boolean',
            'show_mid_banners'     => 'nullable|boolean',
            'show_featured_banner' => 'nullable|boolean',
            'show_brand_banner'    => 'nullable|boolean',
        ]);

        $store = AdminStore::first() ?? new AdminStore();

        if ($request->hasFile('logo')) {
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }

            $logoPath = $request->file('logo')->store('store_logos', 'public');
            $store->logo = $logoPath;
        }

        $store->title                = $request->title;
        $store->email                = $request->email;
        $store->phone                = $request->phone;
        $store->address              = $request->address;

        // Toggles mapping
        $store->is_logo              = $request->has('is_logo') ? true : false;
        $store->is_title             = $request->has('is_title') ? true : false;
        $store->is_sliders           = $request->has('is_sliders') ? true : false;
        $store->show_mid_banners     = $request->has('show_mid_banners') ? true : false;
        $store->show_featured_banner = $request->has('show_featured_banner') ? true : false;
        $store->show_brand_banner    = $request->has('show_brand_banner') ? true : false;

        $store->save();

        return redirect()->back()->with('success', 'Store settings updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminStore $adminStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminStore $adminStore)
    {
        //
    }

    /**
     * Update Store Settings via Resource Route.
     */
    public function update(Request $request, $id)
    {
        return $this->store($request);
    }

    /**
     * Remove Logo Image via AJAX/Resource Route.
     */
    public function destroy($id)
    {
        $store = AdminStore::find($id) ?? AdminStore::first();

        if ($store && $store->logo) {
            if (Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }

            $store->logo = null;
            $store->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Store logo deleted successfully!'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'No logo found to delete.'
        ], 404);
    }
}