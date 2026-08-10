@extends('frontend.layouts.app')

@section('content')

    <!-- Select2 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Tailwind Custom Adjustments for Select2 -->
    <style>
        /* Select2 box padding taake icon ke liye space ban jaye */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 2.75rem !important; /* Icon ki jagah chhorne ke liye */
        }

        /* Baki purani styles wese hi rahengi */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 48px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            background-color: rgba(249, 250, 251, 0.3) !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 12px !important;
        }

        .select2-selection__clear {
            display: none !important;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background-color: #ffffff !important;
            z-index: 99999 !important;
        }

        .select2-search--dropdown {
            padding: 8px !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 8px 12px !important;
            outline: none !important;
            font-size: 0.875rem !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
        }
    </style>

    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-4 sm:py-6">

        <div class="text-center mb-5 max-w-2xl mx-auto">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 tracking-tight">
                Checkout
            </h1>
            <div class="w-12 h-0.5 bg-emerald-600 mx-auto mt-1 rounded-full opacity-80"></div>
            <p class="text-gray-500 text-sm md:text-base mt-1 leading-relaxed font-normal">
                Please provide your details, choose shipping & payment options to complete your order.
            </p>
        </div>

        <!-- MAIN FORM START -->
        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <!-- Hidden Input for Dynamic Shipping Cost -->
            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">

            <!-- MAIN GRID -->
            <div class="grid lg:grid-cols-5 gap-8 mb-8 items-start relative">

                <!-- LEFT: FORM SECTION -->
                <div class="lg:col-span-3 space-y-6">

                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-8">

                        <!-- Part 1: Personal Information -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <i class="fa-solid fa-user-pen text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">Enter your contact details for updates</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <!-- Name & Phone Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- NAME -->
                                    <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fa-regular fa-user"></i>
                                    </span>
                                        <input type="text" name="name" id="name" placeholder=" " required
                                               value="{{\Illuminate\Support\Facades\Auth::user()->name}}"
                                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">
                                        <label for="name"
                                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                                           peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                                           peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                            Full Name
                                        </label>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                        <input type="email" name="email" id="email" placeholder=" " required
                                               value="{{\Illuminate\Support\Facades\Auth::user()->email}}"
                                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">
                                        <label for="email"
                                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                                       peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                                       peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                            Email Address
                                        </label>
                                    </div>

                                </div>
                                <!-- PHONE -->
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i class="fa-solid fa-phone-flip text-sm"></i>
                                    </span>
                                    <input type="text" name="phone" id="phone" placeholder=" " required
                                           value="{{$customer_info->phone ?? ''}}"
                                           class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">
                                    <label for="phone"
                                           class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                                           peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                                           peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                        Phone Number
                                    </label>
                                </div>


                            </div>
                        </div>

                        <!-- Divider Line -->
                        <hr class="border-gray-100">

                        <!-- Part 2: Shipping Information -->
                        <div class="space-y-5">
                            <!-- Country & State Select Dropdowns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Country (Fixed ID: 167 for Pakistan) -->


                                <div class="relative">
                                    <label for="country_id" class="block text-xs font-semibold text-gray-700 mb-1">Country</label>
                                    <div class="relative">
                                        <input type="hidden" name="country_id" value="167">
                                        <select name="country_dummy" id="country_dummy" disabled
                                                class="w-full px-4 py-3.5 pr-10 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm appearance-none focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">
                                            <option value="167" selected>{{ $country->name ?? 'Pakistan' }}</option>
                                        </select>
                                        <span
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
                                    </div>
                                </div>

                                <!-- State / Province Dropdown (Select2 Searchable with Icon) -->
                                <div class="relative">
                                    <label for="state_id" class="block text-xs font-semibold text-gray-700 mb-1">State /
                                        Province</label>
                                    <div class="relative">
                                        <!-- Left Icon -->
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10 pointer-events-none">
                    <i class="fa-solid fa-map text-sm"></i>
                </span>
                                        <select name="state_id" id="state_id" required class="w-full select-with-icon">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option
                                                    @if(isset($customer_info->state_id) && $customer_info->state_id == $state->id) selected
                                                    @endif value="{{ $state->id }}" {{ $loop->first ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- City & Postal Code Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- City Dropdown (Select2 Searchable with Icon) -->
                                <div class="relative">
                                    <label for="city_id"
                                           class="block text-xs font-semibold text-gray-700 mb-1">City </label>
                                    <div class="relative">
                                        <!-- Left Icon -->
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10 pointer-events-none">
                    <i class="fa-solid fa-city text-sm"></i>
                </span>
                                        <select name="city_id" id="city_id" required class="w-full select-with-icon">
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option
                                                    @if(isset($customer_info->city_id) && $customer_info->city_id == $city->id) selected
                                                    @endif value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Postal Code Input -->
                                <div>
                                    <label for="postal_code" class="block text-xs font-semibold text-gray-700 mb-1">Postal
                                        / ZIP Code</label>
                                    <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="fa-solid fa-signs-post text-sm"></i>
            </span>
                                        <input type="text" name="postal_code" id="postal_code"
                                               value="{{$customer_info->postal_code ?? ''}}"
                                               placeholder="Enter postal code" required
                                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            <!-- Complete Street Address Textarea -->
                            <div class="relative">
                            <textarea name="shipping_address" id="address" rows="3" placeholder=" " required
                                      class="peer w-full px-4 pt-5 pb-3 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 resize-none">{{$customer_info->shipping_address ?? ''}}</textarea>
                                <label for="address"
                                       class="absolute left-4 top-4 text-gray-400 text-sm pointer-events-none transition-all duration-200
           peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-2.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
           peer-[:not(:placeholder-shown)]:-translate-y-2.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                    Street Address, House/Apartment No, Landmark
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- SHIPPING METHOD SECTION -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Shipping Options</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Select your preferred delivery speed</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label
                                class="flex items-center justify-between p-4 rounded-2xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition-all duration-200 bg-gray-50/40 has-[:checked]:bg-emerald-50/30 has-[:checked]:border-emerald-500 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                                <div class="flex items-center gap-3.5">
                                    <input type="radio" name="shipping_method" value="standard" checked
                                           data-cost="0"
                                           onchange="updateShippingCost(this)"
                                           class="w-4 h-4 text-emerald-600 accent-emerald-600 border-gray-300 focus:ring-emerald-500 focus:ring-offset-0 focus:outline-none">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Standard Delivery</p>
                                        <p class="text-xs text-gray-500">Delivered in 5-7 business days</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-emerald-600">FREE</span>
                            </label>

                            <label
                                class="flex items-center justify-between p-4 rounded-2xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition-all duration-200 bg-gray-50/40 has-[:checked]:bg-emerald-50/30 has-[:checked]:border-emerald-500 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                                <div class="flex items-center gap-3.5">
                                    <input type="radio" name="shipping_method" value="express"
                                           data-cost="250"
                                           onchange="updateShippingCost(this)"
                                           class="w-4 h-4 text-emerald-600 accent-emerald-600 border-gray-300 focus:ring-emerald-500 focus:ring-offset-0 focus:outline-none">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Express Delivery (3 Days)</p>
                                        <p class="text-xs text-gray-500">Fast delivery within 3 working days</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">Rs 250</span>
                            </label>

                            <label
                                class="flex items-center justify-between p-4 rounded-2xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition-all duration-200 bg-gray-50/40 has-[:checked]:bg-emerald-50/30 has-[:checked]:border-emerald-500 has-[:checked]:ring-1 has-[:checked]:ring-emerald-500">
                                <div class="flex items-center gap-3.5">
                                    <input type="radio" name="shipping_method" value="sameday"
                                           data-cost="500"
                                           onchange="updateShippingCost(this)"
                                           class="w-4 h-4 text-emerald-600 accent-emerald-600 border-gray-300 focus:ring-emerald-500 focus:ring-offset-0 focus:outline-none">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">Same Day Delivery</p>
                                        <p class="text-xs text-gray-500">Get your parcel delivered today</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">Rs 500</span>
                            </label>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD SECTION -->
                    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-wallet text-lg"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Payment Options</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Click on a payment option to view details</p>
                            </div>
                        </div>

                        <!-- Tabs Buttons -->
                        <div
                            class="grid grid-cols-2 sm:grid-cols-4 gap-2 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 mb-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="hidden peer" checked
                                       onchange="switchPaymentTab('cod')">
                                <div
                                    class="payment-tab py-2.5 px-3 rounded-xl text-center text-xs font-semibold text-gray-600 flex items-center justify-center gap-2 border border-transparent transition-all duration-200 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm peer-checked:border-gray-200">
                                    <i class="fa-solid fa-truck-fast text-sm"></i>
                                    <span>COD</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="jazzcash" class="hidden peer"
                                       onchange="switchPaymentTab('jazzcash')">
                                <div
                                    class="payment-tab py-2.5 px-3 rounded-xl text-center text-xs font-semibold text-gray-600 flex items-center justify-center gap-2 border border-transparent transition-all duration-200 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm peer-checked:border-gray-200">
                                    <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                                    <span>JazzCash</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="easypaisa" class="hidden peer"
                                       onchange="switchPaymentTab('easypaisa')">
                                <div
                                    class="payment-tab py-2.5 px-3 rounded-xl text-center text-xs font-semibold text-gray-600 flex items-center justify-center gap-2 border border-transparent transition-all duration-200 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm peer-checked:border-gray-200">
                                    <i class="fa-solid fa-wallet text-sm"></i>
                                    <span>EasyPaisa</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="bank" class="hidden peer"
                                       onchange="switchPaymentTab('bank')">
                                <div
                                    class="payment-tab py-2.5 px-3 rounded-xl text-center text-xs font-semibold text-gray-600 flex items-center justify-center gap-2 border border-transparent transition-all duration-200 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm peer-checked:border-gray-200">
                                    <i class="fa-solid fa-building-columns text-sm"></i>
                                    <span>Bank</span>
                                </div>
                            </label>
                        </div>

                        <!-- Payment Panels Content -->
                        <div>
                            <!-- COD Details -->
                            <div id="content-cod" class="payment-panel">
                                <div
                                    class="flex items-start gap-3 text-emerald-800 text-xs leading-relaxed p-3 bg-emerald-50/80 rounded-xl border border-emerald-100">
                                    <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0 mt-0.5"></i>
                                    <div>
                                        <strong class="block text-sm font-semibold text-gray-900 mb-0.5">Cash on
                                            Delivery Selected</strong>
                                        Pay with cash directly to the courier agent when your parcel arrives.
                                    </div>
                                </div>
                            </div>

                            <!-- JazzCash Details -->
                            <div id="content-jazzcash" class="payment-panel hidden space-y-4">
                                <div
                                    class="p-3 bg-red-50/80 rounded-xl border border-red-100 text-xs text-red-800 leading-relaxed">
                                    <strong>JazzCash Account:</strong> 03001234567 <br>
                                    <strong>Account Title:</strong> Your Store Name
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sender Mobile
                                            Number</label>
                                        <input type="text" name="jazzcash_mobile" placeholder="0300XXXXXXX"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Title /
                                            Sender Name</label>
                                        <input type="text" name="jazzcash_title" placeholder="e.g. Ali Raza"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Transaction ID
                                            (TID)</label>
                                        <input type="text" name="jazzcash_tid" placeholder="Enter 12-digit JazzCash TID"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- EasyPaisa Details -->
                            <div id="content-easypaisa" class="payment-panel hidden space-y-4">
                                <div
                                    class="p-3 bg-emerald-50/80 rounded-xl border border-emerald-100 text-xs text-emerald-800 leading-relaxed">
                                    <strong>EasyPaisa Account:</strong> 03451234567 <br>
                                    <strong>Account Title:</strong> Your Store Name
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sender Mobile
                                            Number</label>
                                        <input type="text" name="easypaisa_mobile" placeholder="0345XXXXXXX"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Title /
                                            Sender Name</label>
                                        <input type="text" name="easypaisa_title" placeholder="e.g. Muhammad Ahmed"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Transaction ID
                                            (TID)</label>
                                        <input type="text" name="easypaisa_tid" placeholder="Enter EasyPaisa TID"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Transfer Details -->
                            <div id="content-bank" class="payment-panel hidden space-y-4">
                                <div
                                    class="p-3 bg-blue-50/80 rounded-xl border border-blue-100 text-xs text-blue-900 leading-relaxed">
                                    <strong>Bank Name:</strong> Meezan Bank <br>
                                    <strong>Account Title:</strong> Your Business Name <br>
                                    <strong>IBAN:</strong> PK36MEZN00012345678901
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Select Your
                                            Bank</label>
                                        <select name="bank_name"
                                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                            <option value="">Choose Bank</option>
                                            <option value="HBL">Habib Bank Limited (HBL)</option>
                                            <option value="Meezan">Meezan Bank</option>
                                            <option value="UBL">United Bank Limited (UBL)</option>
                                            <option value="MCB">MCB Bank</option>
                                            <option value="Allied">Allied Bank (ABL)</option>
                                            <option value="Other">Other Bank</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Title /
                                            Depositor Name</label>
                                        <input type="text" name="bank_account_title" placeholder="Account Holder Name"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account No / IBAN
                                            (Optional)</label>
                                        <input type="text" name="bank_account_no" placeholder="Sender Account / IBAN"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Reference /
                                            Transaction Ref. No.</label>
                                        <input type="text" name="bank_ref_no" placeholder="Enter Ref / Receipt No."
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: STICKY ORDER SUMMARY / CART BOX -->
                <div
                    class="lg:col-span-2 bg-white p-6 md:p-8 rounded-2xl shadow-md border border-gray-100 h-fit lg:sticky lg:top-[110px] self-start z-10">

                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-clipboard-check text-lg"></i>
                        </div>
                        Order Summary
                    </h2>

                    @php $subtotal = 0; @endphp

                    <div class="max-h-[380px] overflow-y-auto pr-2 space-y-1">
                        @foreach($carts as $cart)
                            @php
                                $product = $cart->variant->product ?? null;
                                $itemPrice = $cart->variant->sale_price ?? ($cart->variant->price ?? ($product->base_price ?? 0));
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
                                    <img
                                        src="{{ $imagePath ? asset('storage/'.$imagePath) : asset('upload/no-image.jpg') }}"
                                        class="w-14 h-14 object-cover rounded-lg border border-gray-100 shrink-0">

                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $product->name }}
                                        </h4>
                                        <div class="flex flex-wrap gap-x-2 text-xs text-gray-500 mt-0.5">
                                            <span>Color: <strong
                                                    class="text-gray-700 uppercase text-[10px]">{{ $cart->variant->color_name ?? 'Default' }}</strong></span>
                                            <span class="text-gray-300">|</span>
                                            <span>Size: <strong
                                                    class="text-gray-700 uppercase text-[10px]">{{ $cart->variant->size ?? 'Free' }}</strong></span>
                                            <span class="text-gray-300">|</span>
                                            <span>Qty: <strong
                                                    class="text-gray-700">{{ $cart->quantity }}</strong></span>
                                        </div>
                                    </div>

                                    <div class="font-semibold text-sm text-gray-900 whitespace-nowrap">
                                        Rs {{ number_format($amount) }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Calculation Summary -->
                    <div class="mt-4 space-y-3 text-sm border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900" id="subtotal-amount"
                                  data-subtotal="{{ $subtotal }}">Rs {{ number_format($subtotal) }}</span>
                        </div>

                        <div class="flex justify-between text-gray-600 pb-1">
                            <span>Shipping Cost</span>
                            <span id="display_shipping" class="font-medium text-emerald-600">FREE</span>
                        </div>

                        <div
                            class="flex justify-between text-base font-bold text-gray-900 border-t border-gray-100 pt-3">
                            <span>Total Amount</span>
                            <span id="display_total" class="text-emerald-700">Rs {{ number_format($subtotal) }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" form="checkout-form"
                            class="w-full cursor-pointer mt-6 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-200 text-center block">
                        Place Order Now
                    </button>

                </div>

            </div>

        </form>
        <!-- MAIN FORM END -->

    </div>

    <!-- jQuery & Select2 JS Scripts (Zaroori hai ke jQuery pehle load ho) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom Select2 Initialization & AJAX Logic -->
    <script>
        const subtotal = {{ $subtotal }};

        $(document).ready(function () {
            // State dropdown par Select2 search enable karna
            $('#state_id').select2({
                placeholder: "Select State / Province",
                allowClear: true
            });

            // City dropdown par Select2 search enable karna
            $('#city_id').select2({
                placeholder: "Select City",
                allowClear: true
            });

            // 1. Dynamic Cities AJAX on State Change
            $('#state_id').on('change', function () {
                var stateId = $(this).val();

                // Loading text set karke Select2 ko update karna
                $('#city_id').html('<option value="">Loading cities...</option>').trigger('change');

                if (stateId) {
                    $.ajax({
                        url: '/get-cities/' + stateId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            var options = '<option value="">Select City</option>';
                            $.each(data, function (key, city) {
                                options += '<option value="' + city.id + '">' + city.name + '</option>';
                            });
                            // Options set karke Select2 ko refresh karna taake search bar kaam kare
                            $('#city_id').html(options).trigger('change');
                        },
                        error: function () {
                            $('#city_id').html('<option value="">Failed to load cities</option>').trigger('change');
                        }
                    });
                } else {
                    $('#city_id').html('<option value="">Select State first</option>').trigger('change');
                }
            });
        });

        // 2. Payment Method Tabs Switching
        function switchPaymentTab(tabName) {
            document.querySelectorAll('.payment-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            const selectedPanel = document.getElementById('content-' + tabName);
            if (selectedPanel) {
                selectedPanel.classList.remove('hidden');
            }
        }

        // 3. Shipping Cost Calculator & Grand Total Update
        function updateShippingCost(element) {
            const cost = parseInt(element.getAttribute('data-cost')) || 0;
            document.getElementById('shipping_cost_input').value = cost;

            const shippingDisplay = document.getElementById('display_shipping');
            if (cost === 0) {
                shippingDisplay.textContent = 'FREE';
                shippingDisplay.className = 'font-medium text-emerald-600';
            } else {
                shippingDisplay.textContent = 'Rs ' + cost.toLocaleString();
                shippingDisplay.className = 'font-medium text-gray-900';
            }

            const total = subtotal + cost;
            document.getElementById('display_total').textContent = 'Rs ' + total.toLocaleString();
        }
    </script>

@endsection
