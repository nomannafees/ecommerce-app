@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <!-- Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                {{ !empty($variant) ? 'Edit Variant' : 'Create Variant' }}
            </h2>

            <a href="{{ route('variants.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                List Variants
            </a>

        </div>

        <!-- Form -->
        <form action="{{ !empty($variant) ? route('variants.update', $variant->id) : route('variants.store') }}"
            method="POST">

            @csrf

            @if(!empty($variant))
                @method('PUT')
            @endif

            <div class="grid gap-6 md:grid-cols-2">

                <!-- Product -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Product</label>

                    <select name="product_id"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option hidden value="">Select Product</option>

                        @foreach($products as $product)

                        <option value="{{ $product->id }}"
                            {{ old('product_id', $variant->product_id ?? '') == $product->id ? 'selected' : '' }}>

                            {{ $product->name }}

                        </option>

                        @endforeach

                    </select>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">SKU</label>

                    <input type="text"
                        name="sku"
                        value="{{ old('sku', $variant->sku ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3"
                        placeholder="Enter SKU">
                </div>

                <!-- Color -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Color</label>

                    <input type="text"
                        name="color"
                        value="{{ old('color', $variant->color ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3"
                        placeholder="e.g. Red">
                </div>

                <!-- Size -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Size</label>

                    <input type="text"
                        name="size"
                        value="{{ old('size', $variant->size ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3"
                        placeholder="e.g. M, L, XL">
                </div>

                <!-- Price -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Price</label>

                    <input type="number"
                        step="0.01"
                        name="price"
                        value="{{ old('price', $variant->price ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Stock</label>

                    <input type="number"
                        name="stock"
                        value="{{ old('stock', $variant->stock ?? 0) }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

                <!-- Status -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>

                    <select name="status"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option value="1"
                            {{ old('status', $variant->status ?? 1) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('status', $variant->status ?? 0) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('variants.index') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">
                    Cancel
                </a>

                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-xl px-6 py-3">

                    {{ !empty($variant) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection