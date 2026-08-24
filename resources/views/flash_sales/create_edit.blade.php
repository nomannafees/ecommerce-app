@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

            @php
                $isEdit = isset($flashSale) && $flashSale->id;
                $actionUrl = $isEdit ? route('admin.flash-sales.update', $flashSale->id) : route('admin.flash-sales.store');
            @endphp

            <h2 class="text-xl font-bold mb-6 text-gray-800">
                {{ $isEdit ? 'Edit Flash Sale' : 'Add Product to Flash Sale' }}
            </h2>

            <form action="{{ $actionUrl }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Product Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Product</label>
                        <select name="product_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" required>
                            <option value="">-- Choose a Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ (isset($flashSale) && $flashSale->product_id == $product->id) ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku ?? 'No SKU' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Discount Percentage -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Discount Percentage (%)</label>
                        <input type="number" name="discount_percentage" min="1" max="99"
                               value="{{ old('discount_percentage', $flashSale->discount_percentage ?? '') }}"
                               placeholder="e.g 20"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" required>
                        <p class="text-[11px] text-gray-500 mt-1">This % will be deducted from all variants of this product.</p>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                        <input type="datetime-local" name="start_time"
                               value="{{ old('start_time', isset($flashSale->start_time) ? $flashSale->start_time->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                        <input type="datetime-local" name="end_time"
                               value="{{ old('end_time', isset($flashSale->end_time) ? $flashSale->end_time->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all">
                    {{ $isEdit ? 'Update Flash Sale' : 'Save Flash Sale' }}
                </button>
            </form>
        </div>
    </div>
@endsection