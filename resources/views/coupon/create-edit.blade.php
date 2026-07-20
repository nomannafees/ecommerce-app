@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <!-- Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                {{ !empty($coupon) ? 'Edit Coupon' : 'Create Coupon' }}
            </h2>

            <a href="{{ route('coupons.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                List Coupons
            </a>

        </div>

        <!-- Form -->
        <form action="{{ !empty($coupon) ? route('coupons.update', $coupon->id) : route('coupons.store') }}"
            method="POST">

            @csrf

            @if(!empty($coupon))
                @method('PUT')
            @endif

            <div class="grid gap-6 md:grid-cols-2">

                <!-- Code -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Coupon Code</label>

                    <input type="text"
                        name="code"
                        value="{{ old('code', $coupon->code ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3"
                        placeholder="e.g. SAVE10">
                </div>

                <!-- Type -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Type</label>

                    <select name="type"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option value="percentage"
                            {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>
                            Percentage
                        </option>

                        <option value="fixed"
                            {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>
                            Fixed
                        </option>

                    </select>
                </div>

                <!-- Value -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Value</label>

                    <input type="number"
                        step="0.01"
                        name="value"
                        value="{{ old('value', $coupon->value ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

                <!-- Min Cart Amount -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Min Cart Amount</label>

                    <input type="number"
                        step="0.01"
                        name="min_cart_amount"
                        value="{{ old('min_cart_amount', $coupon->min_cart_amount ?? 0) }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

                <!-- Expire Date -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Expire Date</label>

                    <input type="date"
                        name="expire_date"
                        value="{{ old('expire_date', $coupon->expire_date ?? '') }}"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">
                </div>

                <!-- Status -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>

                    <select name="status"
                        class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3">

                        <option value="1"
                            {{ old('status', $coupon->status ?? 1) == 1 ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('status', $coupon->status ?? 0) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('coupons.index') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">
                    Cancel
                </a>

                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-xl px-6 py-3">

                    {{ !empty($coupon) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection