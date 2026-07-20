@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto px-4 py-10">

        <div class="text-center mb-10 max-w-2xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 tracking-tight">
                Checkout
            </h1>
            <div class="w-12 h-0.5 bg-green-700 mx-auto mt-3 rounded-full opacity-80"></div>
            <p class="text-gray-500 text-sm md:text-base mt-4 leading-relaxed font-normal">
                Please verify your delivery address and order details to complete your purchase.
            </p>
        </div>

        <div class="grid lg:grid-cols-5 gap-8 mb-8">

            <!-- LEFT: BILLING INFORMATION (Modern & Clean Interface) -->
            <div class="lg:col-span-3 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="fa-solid fa-address-card text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Billing Information</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Enter your correct delivery details</p>
                    </div>
                </div>

                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">

                        <!-- Name & Phone in 2-Column Grid on Desktop -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <!-- NAME -->
                            <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200">
                        <i class="fa-regular fa-user"></i>
                    </span>
                                <input type="text" name="name" id="name" placeholder=" " required
                                       class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                                <label for="name"
                                       class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                           peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                           peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                    Full Name
                                </label>
                            </div>

                            <!-- PHONE -->
                            <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200">
                        <i class="fa-solid fa-phone-flip text-sm"></i>
                    </span>
                                <input type="text" name="phone" id="phone" placeholder=" " required
                                       class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                                <label for="phone"
                                       class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                           peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                           peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                    Phone Number
                                </label>
                            </div>

                        </div>

                        <!-- EMAIL -->
                        <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200">
                    <i class="fa-regular fa-envelope"></i>
                </span>
                            <input type="email" name="email" id="email" placeholder=" " required
                                   class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <label for="email"
                                   class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                       peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                       peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                Email Address
                            </label>
                        </div>

                        <!-- ADDRESS -->
                        <div class="relative">
                <span class="absolute left-4 top-5 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200">
                    <i class="fa-solid fa-map-location-dot"></i>
                </span>
                            <textarea name="shipping_address" id="address" rows="4" placeholder=" " required
                                      class="peer w-full pl-11 pr-4 pt-4 pb-2 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200"></textarea>

                            <label for="address"
                                   class="absolute left-11 top-4 text-gray-400 text-sm pointer-events-none transition-all duration-200
                       peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-7 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                       peer-[:not(:placeholder-shown)]:-translate-y-7 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                Shipping Address
                            </label>
                        </div>

                        <!-- PREMIUM COD BOX -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50/50 border border-emerald-100 rounded-2xl p-4 md:p-5 mt-2 shadow-xs">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-xs border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="fa-solid fa-truck-ramp-box text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-emerald-900 text-sm md:text-base">
                                        Cash on Delivery (COD) Available
                                    </h4>
                                    <p class="text-xs text-emerald-700/80 mt-1 leading-relaxed">
                                        No advance payment needed. Pay in cash when your ordered parcel arrives at your doorstep.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-2xl shadow-md border border-gray-100 h-fit sticky top-5">

                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Order Summary
                </h2>

                @php
                    $subtotal = 0;
                @endphp

                <div class="max-h-[380px] overflow-y-auto pr-2 space-y-1">

                    @foreach($carts as $cart)

                        @php
                            $product = $cart->variant->product ?? null;
                            // Variant price mapping with dynamic fallback
                            $itemPrice = $cart->variant->price ?? ($product->base_price ?? 0);
                            $amount = $cart->quantity * $itemPrice;
                            $subtotal += $amount;
                        @endphp

                        @if($product)
                            <div class="flex gap-4 items-center py-4 border-b border-gray-100">

                                @php
                                    $imagePath = ($cart->variant->variantImage)
                                        ? $cart->variant->variantImage->image_path
                                        : ($product->mainVariantImage->image_path ?? '');
                                @endphp
                                <img src="{{ $imagePath ? asset('storage/'.$imagePath) : asset('upload/no-image.jpg') }}"
                                     class="w-14 h-14 object-cover rounded-lg border border-gray-100 shrink-0">

                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-800 truncate">
                                        {{ $product->name }}
                                    </h4>
                                    <div class="flex flex-wrap gap-x-2 text-xs text-gray-500 mt-0.5">
                                        <span>Color: <strong class="text-gray-700 uppercase text-[10px]">{{ $cart->variant->color_name ?? 'Default' }}</strong></span>
                                        <span class="text-gray-300">|</span>
                                        <span>Size: <strong class="text-gray-700 uppercase text-[10px]">{{ $cart->variant->size ?? 'Free' }}</strong></span>
                                        <span class="text-gray-300">|</span>
                                        <span>Qty: <strong class="text-gray-700">{{ $cart->variant->quantity ?? $cart->quantity }}</strong></span>
                                    </div>
                                </div>

                                <div class="font-semibold text-sm text-gray-900 whitespace-nowrap">
                                    Rs {{ number_format($amount) }}
                                </div>

                            </div>
                        @endif

                    @endforeach

                </div>

                <div class="mt-6 space-y-3">

                    <div class="flex justify-between text-gray-600 text-sm">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-900">Rs {{ number_format($subtotal) }}</span>
                    </div>

                    <div class="flex justify-between text-gray-600 text-sm">
                        <span>Shipping</span>
                        <span class="text-green-600 font-medium">
                        Free
                    </span>
                    </div>

                    <div class="border-t pt-4 border-gray-200"></div>

                    <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-800">
                        Total
                    </span>
                        <span class="text-2xl font-bold text-green-700">
                        Rs {{ number_format($subtotal) }}
                    </span>
                    </div>

                    <button type="submit"
                            form="checkout-form"
                            class="w-full mt-6 bg-green-700 text-white py-4 rounded-xl font-semibold text-lg hover:bg-green-800 transition cursor-pointer shadow-sm text-center">
                        Place Order
                    </button>

                </div>

            </div>

        </div>

    </div>

@endsection
