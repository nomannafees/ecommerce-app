@extends('frontend.layouts.app')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

        <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8">

            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Login
            </h2>

            <form method="POST" action="{{ route('login') }}">
            @csrf

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
                           autofocus
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
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all @error('password') border-red-500 @enderror">

                    @error('password')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">

                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               id="remember"
                               {{ old('remember') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer">

                        <span class="ml-2 text-sm text-gray-600">
                            Remember Me
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline font-medium">
                            Forgot Password?
                        </a>
                    @endif

                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md shadow-emerald-600/20 transition duration-300 cursor-pointer">
                    Login
                </button>

            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                       class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline">
                        Create Account
                    </a>
                </p>
            </div>

        </div>

    </div>

@endsection
