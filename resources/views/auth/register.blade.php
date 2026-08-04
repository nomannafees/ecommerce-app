@extends('frontend.layouts.app')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Register
            </h2>

            <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Hidden Role Input -->
                <input type="hidden" value="customer" name="role">

                <!-- Name -->
                <div class="mb-5">
                    <label for="name"
                           class="block mb-2 text-sm font-medium text-gray-700">
                        Name
                    </label>

                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           autofocus
                           placeholder="Enter your full name"
                           class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all @error('name') border-red-500 @enderror">

                    @error('name')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email"
                           class="block mb-2 text-sm font-medium text-gray-700">
                        Email Address
                    </label>

                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="Enter your email"
                           class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all @error('email') border-red-500 @enderror">

                    @error('email')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password"
                           class="block mb-2 text-sm font-medium text-gray-700">
                        Password
                    </label>

                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all @error('password') border-red-500 @enderror">

                    @error('password')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password-confirm"
                           class="block mb-2 text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>

                    <input id="password-confirm"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                </div>

                <!-- Register Button -->
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md shadow-emerald-600/20 transition duration-300 cursor-pointer">
                    Register
                </button>

            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline">
                        Login here
                    </a>
                </p>
            </div>

        </div>

    </div>

@endsection
