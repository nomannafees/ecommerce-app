@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <!-- Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                {{ !empty($wishlist) ? 'Edit Wishlist' : 'Create Wishlist' }}
            </h2>

            <a href="{{ route('wishlists.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                List Wishlists
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

                <!-- User -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">User</label>

                    <select name="user_id"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option hidden value="">Select User</option>

                        @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $wishlist->user_id ?? '') == $user->id ? 'selected' : '' }}>

                            {{ $user->name }}
                        </option>
                        @endforeach

                    </select>
                </div>

                <!-- Product -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Product</label>

                    <select name="product_id"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option hidden value="">Select Product</option>

                        @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $wishlist->product_id ?? '') == $product->id ? 'selected' : '' }}>

                            {{ $product->name }}
                        </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('wishlists.index') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">
                    Cancel
                </a>

                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-xl px-6 py-3">

                    {{ !empty($wishlist) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection