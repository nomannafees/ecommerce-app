@extends('frontend.layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
            Register
        </h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
                <input  hidden type="text"  value="customer" name="role">
            <div class="mb-5">
                <label for="name"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Name
                </label>

                <input id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    autofocus
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-500 @else border-gray-300 @enderror">

                @error('name')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-5">
                <label for="email"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address
                </label>

                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @else border-gray-300 @enderror">

                @error('email')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>

                <input id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-500 @else border-gray-300 @enderror">

                @error('password')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password-confirm"
                    class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password
                </label>

                <input id="password-confirm"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Register Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300">
                Register
            </button>

        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="text-blue-600 font-semibold hover:underline">
                    Login here
                </a>
            </p>
        </div>

    </div>

</div>

@endsection
