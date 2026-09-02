@extends('frontend.layouts.app')

@php
    // Sirf Personal Info update ka success message (password_success alag key hai, is se clash nahi hoga)
    if (session()->has('success')) {
        $pageSuccessMessage = session('success');
    }
@endphp

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-3 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                <p class="text-xs text-gray-500 mt-1">Manage your personal information, phone, address, and account security.</p>
            </div>
        </div>

        <!-- Global Success Message (Sirf Personal Info ke liye) -->
        @if(isset($pageSuccessMessage))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $pageSuccessMessage }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

            <!-- LEFT SIDE: Main Profile Information (Bara Card) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Personal Information</h2>
                        <p class="text-xs text-gray-500">Update your profile details and account information</p>
                    </div>
                </div>

                <form action="{{ route('frontend.user_info.update') }}" method="POST">
                @csrf

                <!-- Input Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border text-xs text-gray-800 outline-none transition-all {{ $errors->has('name') ? 'border-red-500 bg-red-50/20 focus:ring-2 focus:ring-red-500/20' : 'border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500' }}">
                            @error('name')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-100 text-xs text-gray-500 cursor-not-allowed">
                        </div>

                        <!-- Phone Number -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $userInfo->phone ?? '') }}" placeholder="03001234567"
                                   class="w-full px-4 py-2.5 rounded-xl border text-xs text-gray-800 outline-none transition-all {{ $errors->has('phone') ? 'border-red-500 bg-red-50/20 focus:ring-2 focus:ring-red-500/20' : 'border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500' }}">
                            @error('phone')
                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Shipping Address</label>
                        <textarea name="shipping_address" rows="3" placeholder="Enter complete shipping address..."
                                  class="w-full px-4 py-2.5 rounded-xl border text-xs text-gray-800 outline-none transition-all resize-none {{ $errors->has('shipping_address') ? 'border-red-500 bg-red-50/20 focus:ring-2 focus:ring-red-500/20' : 'border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500' }}">{{ old('shipping_address', $userInfo->shipping_address ?? '') }}</textarea>
                        @error('shipping_address')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-6 py-3 rounded-xl shadow-md shadow-emerald-600/20 transition-all inline-flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Save Information</span>
                    </button>
                </form>
            </div>

            <!-- RIGHT SIDE: Password Update Security (Chota Card) -->
            <div class="bg-white rounded-3xl p-6 border mb-8 sm-mb-10 border-gray-100 shadow-sm">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Change Password</h2>
                        <p class="text-xs text-gray-500">Keep your account secure</p>
                    </div>
                </div>

                <!-- Password Success Message (Ab card ke andar hi show hoga) -->
                @if(session('password_success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('password_success') }}</span>
                    </div>
                @endif

            <!-- Password Specific Errors Alert Box -->
                @if($errors->has('current_password') || $errors->has('password'))
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                        @foreach($errors->get('current_password') as $error)
                            <p class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                        @foreach($errors->get('password') as $error)
                            <p class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('frontend.user_info.password') }}" method="POST" >
                    @csrf

                    <div class="space-y-4 mb-6">
                        <!-- Current Password -->
                        <div x-data="{ showCurrentPassword: false }">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Current Password
                            </label>

                            <div class="relative">
                                <input
                                        :type="showCurrentPassword ? 'text' : 'password'"
                                        name="current_password"
                                        required
                                        placeholder="••••••••"
                                        class="w-full px-4 pr-11 py-2.5 rounded-xl border text-xs text-gray-800 outline-none transition-all
            {{ $errors->has('current_password')
                ? 'border-red-500 bg-red-50/20 focus:ring-2 focus:ring-red-500/20'
                : 'border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500' }}">

                                <button
                                        type="button"
                                        @click="showCurrentPassword = !showCurrentPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition">

                                    <!-- Eye Open -->
                                    <i x-show="!showCurrentPassword"
                                       class="fa-regular fa-eye text-sm"></i>

                                    <!-- Eye Slash -->
                                    <i x-show="showCurrentPassword"
                                       x-cloak
                                       class="fa-regular fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div x-data="{ showNewPassword: false }">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                New Password
                            </label>

                            <div class="relative">
                                <input
                                        :type="showNewPassword ? 'text' : 'password'"
                                        name="password"
                                        required
                                        placeholder="••••••••"
                                        class="w-full px-4 pr-11 py-2.5 rounded-xl border text-xs text-gray-800 outline-none transition-all
            {{ $errors->has('password')
                ? 'border-red-500 bg-red-50/20 focus:ring-2 focus:ring-red-500/20'
                : 'border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500' }}">

                                <button
                                        type="button"
                                        @click="showNewPassword = !showNewPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition">

                                    <i x-show="!showNewPassword"
                                       class="fa-regular fa-eye text-sm"></i>

                                    <i x-show="showNewPassword"
                                       x-cloak
                                       class="fa-regular fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div x-data="{ showConfirmPassword: false }">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Confirm New Password
                            </label>

                            <div class="relative">
                                <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        name="password_confirmation"
                                        required
                                        placeholder="••••••••"
                                        class="w-full px-4 pr-11 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">

                                <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition">

                                    <i x-show="!showConfirmPassword"
                                       class="fa-regular fa-eye text-sm"></i>

                                    <i x-show="showConfirmPassword"
                                       x-cloak
                                       class="fa-regular fa-eye-slash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-gray-900 hover:bg-black text-white text-xs font-semibold py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>Update Password</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
