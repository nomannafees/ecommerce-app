@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ !empty($wishlist) ? 'Edit Wishlist' : 'Create Wishlist' }}
                </h2>

                <!-- Header Action Button -->
                <a href="{{ route('wishlists.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Wishlists</span>
                </a>

            </div>

            <!-- Form -->
            <form action="{{ !empty($wishlist) ? route('wishlists.update', $wishlist->id) : route('wishlists.store') }}"
                  method="POST">

                @csrf

                @if(!empty($wishlist))
                    @method('PUT')
                @endif

                <div class="grid gap-6 md:grid-cols-2">

                    <!-- User Dropdown (Floating Border Label Style) -->
                    <div class="relative w-full">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
        <i class="fa-solid fa-user text-sm"></i>
    </span>

                        <select name="user_id"
                                id="wishlist_user"
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('user_id', $wishlist->user_id ?? '') == '' ? 'selected' : '' }}>
                                Select User
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $wishlist->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                        <label for="wishlist_user"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-10 transition-colors duration-200">
                            User
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
        <i class="fa-solid fa-chevron-down text-xs"></i>
    </span>

                        @error('user_id')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Dropdown (Floating Border Label Style) -->
                    <div class="relative w-full">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
        <i class="fa-solid fa-box text-sm"></i>
    </span>

                        <select name="product_id"
                                id="wishlist_product"
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('product_id', $wishlist->product_id ?? '') == '' ? 'selected' : '' }}>
                                Select Product
                            </option>

                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id', $wishlist->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach

                        </select>

                        <label for="wishlist_product"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-10 transition-colors duration-200">
                            Product
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
        <i class="fa-solid fa-chevron-down text-xs"></i>
    </span>

                        @error('product_id')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bottom Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-8 mt-8">

                    <a href="{{ route('wishlists.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200 cursor-pointer text-sm group">
                        <i class="fa-solid fa-xmark text-gray-500 text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer group active:scale-[0.98]">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ !empty($wishlist) ? 'Update' : 'Save' }}</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
