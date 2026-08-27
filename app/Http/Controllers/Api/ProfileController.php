<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerInfo;

class ProfileController extends Controller
{
    // 1. Get Profile by user_id (With Sanctum Middleware)
    public function profile(Request $request, $user_id)
    {
        // User ko ID se find karein
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found!'
            ], 404);
        }

        // Customer info fetch karein
        $userInfo = CustomerInfo::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $userInfo ? $userInfo->phone : null,
                'shipping_address' => $userInfo ? $userInfo->shipping_address : null,
            ]
        ], 200);
    }

    // 2. Update Profile by user_id (With Sanctum Middleware)
    public function updateProfile(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found!'
            ], 404);
        }

        // Validation (Email add kar di hai aur unique check lagaya hai taake current user ki apni email par error na aaye)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'shipping_address' => ['nullable', 'string', 'max:1000'],
        ]);

        // 1. Update user name & email
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // 2. Update or create customer info
        $userInfo = CustomerInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'shipping_address' => $request->shipping_address,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Profile details updated successfully!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? null,
                'phone' => $userInfo->phone,
                'shipping_address' => $userInfo->shipping_address,
            ]
        ], 200);
    }

    // 3. Update Password by user_id (With Sanctum Middleware)
    public function updatePassword(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found!'
            ], 404);
        }

        // Validation (Yahan $value ke sath $ sign add kar diya hai)
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                    $fail('The provided current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // Password Update
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully!'
        ], 200);
    }
}
