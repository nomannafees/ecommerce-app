@extends('frontend.layouts.app')
@section('content')

    <style>
        /* Product Description */
        .product-description ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin: 0.75rem 0;
        }

        .product-description ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin: 0.75rem 0;
        }

        .product-description li {
            margin-bottom: 0.25rem;
        }
        .swiper-button-next, .swiper-rtl .swiper-button-prev {
            padding: 30px 30px 30px 32px;
        }

        .swiper-button-prev, .swiper-rtl .swiper-button-next {
            padding: 30px 30px;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-4 pb-16 sm:py-6">

        <div class="mb-3 sm:mb-4 text-xs sm:text-sm text-gray-500 truncate">
            Home / Products / <span class="text-black font-medium">{{ $product->name }}</span>
        </div>

        <!-- Grid: Left column ka size kam (max width constraint ke sath) aur right column ko bara kar diya hai -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10">

            <!-- LEFT COLUMN: Image & Swiper -->
            <div class="lg:col-span-5">
                <!-- SWIPER MAIN SLIDER WITH ZOOM CONTAINER -->
                <div class="relative flex gap-4">

                    <!-- Main Swiper -->
                    <div class="swiper mainImageSwiper bg-white rounded-xl overflow-hidden relative group w-full">
                        <div class="swiper-wrapper cursor-crosshair" id="zoomContainer" onmousemove="zoomIn(event)" onmouseleave="zoomOut()" onmouseenter="zoomEnter(event)">
                            @foreach($product->variants->unique('variant_image_id') as $v)
                                @if($v->variantImage)
                                    <div class="swiper-slide relative" data-color="{{ $v->color_name }}"
                                         data-image-url="{{ asset('storage/' . $v->variantImage->image_path) }}">
                                        <img src="{{ asset('storage/' . $v->variantImage->image_path) }}"
                                             class="w-full h-[350px] sm:h-[420px] lg:h-[490px] object-cover main-product-image"
                                             alt="{{ $product->name }}">

                                        <!-- Magnifier Lens (Green Theme) -->
                                        <div class="magnifier-lens hidden absolute border-2 border-emerald-500 bg-emerald-500/20 pointer-events-none w-28 h-28 rounded-md"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Swiper Navigation Buttons -->
                        <div style="padding: 0px 22px"
                             class="swiper-button-next !text-black bg-white/70 w-9 h-9 sm:w-10 sm:h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-xs sm:after:!text-sm"></div>
                        <div style="padding: 0px 22px"
                             class="swiper-button-prev px-3 sm:px-5 !text-black bg-white/70 w-9 h-9 sm:w-10 sm:h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-xs sm:after:!text-sm"></div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- ZOOMED PREVIEW BOX -->
                    <div id="zoomResult" class="hidden lg:block absolute left-[102%] top-0 w-[450px] h-[490px] bg-white rounded-xl shadow-xl overflow-hidden z-50 border border-gray-200 pointer-events-none opacity-0 transition-opacity duration-200">
                        <div id="zoomedImage" class="w-full h-full bg-no-repeat"></div>
                    </div>
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
                                         class="thumb cursor-pointer shadow-sm rounded-lg h-18 w-full object-cover border-2 {{ $v->variantImage->is_main ? 'border-gray-300' : 'border-transparent' }}"
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
            <div class="lg:col-span-7">

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

                    // Flash Sale Check & Calculation (Card section ki tarah)
                    $hasFlashSale = $product->flashSale && \Carbon\Carbon::now()->between($product->flashSale->start_time, $product->flashSale->end_time);
                    $discountPercent = $hasFlashSale ? $product->flashSale->discount_percentage : 0;

                    $originalPrice = $defaultVariant ? ($defaultVariant->cut_price ?? $defaultVariant->price) : ($product->base_price ?? 0);

                    if ($hasFlashSale && $discountPercent > 0) {
                        $initialPrice = $originalPrice - ($originalPrice * ($discountPercent / 100));
                        $initialCutPrice = $originalPrice;
                    } else {
                        $initialPrice = $defaultVariant ? $defaultVariant->price : ($product->base_price ?? 0);
                        $initialCutPrice = $defaultVariant ? $defaultVariant->cut_price : null;
                    }

                    $initialStock = $defaultVariant ? $defaultVariant->stock : 0;
                @endphp

                <div class="mt-3 flex items-center gap-3">
    <span id="displayPrice" class="text-xl sm:text-2xl font-bold text-green-600">
        Rs {{ number_format($initialPrice) }}
    </span>

                    <span id="displayCutPrice"
                          class="text-xs sm:text-base text-gray-400 line-through {{ $initialCutPrice && $initialCutPrice > $initialPrice ? '' : 'hidden' }}">
        Rs {{ $initialCutPrice ? number_format($initialCutPrice) : 0 }}
    </span>

                    @if($hasFlashSale && $discountPercent > 0)
                        <span id="discountBadge" class="bg-amber-100 text-amber-700 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
            {{ number_format($discountPercent, 0) }}% OFF
        </span>
                    @else
                        <span id="discountBadge" class="hidden bg-amber-100 text-amber-700 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider"></span>
                    @endif
                </div>

                <div class="mt-2">
            <span id="stockBadge"
                  class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $initialStock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $initialStock > 0 ? $initialStock . ' Items In Stock' : 'Out of Stock' }}
            </span>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold text-sm sm:text-base mb-1">Description</h3>
                    <div class=" text-gray-600 text-xs sm:text-sm leading-5 sm:leading-6 prose prose-sm line-clamp-2">
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

                <div class="flex flex-row gap-2 sm:gap-3 mt-5">

                    <button type="button" id="addToCartBtn"
                            class="flex-1 sm:flex-none bg-black text-white px-2 sm:px-7 py-2.5 cursor-pointer rounded-lg text-xs sm:text-sm font-semibold hover:bg-gray-800 transition shadow-sm whitespace-nowrap">
                        Add To Cart
                    </button>

                    <button type="button" id="buyNowBtn"
                            class="flex-1 sm:flex-none bg-[#ff4d2d] text-white px-2 sm:px-7 py-2.5 rounded-lg text-xs sm:text-sm font-semibold hover:bg-[#e63e20] transition cursor-pointer shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-bolt mr-1"></i>
                        Buy Now
                    </button>

                </div>

            </div>
        </div>

        <!-- ================= PRODUCT DESCRIPTION SECTION ================= -->
        <div class="mt-5 sm:mt-6 pt-2 ">
            <div class=" rounded-lg transition-all duration-300 ">

                <!-- Header -->
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/60 text-emerald-600 flex items-center justify-center shrink-0 shadow-inner">
                            <i class="fa-solid fa-align-left text-sm"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight">Product Description</h2>
                    </div>

                    <!-- Optional subtle badge or tag -->
                    <span class="hidden sm:inline-block text-[11px] font-semibold uppercase tracking-wider text-emerald-600 bg-emerald-50/80 px-2.5 py-1 rounded-full">
                Overview
            </span>
                </div>

                <!-- Description Container with Gradient Fade Overlay -->
                <div class="relative">
                    <div id="fullDescriptionContent"
                         class="product-description text-gray-600 text-sm sm:text-[15px] leading-relaxed prose prose-sm max-w-none overflow-hidden transition-all duration-500 ease-in-out"
                         style="max-height: 160px;">
                        {!! $product->description !!}
                    </div>

                    <!-- Fade Overlay (Only visible when collapsed) -->
                    <div id="descGradientOverlay" class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none transition-opacity duration-300"></div>
                </div>

                <!-- Toggle Button -->
                <div class="mt-4 pt-2 flex justify-center">
                    <button type="button"
                            id="descToggleBtn"
                            onclick="toggleFullDescription()"
                            class="group inline-flex items-center gap-2 px-4 py-2 rounded-xl hover:bg-emerald-100 bg-emerald-100 text-gray-700 font-semibold text-xs sm:text-sm transition-all duration-200 border border-emerald-100 hover:border-emerald-200">

                        <span id="descToggleText">Read Full Description</span>

                        <span class="w-5 h-5 rounded-full bg-emerald-200 text-emerald-400 group-hover:text-emerald-500 flex items-center justify-center shadow-sm transition-transform duration-300"
                              id="descToggleIconWrapper">

            <i id="descToggleIcon"
               class="fa-solid fa-chevron-down text-[10px]"></i>

        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= COMPLETE CUSTOMER REVIEWS SECTION ================= -->
        <div class="mt-8 sm:mt-10 pt-4 border-t border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Customer Reviews</h2>

        @if($totalReviews > 0)
            <!-- Combined Rating Summary & Breakdown Card -->
                <div class="">

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
                                <div class="p-3 border-b border-gray-200">

                                    <!-- Header: User Info & Rating -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-2.5">
                                            <!-- Dynamic Avatar -->
                                            <div
                                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                                                {{ strtoupper(substr($review->user->name ?? ' ', 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 text-xs">{{ ucwords($review->user->name ?? 'Verified Customer') }}</h4>
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
         class="fixed inset-0 z-[9999] hidden bg-black/90 backdrop-blur-sm">

        <!-- Close Button -->
        <button onclick="closeReviewModal()" type="button"
                class="absolute top-4 right-4 sm:top-6 sm:right-6 z-[10000]
                   w-10 h-10 sm:w-12 sm:h-12
                   flex items-center justify-center
                   rounded-full bg-black/50 hover:bg-black/80
                   text-white text-xl transition cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Modal Content -->
        <div class="w-full h-full flex items-center justify-center px-4 sm:px-16">

            <div class="relative w-full max-w-5xl h-[85vh]">

                <!-- Swiper -->
                <div class="swiper reviewModalSwiper w-full h-full">

                    <div class="swiper-wrapper" id="reviewModalSwiperWrapper">
                        <!-- Images JS se render hongi -->
                    </div>

                    <!-- Previous -->
                    <div class="swiper-button-prev
                            !text-white
                            !w-10 !h-10 sm:!w-12 sm:!h-12
                            !bg-black/50 hover:!bg-black/80
                            rounded-full
                            transition">
                    </div>

                    <!-- Next -->
                    <div class="swiper-button-next
                            !text-white
                            !w-10 !h-10 sm:!w-12 sm:!h-12
                            !bg-black/50 hover:!bg-black/80
                            rounded-full
                            transition">
                    </div>

                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>

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
        // --- Flash Sale Variables ---
        const hasActiveFlashSale = {{ $product->flashSale && \Carbon\Carbon::now()->between($product->flashSale->start_time, $product->flashSale->end_time) ? 'true' : 'false' }};
        const flashSalePercent = {{ $product->flashSale && \Carbon\Carbon::now()->between($product->flashSale->start_time, $product->flashSale->end_time) ? $product->flashSale->discount_percentage : 0 }};
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

            // Page load hotay hi default color wali slide par foran jump kar jayein (without visual flicker)
            if (selectedColor) {
                let defaultVariantId = "{{ $defaultVariant->id ?? '' }}";
                let matchedVariant = allVariants.find(v => v.color_name === selectedColor);

                let slides = document.querySelectorAll('.mainImageSwiper .swiper-wrapper .swiper-slide:not(.swiper-slide-duplicate)');
                let slideIndexToMove = -1;
                slides.forEach((slide, idx) => {
                    if (slide.getAttribute('data-color') === selectedColor) {
                        if (slideIndexToMove === -1) {
                            slideIndexToMove = idx;
                        }
                    }
                });

                if (slideIndexToMove !== -1 && mainSwiper) {
                    mainSwiper.slideTo(slideIndexToMove, 0); // 0 millisecond delay taake flick na ho
                }

                let colorButton = Array.from(document.querySelectorAll('.color-btn')).find(btn => btn.getAttribute('onclick').includes(selectedColor));
                if (colorButton) {
                    selectColor(colorButton, selectedColor, defaultVariantId || (matchedVariant ? matchedVariant.id : null));
                }
            } else {
                let initialThumb = document.querySelector('.thumb[data-index="0"]');
                if (initialThumb) {
                    updateThumbActiveState(initialThumb);
                }
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
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Stock is not available',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
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
                        document.querySelectorAll('.cart-count').forEach(function (el) {
                            el.innerText = data.cartCount;
                            el.classList.remove('hidden');
                        });
                        if (window.innerWidth < 768) {

                            Swal.fire({
                                position: 'center',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: false,
                                background: '#333333',
                                color: '#ffffff',
                                width: 'auto',
                                padding: '0.8rem 1.4rem', // Top/Bottom aur Left/Right ki padding ko mazeed chota kiya gaya hai
                                didOpen: (popup) => {
                                    popup.style.borderRadius = '14px';
                                    popup.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.3)';

                                    // SweetAlert ke andar jo extra content wrapper hota hai uski spacing khatam karne ke liye
                                    const content = popup.querySelector('.swal2-html-container');
                                    if (content) {
                                        content.style.margin = '0';
                                        content.style.padding = '0';
                                    }

                                    const title = popup.querySelector('.swal2-title');
                                    if (title) {
                                        title.style.textAlign = 'center';
                                        title.style.margin = '0';
                                        title.style.padding = '0';
                                        title.style.fontSize = '14px';
                                        title.style.fontWeight = '500';
                                        title.style.whiteSpace = 'nowrap';
                                    }
                                },
                                title: data.message
                            });

                        } else {

                            // 🖥️ DESKTOP TOAST
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });

                        }
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
                        if (typeof openAuthModal === 'function') {
                            openAuthModal();
                        }
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
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Stock is not available',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
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
                        if (typeof openAuthModal === 'function') {
                            openAuthModal();
                        }
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

        // 1. Color Select
        function selectColor(element, colorName, preselectedVariantId = null) {
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

            renderSizesForColor(colorName, preselectedVariantId);
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

                // Base original price determine karna (agar cut_price hai toh wo, warna variant.price)
                let baseOriginalPrice = variant.cut_price && parseFloat(variant.cut_price) > 0 ? variant.cut_price : variant.price;
                sizeButton.setAttribute('onclick', `selectSize(this, '${variant.id}', ${variant.stock}, ${variant.price}, '${baseOriginalPrice}')`);

                sizeContainer.appendChild(sizeButton);
                if ((preselectedVariantId && variant.id == preselectedVariantId) || (!preselectedVariantId && index === 0)) {
                    sizeButton.click();
                }
            });
        }

        // 3. Size Select
        function selectSize(element, variantId, stock, variantPrice, baseOriginalPrice) {
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
                btn.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');

            document.getElementById('selectedVariantId').value = variantId;
            document.getElementById('selectedVariantStock').value = stock;
            document.getElementById('qtyInput').value = 1;

            let finalPrice = variantPrice;
            let originalPriceToDisplay = parseFloat(baseOriginalPrice) > 0 ? parseFloat(baseOriginalPrice) : variantPrice;

            let cutPriceElement = document.getElementById('displayCutPrice');
            let discountBadge = document.getElementById('discountBadge');

            // Flash sale calculation sync
            if (hasActiveFlashSale && flashSalePercent > 0) {
                let discountAmount = (originalPriceToDisplay * flashSalePercent) / 100;
                finalPrice = originalPriceToDisplay - discountAmount;

                if (cutPriceElement) {
                    cutPriceElement.innerText = "Rs " + originalPriceToDisplay.toLocaleString();
                    cutPriceElement.classList.remove('hidden');
                }

                if (discountBadge) {
                    discountBadge.innerText = Math.round(flashSalePercent) + '% OFF';
                    discountBadge.classList.remove('hidden');
                }
            } else {
                if (originalPriceToDisplay > finalPrice) {
                    if (cutPriceElement) {
                        cutPriceElement.innerText = "Rs " + originalPriceToDisplay.toLocaleString();
                        cutPriceElement.classList.remove('hidden');
                    }
                } else {
                    if (cutPriceElement) {
                        cutPriceElement.classList.add('hidden');
                    }
                }
                if (discountBadge) {
                    discountBadge.classList.add('hidden');
                }
            }

            document.getElementById('displayPrice').innerText = "Rs " + finalPrice.toLocaleString();

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
                    title: stock > 0 ? 'Only ' + stock + ' items available' : 'Stock is not available',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                return;
            }
            input.value = value;
        }

        // Review Image Modal ke liye functions (Swiper ke sath)
        function openReviewModal(images, startIndex = 0) {

            const modal = document.getElementById('reviewImageModal');
            const wrapper = document.getElementById('reviewModalSwiperWrapper');

            // Purani slides remove
            wrapper.innerHTML = '';

            // New images add
            images.forEach(function (imgUrl) {

                wrapper.insertAdjacentHTML('beforeend', `
            <div class="swiper-slide review-modal-slide">
                <img src="${imgUrl}"
                     class="review-modal-image"
                     alt="Review Image">
            </div>
        `);

            });

            // Modal open
            modal.classList.remove('hidden');
            modal.style.display = 'block';

            document.body.style.overflow = 'hidden';

            // Purana swiper destroy
            if (reviewSwiperInstance) {
                reviewSwiperInstance.destroy(true, true);
                reviewSwiperInstance = null;
            }

            // New Swiper
            reviewSwiperInstance = new Swiper('.reviewModalSwiper', {
                initialSlide: startIndex,
                loop: images.length > 1,

                navigation: {
                    nextEl: '#reviewImageModal .swiper-button-next',
                    prevEl: '#reviewImageModal .swiper-button-prev',
                },

                pagination: {
                    el: '#reviewImageModal .swiper-pagination',
                    clickable: true
                },

                observer: true,
                observeParents: true,
            });
        }

        function closeReviewModal() {

            const modal = document.getElementById('reviewImageModal');

            modal.classList.add('hidden');
            modal.style.display = 'none';

            document.body.style.overflow = '';

            if (reviewSwiperInstance) {
                reviewSwiperInstance.destroy(true, true);
                reviewSwiperInstance = null;
            }
        }
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


        /* Review Modal */
        .reviewModalSwiper {
            width: 100%;
            height: 100%;
        }

        .reviewModalSwiper .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .review-modal-slide {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .review-modal-image {
            display: block;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: auto;
        }

    </style>

    <script>
        let isExpanded = false;

        function toggleFullDescription() {
            const content = document.getElementById('fullDescriptionContent');
            const toggleText = document.getElementById('descToggleText');
            const icon = document.getElementById('descToggleIcon');
            const gradientOverlay = document.getElementById('descGradientOverlay');

            isExpanded = !isExpanded;

            if (isExpanded) {
                // Expand to full scroll height
                content.style.maxHeight = content.scrollHeight + 'px';
                toggleText.textContent = 'Show Less';
                icon.style.transform = 'rotate(180deg)';
                gradientOverlay.style.opacity = '0'; // Hide gradient when fully open
            } else {
                // Collapse back
                content.style.maxHeight = '160px';
                toggleText.textContent = 'Read Full Description';
                icon.style.transform = 'rotate(0deg)';
                gradientOverlay.style.opacity = '1'; // Show gradient when collapsed
            }
        }
    </script>

    <script>
        function zoomEnter(event) {
            const result = document.getElementById('zoomResult');
            result.classList.remove('opacity-0');
        }

        function zoomOut() {
            const result = document.getElementById('zoomResult');
            result.classList.add('opacity-0');

            // Sabhi slides ki lenses chhupa dein
            document.querySelectorAll('.magnifier-lens').forEach(lens => lens.classList.add('hidden'));
        }

        function zoomIn(event) {
            const activeSlide = document.querySelector('.mainImageSwiper .swiper-slide-active');
            if (!activeSlide) return;

            const img = activeSlide.querySelector('.main-product-image');
            const lens = activeSlide.querySelector('.magnifier-lens');
            const result = document.getElementById('zoomResult');
            const zoomed = document.getElementById('zoomedImage');

            if (!img || !lens) return;

            // Lens aur Result box ko show karein
            lens.classList.remove('hidden');
            result.classList.remove('opacity-0');

            // Zoomed box mein active image ka path set karein
            zoomed.style.backgroundImage = `url('${img.src}')`;

            // Mouse ki position image ke andar nikalain
            const rect = img.getBoundingClientRect();
            let x = event.clientX - rect.left;
            let y = event.clientY - rect.top;

            // Lens ke dimensions
            const lensWidth = lens.offsetWidth;
            const lensHeight = lens.offsetHeight;

            // Lens ko image se bahar na jane dena (Boundary checks)
            if (x < lensWidth / 2) x = lensWidth / 2;
            if (x > rect.width - lensWidth / 2) x = rect.width - lensWidth / 2;
            if (y < lensHeight / 2) y = lensHeight / 2;
            if (y > rect.height - lensHeight / 2) y = rect.height - lensHeight / 2;

            // Lens ki positioning (Mouse ke center mein)
            let lensX = x - lensWidth / 2;
            let lensY = y - lensHeight / 2;
            lens.style.left = lensX + 'px';
            lens.style.top = lensY + 'px';

            // Zoomed preview background size (Proportion set karna)
            const bgWidth = rect.width * 2.5;
            const bgHeight = rect.height * 2.5;
            zoomed.style.backgroundSize = `${bgWidth}px ${bgHeight}px`;

            // Zoomed preview ki calculation
            let fx = bgWidth / rect.width;
            let fy = bgHeight / rect.height;

            let bgPosX = -(x * fx - zoomed.offsetWidth / 2);
            let bgPosY = -(y * fy - zoomed.offsetHeight / 2);

            zoomed.style.backgroundPosition = `${bgPosX}px ${bgPosY}px`;
        }
    </script>
@endsection
