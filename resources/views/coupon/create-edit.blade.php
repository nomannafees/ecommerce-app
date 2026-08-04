@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ !empty($coupon) ? 'Edit Coupon' : 'Create Coupon' }}
                </h2>

                <!-- Header Action Button -->
                <a href="{{ route('coupons.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Coupons</span>
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

                    <!-- Coupon Code -->
                    <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                        <i class="fa-solid fa-ticket text-sm"></i>
                    </span>

                        <input type="text"
                               name="code"
                               id="coupon_code"
                               value="{{ old('code', $coupon->code ?? '') }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="coupon_code"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Coupon Code (e.g. SAVE10)
                        </label>

                        @error('code')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type (Fixed Floating Border Label) -->
                    <div class="relative w-full">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
        <i class="fa-solid fa-percent text-sm"></i>
    </span>

                        <select name="type"
                                id="coupon_type"
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="percentage"
                                {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>

                            <option value="fixed"
                                {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>
                                Fixed Amount
                            </option>

                        </select>

                        <label for="coupon_type"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-10 transition-colors duration-200">
                            Type
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
        <i class="fa-solid fa-chevron-down text-xs"></i>
    </span>

                        @error('type')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                        <i class="fa-solid fa-coins text-sm"></i>
                    </span>

                        <input type="number"
                               step="0.01"
                               name="value"
                               id="coupon_value"
                               value="{{ old('value', $coupon->value ?? '') }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="coupon_value"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Discount Value
                        </label>

                        @error('value')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Min Cart Amount -->
                    <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                        <i class="fa-solid fa-cart-shopping text-sm"></i>
                    </span>

                        <input type="number"
                               step="0.01"
                               name="min_cart_amount"
                               id="coupon_min_cart"
                               value="{{ old('min_cart_amount', $coupon->min_cart_amount ?? 0) }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="coupon_min_cart"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Min Cart Amount
                        </label>

                        @error('min_cart_amount')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expire Date (Fixed Floating Border Label) -->
                    <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                        <i class="fa-solid fa-calendar-days text-sm"></i>
                    </span>

                        <input type="date"
                               name="expire_date"
                               id="coupon_expire"
                               value="{{ old('expire_date', $coupon->expire_date ?? '') }}"
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="coupon_expire"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-10 transition-colors duration-200">
                            Expire Date
                        </label>

                        @error('expire_date')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status (Fixed Floating Border Label) -->
                    <div class="relative w-full">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
        <i class="fa-solid fa-toggle-on text-sm"></i>
    </span>

                        <select name="status"
                                id="coupon_status"
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="1"
                                {{ old('status', $coupon->status ?? 1) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status', $coupon->status ?? 0) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <label for="coupon_status"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-10 transition-colors duration-200">
                            Status
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
        <i class="fa-solid fa-chevron-down text-xs"></i>
    </span>

                        @error('status')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bottom Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-8 mt-8 border-t border-gray-100">

                    <a href="{{ route('coupons.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200 cursor-pointer text-sm group">
                        <i class="fa-solid fa-xmark text-gray-500 text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer group active:scale-[0.98]">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ !empty($coupon) ? 'Update' : 'Save' }}</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
