@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                {{ !empty($cart) ? 'Edit Cart' : 'Create Cart' }}
            </h2>

            <a href="{{ route('carts.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                List Carts
            </a>

        </div>

        <!-- Form -->
        <form action="{{ !empty($cart) ? route('carts.update', $cart->id) : route('carts.store') }}"
            method="POST">

            @csrf

            @if(!empty($cart))
            @method('PUT')
            @endif

            <div class="grid gap-6 md:grid-cols-2">

                <!-- User -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        User
                    </label>

                    <select name="user_id"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option hidden value="">Select User</option>

                        @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $cart->user_id ?? '') == $user->id ? 'selected' : '' }}>

                            {{ $user->name }}

                        </option>
                        @endforeach

                    </select>
                </div>

                <!-- Variant -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Variant
                    </label>

                    <select name="variant_id"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option hidden value="">Select Variant</option>

                        @foreach($variants as $variant)
                        <option value="{{ $variant->id }}"
                            {{ old('variant_id', $cart->variant_id ?? '') == $variant->id ? 'selected' : '' }}>

                            {{ $variant->product->name ?? 'No Product' }}
                            -
                            {{ $variant->size }}
                            -
                            {{ $variant->color }}

                        </option>
                        @endforeach

                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Quantity
                    </label>

                    <input type="number"
                        min="1"
                        name="quantity"
                        value="{{ old('quantity', $cart->quantity ?? 1) }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('carts.index') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">
                    Cancel
                </a>

                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-xl px-6 py-3">

                    {{ !empty($cart) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>
    ```

</div>

@endsection