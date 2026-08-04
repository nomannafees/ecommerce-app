<?php

namespace App\Http\Controllers;

use App\Models\AdminInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $adminInfo = AdminInfo::where('user_id', $user->id)->first();

        return view('admin_info.index', compact('user', 'adminInfo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store or Update Personal/Financial Details.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validation
        $request->validate([
            'name'         => 'required|string|max:255',
            'jazzcash_no'  => 'nullable|string|max:20',
            'easypaisa_no' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 2. Update User Name
        $user->name = $request->name;
        $user->save();

        // 3. AdminInfo Record Get ya Create karein
        $adminInfo = AdminInfo::firstOrNew(['user_id' => $user->id]);

        // 4. Image Uploading
        if ($request->hasFile('image')) {
            // Purani image delete karein agar exist karti ho
            if ($adminInfo->image && Storage::disk('public')->exists($adminInfo->image)) {
                Storage::disk('public')->delete($adminInfo->image);
            }

            // Nayi image upload karein
            $imagePath = $request->file('image')->store('admin_profiles', 'public');
            $adminInfo->image = $imagePath;
        }

        // 5. Baqi fields save karein
        $adminInfo->jazzcash_no  = $request->jazzcash_no;
        $adminInfo->easypaisa_no = $request->easypaisa_no;
        $adminInfo->address      = $request->address;
        $adminInfo->save();

        return redirect()->back()->with('success', 'Profile information updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminInfo $adminInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminInfo $adminInfo)
    {
        //
    }

    /**
     * Update Password or General Info via Resource Route.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Agar Request mein Password Change ka Data Aaya Hai
        if ($request->has('current_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'password'         => ['required', 'confirmed', Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully!');
        }

        // Default: Store method par delegate karein
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $adminInfo = AdminInfo::where('user_id', Auth::id())->first();

        if ($adminInfo && $adminInfo->image) {
            // Delete file from public storage disk
            if (Storage::disk('public')->exists($adminInfo->image)) {
                Storage::disk('public')->delete($adminInfo->image);
            }

            // Set image field to null
            $adminInfo->image = null;
            $adminInfo->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Profile picture deleted successfully!'
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'No image found to delete.'
        ], 404);
    }
}
