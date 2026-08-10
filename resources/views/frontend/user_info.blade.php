@extends('frontend.layouts.app')

@php
    // Toaster popup ko layout level par trigger hone se rokne ke liye:
    if (session()->has('success')) {
        $pageSuccessMessage = session('success');
        session()->forget('success');
    }
@endphp

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-xs text-gray-500 mt-1">Manage your account information and password.</p>
        </div>

        <!-- Global Success Message (Card UI Alert Only) -->
        @if(isset($pageSuccessMessage))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                {{ $pageSuccessMessage }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- LEFT: Personal Information Form -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4">Personal Information</h2>

                <!-- Name Specific Error Alert inside Card -->
                @if($errors->has('name'))
                    <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                        @foreach($errors->get('name') as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('frontend.user_info.update') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2 rounded-xl border @error('name') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror text-xs outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-gray-100 text-xs text-gray-500 cursor-not-allowed">
                        </div>
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-6 py-2.5 rounded-xl font-semibold transition cursor-pointer shadow-md shadow-emerald-600/20">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- RIGHT: Password Update Form -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4">Change Password</h2>

                <!-- Password Specific Error Alert inside Card -->
                @if($errors->has('current_password') || $errors->has('password'))
                    <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                        @if($errors->has('current_password'))
                            @foreach($errors->get('current_password') as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        @endif

                        @if($errors->has('password'))
                            @foreach($errors->get('password') as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                @endif

                <form action="{{ route('frontend.user_info.password') }}" method="POST">
                    @csrf
                    <div class="space-y-3 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2 rounded-xl border @error('current_password') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror text-xs outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2 rounded-xl border @error('password') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror text-xs outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-xs outline-none transition-all">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white text-xs py-2.5 rounded-xl font-semibold transition cursor-pointer shadow-md">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
