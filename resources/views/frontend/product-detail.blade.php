@extends('frontend.layouts.app')
@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-4 sm:py-6">

        <div class="mb-3 sm:mb-4 text-xs sm:text-sm text-gray-500 truncate">
            Home / Products / <span class="text-black font-medium">{{ $product->name }}</span>
        </div>

        <!-- Grid: Left column ka size kam (max width constraint ke sath) aur right column ko bara kar diya hai -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">

            <!-- LEFT COLUMN: Image & Swiper (Width reduced to lg:col-span-5) -->
            <div class="lg:col-span-5">
                <!-- SWIPER MAIN SLIDER -->
                <div class="swiper mainImageSwiper bg-white rounded-xl overflow-hidden relative group">
                    <div class="swiper-wrapper cursor-pointer">
                        @foreach($product->variants->unique('variant_image_id') as $v)
                            @if($v->variantImage)
                                <div class="swiper-slide" data-color="{{ $v->color_name }}"
                                     data-image-url="{{ asset('storage/' . $v->variantImage->image_path) }}">
                                    <img src="{{ asset('storage/' . $v->variantImage->image_path) }}"
                                         class="w-full h-[350px] sm:h-[420px] lg:h-[480px] object-cover"
                                         alt="{{ $product->name }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <style>
                        .swiper-button-next, .swiper-rtl .swiper-button-prev {
                            padding: 30px 30px 30px 32px;
                        }

                        .swiper-button-prev, .swiper-rtl .swiper-button-next {
                            padding: 30px 30px;
                        }
                    </style>
                    <div style="padding: 0px 22px"
                         class="swiper-button-next !text-black bg-white/70 w-9 h-9 sm:w-10 sm:h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-xs sm:after:!text-sm"></div>
                    <div style="padding: 0px 22px"
                         class="swiper-button-prev px-3 sm:px-5 !text-black bg-white/70 w-9 h-9 sm:w-10 sm:h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-xs sm:after:!text-sm"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- THUMBNAIL CAROUSEL -->
                <div class="relative mt-3 group hidden md:block">
                    <div id="thumbCarousel"
                         class="flex gap-2.5 overflow-x-auto scrollbar-hide scroll-smooth select-none py-1.5">
                        @php $thumbIndex = 0; @endphp
                        @foreach($product->variants->unique('variant_image_id') as $v)
                            @if($v->variantImage)
                                <div class="shrink-0 min-w-[20%]">
                                    <img onclick="changeSwiperSlide({{ $thumbIndex }}, this)"
                                         src="{{ asset('storage/' . $v->variantImage->image_path) }}"
                                         class="thumb cursor-pointer shadow-sm rounded-lg h-16 w-full object-cover border-2 {{ $v->variantImage->is_main ? 'border-gray-300' : 'border-transparent' }}"
                                         data-index="{{ $thumbIndex }}">
                                </div>
                                @php $thumbIndex++; @endphp
                            @endif
                        @endforeach
                    </div>

                    <button onclick="moveCarousel(-1)"
                            class="absolute left-0 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-r opacity-0 group-hover:opacity-100 transition z-10">
                        ❮
                    </button>
                    <button onclick="moveCarousel(1)"
                            class="absolute right-0 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-l opacity-0 group-hover:opacity-100 transition z-10">
                        ❯
                    </button>
                </div>
            </div>

            <!-- RIGHT COLUMN: Product Details (Expanded to lg:col-span-7) -->
            <div class="lg:col-span-7 lg:sticky lg:top-24 h-fit">

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 capitalize">
                    {{ $product->name }}
                </h1>

                <p class="mt-1 text-xs text-gray-500">
                    Brand: <span
                        class="font-medium text-black">{{ ucfirst($product->prod_brand->name ?? 'Generic') }}</span>
                </p>

                <!-- DYNAMIC TOP RATING STARS -->
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex text-yellow-500 text-xs">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <i class="fa-solid fa-star"></i>
                            @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star text-gray-300"></i>
                            @endif
                        @endfor
                    </div>
                    <span
                        class="text-gray-500 text-xs">({{ number_format($avgRating, 1) }} - {{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})</span>
                </div>

                @php
                    $defaultVariant = $product->mainVariant ?? $product->variants->first();
                    $initialPrice = $defaultVariant ? $defaultVariant->price : ($product->base_price ?? 0);
                    $initialCutPrice = $defaultVariant ? $defaultVariant->cut_price : null;
                    $initialStock = $defaultVariant ? $defaultVariant->stock : 0;
                @endphp

                <div class="mt-3 flex items-end gap-3">
                <span id="displayPrice" class="text-xl sm:text-2xl font-bold text-green-600">
                    Rs {{ number_format($initialPrice) }}
                </span>
                    <span id="displayCutPrice"
                          class="text-xs sm:text-base text-gray-400 line-through {{ $initialCutPrice ? '' : 'hidden' }}">
                    Rs {{ $initialCutPrice ? number_format($initialCutPrice) : 0 }}
                </span>
                </div>

                <div class="mt-2">
                <span id="stockBadge"
                      class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $initialStock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $initialStock > 0 ? $initialStock . ' Items In Stock' : 'Out of Stock' }}
                </span>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold text-sm sm:text-base mb-1">Description</h3>
                    <div class="text-gray-600 text-xs sm:text-sm leading-5 sm:leading-6 prose prose-sm line-clamp-2">
                        {!! $product->description !!}
                    </div>
                </div>

                <input type="hidden" id="product_id" value="{{ $product->id }}">
                <input type="hidden" id="selectedVariantId" value="{{ $defaultVariant->id ?? '' }}">
                <input type="hidden" id="selectedVariantStock" value="{{ $initialStock }}">

                @if($product->variants && $product->variants->count() > 0)
                    <div class="mt-4">
                        <h3 class="font-semibold mb-1.5 text-xs sm:text-sm text-gray-800">Select Color</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variants->unique('color_name') as $variant)
                                @php
                                    $colorImg = $variant->variantImage ? asset('storage/' . $variant->variantImage->image_path) : '';
                                    $isDefaultColor = ($defaultVariant && $defaultVariant->color_name == $variant->color_name);
                                @endphp
                                <button type="button"
                                        onclick="selectColor(this, '{{ $variant->color_name }}')"
                                        data-image="{{ $colorImg }}"
                                        class="color-btn border cursor-pointer rounded-lg p-1 flex items-center gap-1.5 text-xs font-medium transition duration-200 hover:border-gray-400 {{ $isDefaultColor ? 'border-gray-300 bg-black/5 ring-1 ring-black/10' : 'border-gray-200' }}">
                                    @if($colorImg)
                                        <img src="{{ $colorImg }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded object-cover">
                                    @endif
                                    <span class="pr-2 pl-1">{{ ucfirst($variant->color_name ?? 'Default') }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold mb-1.5 text-xs sm:text-sm text-gray-800">Select Size</h3>
                        <div id="sizeContainer" class="flex flex-wrap gap-2">
                        </div>
                    </div>
                @endif

                <div class="mt-4">
                    <h3 class="font-semibold text-xs sm:text-sm mb-1.5">Quantity</h3>
                    <div class="flex items-center border border-gray-400 rounded-lg w-fit overflow-hidden bg-gray-50">
                        <button type="button" onclick="qty(-1)"
                                class="px-3 py-1.5 text-sm sm:text-base hover:bg-gray-100 cursor-pointer font-bold text-gray-600">-
                        </button>
                        <input id="qtyInput" type="text" value="1"
                               class="w-10 sm:w-12 text-center text-xs sm:text-sm outline-none border-x border-x-gray-400 bg-transparent" readonly>
                        <button type="button" onclick="qty(1)"
                                class="px-3 py-1.5 text-sm sm:text-base hover:bg-gray-100 cursor-pointer font-bold text-gray-600">+
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 mt-5">
                    <button type="button" id="addToCartBtn"
                            class="bg-black text-white px-5 sm:px-7 py-2.5 cursor-pointer rounded-lg text-xs sm:text-sm font-semibold hover:bg-gray-800 transition shadow-sm">
                        Add To Cart
                    </button>
                    <button type="button" id="buyNowBtn"
                            class="bg-[#ff4d2d] text-white px-5 sm:px-7 py-2.5 rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#e63e20] transition w-full sm:w-auto cursor-pointer shadow-sm">
                        <i class="fa-solid fa-bolt mr-1.5"></i> Buy Now
                    </button>
                </div>

            </div>
        </div>

        <!-- ================= COMPLETE CUSTOMER REVIEWS SECTION ================= -->
        <div class="mt-8 sm:mt-10 pt-4 border-t border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Customer Reviews</h2>

        @if($totalReviews > 0)
            <!-- Combined Rating Summary & Breakdown Card -->
                <div class="bg-gray-50 rounded-xl p-3 sm:p-5 border border-gray-100 space-y-4">

                    <!-- Top Part: Average Rating & Breakdown Side-by-Side (or Stacked on Mobile) -->
                    <div class="flex flex-col md:flex-row items-center gap-4 sm:gap-6">

                        <!-- Left: Average Rating -->
                        <div class="text-center md:border-r md:border-gray-200 md:pr-8 w-full md:w-auto">
                            <span class="text-3xl sm:text-4xl font-black text-gray-900">{{ number_format($avgRating, 1) }}</span>
                            <div class="flex text-yellow-400 text-xs justify-center my-1 gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= floor($avgRating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                            </div>
                            <p class="text-[11px] text-gray-500 font-medium">Based
                                on {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
                        </div>

                        <!-- Right: Dynamic 5-Star Breakdown Progress -->
                        <div class="flex-1 w-full space-y-1.5">
                            @for($star = 5; $star >= 1; $star--)
                                @php
                                    $count = $product->reviews->where('rating', $star)->count();
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center text-[11px] sm:text-xs text-gray-600 gap-2">
                                    <span class="w-10 font-medium">{{ $star }} Star</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-yellow-400 h-2 rounded-full transition-all duration-500"
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="w-6 text-right text-gray-400">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>

                    </div>

                    <!-- Divider Line inside the same card separating Summary and User Reviews List -->
                    <div class="border-t border-gray-200 pt-3 sm:pt-4">
                        <h3 class="text-xs sm:text-sm font-bold text-gray-800 mb-3">Recent Reviews</h3>

                        <!-- Individual Reviews Cards List inside the same wrapper -->
                        <div class="space-y-3">
                            @foreach($product->reviews as $review)
                                <div class="p-3 bg-white border border-gray-100 rounded-lg">

                                    <!-- Header: User Info & Rating -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2.5">
                                            <!-- Dynamic Avatar -->
                                            <div
                                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                                                {{ strtoupper(substr($review->user->name ?? ' ', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 text-xs">{{ $review->user->name ?? 'Verified Customer' }}</h4>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <div class="flex text-yellow-400 text-[9px] gap-0.5">
                                                        @for($s = 1; $s <= 5; $s++)
                                                            <i class="{{ $s <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <span
                                                        class="text-[9px] text-gray-400">• {{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <span
                                            class="bg-green-50 text-green-700 text-[9px] font-medium px-1.5 py-0.5 rounded-full border border-green-200 flex items-center gap-1 shrink-0">
                                        <i class="fa-solid fa-circle-check text-[8px]"></i> Verified
                                    </span>
                                    </div>

                                    <!-- Comment Content -->
                                    @if($review->comment)
                                        <p class="mt-2 text-gray-600 text-xs leading-normal">
                                            {{ $review->comment }}
                                        </p>
                                    @endif

                                <!-- Review Attached Images -->
                                    @if($review->images && $review->images->count() > 0)
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            @foreach($review->images as $imgIndex => $img)
                                                <div
                                                    onclick="openReviewModal({{ json_encode($review->images->pluck('image_path')->map(fn($p) => asset('storage/' . $p))) }}, {{ $imgIndex }})"
                                                    class="cursor-pointer group overflow-hidden rounded-md border border-gray-200">
                                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                                         class="w-12 h-12 object-cover group-hover:scale-105 transition duration-200"
                                                         alt="Review Image">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
        @else
            <!-- Empty Review State -->
                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <i class="fa-regular fa-comment-dots text-3xl text-gray-300 mb-2 block"></i>
                    <p class="text-gray-500 font-medium text-xs">There are currently no reviews for this product.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- REVIEW IMAGE POPUP / LIGHTBOX MODAL -->
    <div id="reviewImageModal"
         class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4">
        <!-- Close Button (Cut Icon) -->
        <button onclick="closeReviewModal()" type="button"
                class="absolute top-4 right-4 text-white/80 hover:text-white bg-black/40 hover:bg-black/80 rounded-full w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-xl transition z-50 cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Modal Swiper Container -->
        <div class="w-full max-w-4xl max-h-[85vh] relative">
            <div class="swiper reviewModalSwiper w-full h-[70vh] sm:h-[75vh] rounded-2xl overflow-hidden">
                <div class="swiper-wrapper flex items-center" id="reviewModalSwiperWrapper">
                    <!-- Slides JS se render hongi -->
                </div>
                <!-- Navigation -->
                <div
                    class="swiper-button-next !text-white bg-black/40 hover:bg-black/70 w-10 h-10 sm:w-12 sm:h-12 rounded-full !after:text-base sm:after:!text-lg transition"></div>
                <div
                    class="swiper-button-prev !text-white bg-black/40 hover:bg-black/70 w-10 h-10 sm:w-12 sm:h-12 rounded-full !after:text-base sm:after:!text-lg transition"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <!-- AUTHENTICATION (LOGIN / REGISTER) MODAL DESIGNED USING YOUR EXACT UI -->
    <div id="authModal"
         class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-8 relative">
            <!-- Close Button -->
            <button onclick="closeAuthModal()" type="button"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- LOGIN VIEW -->
            <div id="loginView" class="max-w-md mx-auto p-2">
                <!-- Top Icon & Header -->
                <div class="text-center mb-6">

                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        Welcome Back
                    </h2>
                    <p class="text-sm text-gray-500">
                        Please enter your credentials to log in to your account.
                    </p>
                </div>

                <form id="modalLoginForm" onsubmit="submitModalLogin(event)">
                @csrf

                <!-- Email Field -->
                    <div class="mb-5">
                        <label for="modalLoginEmail" class="block mb-2 text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalLoginEmail"
                                   type="email"
                                   name="email"
                                   required
                                   autocomplete="email"
                                   placeholder="name@company.com"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-5">
                        <label for="modalLoginPassword" class="block mb-2 text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalLoginPassword"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Submit Button with Login/Sign-in Icon -->
                    <button type="submit"
                            id="modalLoginBtn"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md shadow-emerald-600/20 transition duration-300 cursor-pointer flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                        </svg>
                        <span>Login</span>
                    </button>
                </form>

                <!-- Register Link Trigger -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <button type="button" onclick="switchAuthView('register')"
                                class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline cursor-pointer ml-1">
                            Create Account
                        </button>
                    </p>
                </div>
            </div>

            <!-- REGISTER VIEW -->
            <div id="registerView" class="hidden max-w-md mx-auto p-2">
                <!-- Header & Description -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        Create Account
                    </h2>
                    <p class="text-sm text-gray-500">
                        Please fill in the details below to create your new account.
                    </p>
                </div>

                <form id="modalRegisterForm" onsubmit="submitModalRegister(event)">
                    @csrf
                    <input type="hidden" value="customer" name="role">

                    <!-- Name Field -->
                    <div class="mb-5">
                        <label for="modalRegisterName" class="block mb-2 text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalRegisterName"
                                   type="text"
                                   name="name"
                                   required
                                   autocomplete="name"
                                   placeholder="Enter your full name"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="modalRegisterEmail" class="block mb-2 text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalRegisterEmail"
                                   type="email"
                                   name="email"
                                   required
                                   autocomplete="email"
                                   placeholder="name@company.com"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-5">
                        <label for="modalRegisterPassword" class="block mb-2 text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalRegisterPassword"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6">
                        <label for="modalRegisterPasswordConfirm" class="block mb-2 text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                            </div>
                            <!-- Input -->
                            <input id="modalRegisterPasswordConfirm"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="bg-gray-50 border border-gray-300 rounded-xl w-full pl-10 pr-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Register Button with Icon -->
                    <button type="submit"
                            id="modalRegisterBtn"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md shadow-emerald-600/20 transition duration-300 cursor-pointer flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/>
                        </svg>
                        <span>Create Account</span>
                    </button>
                </form>

                <!-- Login Link Trigger -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <button type="button" onclick="switchAuthView('login')"
                                class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline cursor-pointer ml-1">
                            Login here
                        </button>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- SweetAlert2 aur Swiper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const allVariants = @json($product->variants);
        let selectedColor = "{{ $defaultVariant->color_name ?? '' }}";
        let mainSwiper;
        let reviewSwiperInstance = null;

        window.addEventListener('DOMContentLoaded', () => {
            mainSwiper = new Swiper('.mainImageSwiper', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                on: {
                    slideChange: function () {
                        let activeIndex = this.realIndex;
                        let targetThumb = document.querySelector(`.thumb[data-index="${activeIndex}"]`);
                        if (targetThumb) {
                            updateThumbActiveState(targetThumb);
                        }
                    }
                }
            });

            let initialThumb = document.querySelector('.thumb[data-index="0"]');
            if (initialThumb) {
                updateThumbActiveState(initialThumb);
            }

            if (selectedColor) {
                let defaultVariantId = "{{ $defaultVariant->id ?? '' }}";
                let matchedVariant = allVariants.find(v => v.color_name === selectedColor);
                renderSizesForColor(selectedColor, defaultVariantId || (matchedVariant ? matchedVariant.id : null));
            }

            // --- BUY NOW CLICK HANDLER ---
            const buyNowBtn = document.getElementById('buyNowBtn');
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    triggerBuyNowAction();
                });
            }

            const addToCartBtn = document.getElementById('addToCartBtn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    triggerAddToCartAction();
                });
            }
        });

        function triggerAddToCartAction() {

            window.lastAction = 'cart';
            let addToCartBtn = document.getElementById('addToCartBtn');
            let variantId = document.getElementById('selectedVariantId').value;
            let quantity = document.getElementById('qtyInput').value || 1;
            let stock = parseInt(document.getElementById('selectedVariantStock').value) || 0;

            if (stock <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This product variant is out of stock!'
                });
                return;
            }

            if (!variantId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select a color and size first.'
                });
                return;
            }

            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = 'Processing...';

            fetch("{{ route('frontend.cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    qty: quantity
                })
            })
                .then(response => {

                    if (response.status === 401) {
                        throw new Error('Please login first.');
                    }

                    return response.json();
                })
                .then(data => {

                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = 'Add To Cart';

                    if (data.status) {

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });

                    }

                })
                .catch(error => {

                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = 'Add To Cart';

                    if (error.message === 'Please login first.') {
                        openAuthModal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message
                        });
                    }

                });

        }

        function triggerBuyNowAction() {
            window.lastAction = 'buy';
            let buyNowBtn = document.getElementById('buyNowBtn');
            let productId = document.getElementById('product_id').value;
            let variantId = document.getElementById('selectedVariantId').value;
            let quantity = document.getElementById('qtyInput').value || 1;
            let stock = parseInt(document.getElementById('selectedVariantStock').value) || 0;

            if (stock <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'This product variant is out of stock!',
                });
                return;
            }

            if (!variantId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select a color and size first.',
                });
                return;
            }

            buyNowBtn.disabled = true;
            buyNowBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i> Processing...';

            fetch("{{ route('buy.now') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({product_id: productId, variant_id: variantId, quantity: quantity})
            })
                .then(response => {
                    if (response.status === 401) {
                        throw new Error('Please login first.');
                    }
                    return response.json();
                })
                .then(data => {
                    buyNowBtn.disabled = false;
                    buyNowBtn.innerHTML = '<i class="fa-solid fa-bolt mr-2"></i> Buy Now';
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Something went wrong.'
                        });
                    }
                })
                .catch(error => {
                    buyNowBtn.disabled = false;
                    buyNowBtn.innerHTML = '<i class="fa-solid fa-bolt mr-2"></i> Buy Now';
                    if (error.message === 'Please login first.') {
                        openAuthModal();
                    } else {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to process request.'
                        });
                    }
                });
        }

        // --- AUTH MODAL CONTROLLERS ---
        function openAuthModal() {
            document.getElementById('authModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            switchAuthView('login'); // Default open to login view
        }

        function closeAuthModal() {
            document.getElementById('authModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function switchAuthView(viewName) {
            const loginView = document.getElementById('loginView');
            const registerView = document.getElementById('registerView');
            if (viewName === 'register') {
                loginView.classList.add('hidden');
                registerView.classList.remove('hidden');
            } else {
                registerView.classList.add('hidden');
                loginView.classList.remove('hidden');
            }
        }

        function submitModalLogin(e) {
            e.preventDefault();
            let email = document.getElementById('modalLoginEmail').value;
            let password = document.getElementById('modalLoginPassword').value;
            let btn = document.getElementById('modalLoginBtn');

            btn.disabled = true;
            btn.innerText = 'Logging in...';

            fetch("{{ route('login') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({email: email, password: password})
            })
                .then(res => {
                    btn.disabled = false;
                    btn.innerText = 'Login';
                    if (res.ok || res.redirected) {
                        closeAuthModal();
                        location.reload();

                    } else {
                        return res.json().then(data => {
                            throw new Error(data.message || 'Invalid credentials');
                        });
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerText = 'Login';
                    Swal.fire('Error', err.message || 'Invalid credentials or login failed.', 'error');
                });
        }

        function submitModalRegister(e) {
            e.preventDefault();
            let name = document.getElementById('modalRegisterName').value;
            let email = document.getElementById('modalRegisterEmail').value;
            let password = document.getElementById('modalRegisterPassword').value;
            let password_confirmation = document.getElementById('modalRegisterPasswordConfirm').value;
            let btn = document.getElementById('modalRegisterBtn');

            btn.disabled = true;
            btn.innerText = 'Registering...';

            fetch("{{ route('register') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({name, email, password, password_confirmation, role: 'customer'})
            })
                .then(res => {
                    btn.disabled = false;
                    btn.innerText = 'Register';
                    if (res.ok || res.redirected) {
                        closeAuthModal();
                        location.reload();
                    } else {
                        return res.json().then(data => {
                            throw new Error(data.message || 'Registration failed');
                        });
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerText = 'Register';
                    Swal.fire('Error', err.message || 'Registration failed. Please check inputs.', 'error');
                });
        }

        // 1. Color Select
        function selectColor(element, colorName) {
            selectedColor = colorName;
            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.classList.remove('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
                btn.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
            let slides = document.querySelectorAll('.mainImageSwiper .swiper-wrapper .swiper-slide:not(.swiper-slide-duplicate)');
            let slideIndexToMove = -1;
            slides.forEach((slide, idx) => {
                if (slide.getAttribute('data-color') === colorName) {
                    if (slideIndexToMove === -1) {
                        slideIndexToMove = idx;
                    }
                }
            });
            if (slideIndexToMove !== -1 && mainSwiper) {
                mainSwiper.slideToLoop(slideIndexToMove);
                let targetThumb = document.querySelector(`.thumb[data-index="${slideIndexToMove}"]`);
                if (targetThumb) {
                    updateThumbActiveState(targetThumb);
                }
            }
            renderSizesForColor(colorName);
        }

        // 2. Render Sizes
        function renderSizesForColor(colorName, preselectedVariantId = null) {
            const sizeContainer = document.getElementById('sizeContainer');
            sizeContainer.innerHTML = '';
            const filteredVariants = allVariants.filter(v => v.color_name === colorName);
            filteredVariants.forEach((variant, index) => {
                const sizeButton = document.createElement('button');
                sizeButton.type = "button";
                sizeButton.innerText = variant.size ? variant.size.toUpperCase() : 'FREE SIZE';
                sizeButton.className = "size-btn border cursor-pointer rounded-lg px-4 py-2 text-sm font-medium transition duration-200 hover:border-gray-400 border-gray-200";
                sizeButton.setAttribute('onclick', `selectSize(this, '${variant.id}', ${variant.stock}, ${variant.price}, '${variant.cut_price || ""}')`);
                sizeContainer.appendChild(sizeButton);
                if ((preselectedVariantId && variant.id == preselectedVariantId) || (!preselectedVariantId && index === 0)) {
                    sizeButton.click();
                }
            });
        }

        // 3. Size Select
        function selectSize(element, variantId, stock, price, cutPrice) {
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
                btn.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
            document.getElementById('selectedVariantId').value = variantId;
            document.getElementById('selectedVariantStock').value = stock;
            document.getElementById('qtyInput').value = 1;
            document.getElementById('displayPrice').innerText = "Rs " + price.toLocaleString();
            let cutPriceElement = document.getElementById('displayCutPrice');
            if (cutPrice && parseFloat(cutPrice) > price) {
                cutPriceElement.innerText = "Rs " + parseFloat(cutPrice).toLocaleString();
                cutPriceElement.classList.remove('hidden');
            } else {
                cutPriceElement.classList.add('hidden');
            }
            let stockBadge = document.getElementById('stockBadge');
            if (stock > 0) {
                stockBadge.innerHTML = stock + ' Items In Stock';
                stockBadge.className = "px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700";
            } else {
                stockBadge.innerHTML = 'Out of Stock';
                stockBadge.className = "px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700";
            }
        }

        // Thumbnail click handler
        function changeSwiperSlide(index, element) {
            if (mainSwiper) {
                mainSwiper.slideToLoop(index);
            }
            updateThumbActiveState(element);
        }

        // Update border state on active thumbnail
        function updateThumbActiveState(element) {
            document.querySelectorAll('.thumb').forEach(img => {
                img.classList.remove('border-gray-300');
                img.classList.add('border-transparent');
            });
            element.classList.add('border-gray-300');
            element.classList.remove('border-transparent');
        }

        // Carousel Slider Navigation
        function moveCarousel(direction) {
            const carousel = document.getElementById('thumbCarousel');
            carousel.scrollBy({left: direction * 150, behavior: 'smooth'});
        }

        // Quantity Controller
        function qty(change) {
            let input = document.getElementById('qtyInput');
            let value = parseInt(input.value) || 1;
            let stock = parseInt(document.getElementById('selectedVariantStock').value) || 0;
            value += change;
            if (value < 1) value = 1;
            if (change > 0 && value > stock) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: stock > 0 ? 'Only ' + stock + ' items available' : 'Product is out of stock',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            input.value = value;
        }

        // ================= REVIEW LIGHTBOX MODAL FUNCTIONS =================
        function openReviewModal(imageUrls, startIndex = 0) {
            const modal = document.getElementById('reviewImageModal');
            const wrapper = document.getElementById('reviewModalSwiperWrapper');
            if (!imageUrls || imageUrls.length === 0) return;
            wrapper.innerHTML = '';
            imageUrls.forEach(url => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide flex items-center justify-center bg-black/20';
                slide.innerHTML = `<img src="${url}" class="max-h-[75vh] max-w-full object-contain rounded-lg shadow-2xl mx-auto" alt="Review Image">`;
                wrapper.appendChild(slide);
            });
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (reviewSwiperInstance) {
                reviewSwiperInstance.destroy(true, true);
            }
            reviewSwiperInstance = new Swiper('.reviewModalSwiper', {
                initialSlide: startIndex,
                loop: imageUrls.length > 1,
                navigation: {
                    nextEl: '.reviewModalSwiper .swiper-button-next',
                    prevEl: '.reviewModalSwiper .swiper-button-prev',
                },
                pagination: {
                    el: '.reviewModalSwiper .swiper-pagination',
                    clickable: true,
                },
            });
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewImageModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // ESC Key aur Backdrop Click Events
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeReviewModal();
                closeAuthModal();
            }
        });
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .swiper-pagination-bullet-active {
            background: #000 !important;
        }

        .reviewModalSwiper .swiper-pagination-bullet-active {
            background: #fff !important;
        }
    </style>
@endsection
