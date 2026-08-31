@extends('frontend.layouts.app')
@section('content')

    <style>
        .prod_card .p_desc p {
            font-size: 10px;
            color: #6b7280; /* text-gray-500 */
            margin-top: 0.25rem; /* mt-1 */
            margin-bottom: 0;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (min-width: 640px) {
            .prod_card .p_desc p {
                font-size: 0.875rem; /* text-sm = 14px */
                margin-top: 0.5rem; /* mt-2 */
            }
        }

        @media (max-width: 640px) {
            .prod_card .p_desc p {
                font-size: 0.875rem; /* text-sm = 14px */
                margin-top: 0.5rem; /* mt-2 */

                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }

        .brands-carousel .flickity-page-dots {
            bottom: -25px; /* Isay -30px se -25px ya -20px kar dein */
            position: relative; /* Absolute ki jagah relative ya absolute with proper spacing */
        }

        .brands-carousel .flickity-page-dots {
            bottom: -14px !important;
            margin-bottom: -16px !important;
        }
    </style>


    @if(isset($setting) && $setting->is_sliders  == 1)
        <div class="w-full">
            <div class="swiper heroSwiper w-full h-[180px] xs:h-[220px] sm:h-[320px] md:h-[380px] lg:h-[435px] relative overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse($sliders as $slider)
                        <div class="swiper-slide relative">

                            <!-- 1. Image Check: Agar is_image == 1 hai aur image mojood hai tabhi image dikhayein -->
                            @if($slider->is_image == 1 && $slider->image)
                                <img src="{{ asset('storage/' . $slider->image) }}"
                                     class="w-full h-[180px] xs:h-[220px] sm:h-[320px] md:h-[380px] lg:h-[435px] object-cover">
                            @else
                            <!-- Agar image off hai ya nahi hai, toh black background ya placeholder dikha sakte hain -->
                                <div class="w-full h-[180px] xs:h-[220px] sm:h-[320px] md:h-[380px] lg:h-[435px] bg-gray-900"></div>
                            @endif

                        <!-- Overlay Content (Heading & Description checks) -->
                            @if(($slider->is_title == 1 && !empty($slider->heading)) || ($slider->is_description == 1 && !empty($slider->description)))
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <div class="text-center text-white px-3 sm:px-6 max-w-3xl mx-auto">

                                        <!-- 2. Title Check -->
                                        @if($slider->is_title == 1 && !empty($slider->heading))
                                            <h1 class="text-sm xs:text-base sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-3 drop-shadow-md">
                                                {{ $slider->heading }}
                                            </h1>
                                        @endif

                                    <!-- 3. Description Check -->
                                        @if($slider->is_description == 1 && !empty($slider->description))
                                            <p class="text-[10px] xs:text-xs sm:text-sm md:text-base max-w-xs sm:max-w-xl mx-auto mb-2 sm:mb-5 line-clamp-2 sm:line-clamp-none opacity-90">
                                                {{ $slider->description }}
                                            </p>
                                        @endif

                                        <a href="{{ route('frontendProduct') }}"
                                           class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-white text-black text-[10px] sm:text-sm px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition group shadow-lg">
                                            <i class="fa-solid fa-bag-shopping text-[10px] sm:text-sm group-hover:scale-105 transition-transform"></i>
                                            <span>Shop Now</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @empty
                        <div class="swiper-slide flex items-center justify-center h-[180px] sm:h-[350px] bg-gray-900">
                            <h2 class="text-white text-xs sm:text-lg font-medium">No Slider Found</h2>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-button-next !text-white !w-6 !h-6 sm:!w-10 sm:!h-10 after:!text-xs sm:after:!text-lg !right-1 sm:!right-3"></div>
                <div class="swiper-button-prev !text-white !w-6 !h-6 sm:!w-10 sm:!h-10 after:!text-xs sm:after:!text-lg !left-1 sm:!left-3"></div>
                <div class="swiper-pagination !bottom-1 sm:!bottom-3"></div>
            </div>
        </div>
    @endif


    <style>
        /* Active Dot Green Color */
        .heroSwiper .swiper-pagination-bullet-active {
            background-color: #10b981 !important; /* Emerald Green */
            opacity: 1 !important;
        }

        /* Inactive Dots White Color */
        .heroSwiper .swiper-pagination-bullet {
            background-color: #ffffff !important;
            opacity: 0.7 !important;
        }
    </style>

    <!-- FLASH SALE SECTION -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 sm:pt-4 mb-2 sm:mb-4 lg:mb-2 flex justify-between items-center">
        <div>
            <h2 class="text-xl sm:text-2xl mt-2 sm:mt-4 font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-amber-500 animate-bounce"></i> Flash Sales
            </h2>
            <p class="text-xs sm:text-sm text-gray-500">Hurry up! Limited time offers on top products</p>
        </div>
        <a href="{{ route('products.more', ['type' => 'flash-sale']) }}" class="text-xs sm:text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition">
            <span>More Products</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <!-- Responsive Grid Setup -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 sm:pt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mb-4 gap-1 lg:gap-3 xl:gap-3 2xl:gap-3 md:gap-3">
        @forelse($flashSaleProducts as $index => $product)
            @php
                $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                $avgRating = $product->reviews->avg('rating') ?? 0;

                // Logic: Agar screen lg (bari screen se aik step choti) hai toh sirf pehle 5 products dikhein, baqi sab screens par 6 ke 6 dikhein.
                if ($index < 5) {
                    $displayClass = 'flex';
                } elseif ($index == 5) {
                    $displayClass = 'flex lg:hidden xl:flex'; // LG par 5 dikhane ke liye 6th product ko hide kar diya hai
                } else {
                    $displayClass = 'hidden';
                }
            @endphp

            <a href="{{ route('product.detail', $product->slug) }}" class="group {{ $displayClass }}">
                {{-- Card Container --}}
                <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

                    {{-- High Contrast & Vibrant Flash Sale Badge --}}
                    @if($product->flashSale)
                        <div class="absolute top-2 -left-1 z-20 bg-gradient-to-r from-orange-600 to-amber-500 text-white pl-3 pr-3.5 py-1 rounded-r-full text-[10px] sm:text-[11px] font-extrabold shadow-md flex items-center gap-1 tracking-wide">
                            <i class="fa-solid fa-bolt text-yellow-200 text-[10px]"></i>
                            <span>{{ number_format($product->flashSale->discount_percentage, 0) }}% OFF</span>
                        </div>
                    @endif

                    {{-- IMAGE CONTAINER --}}
                    <div class="relative bg-gray-100 overflow-hidden h-48 xs:h-52 sm:h-56 2xl:h-56 md:h-56 lg:h-56">
                        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <button type="submit"
                                    class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10 hover:bg-gray-50 transition"
                                    style="padding: 4px 9px 4px 9px !important;">
                                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-600' }}"></i>
                            </button>
                        </form>

                        @if($product->mainVariantImage)
                            <img src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover object-top group-hover:scale-104 transition-transform duration-300">
                        @else
                            <img src="{{ asset('upload/no-image.jpg') }}"
                                 alt="No Image Available"
                                 class="w-full h-full object-cover object-top">
                        @endif
                    </div>

                    {{-- CARD CONTENT --}}
                    <div class="p-1.5 sm:p-2.5 xs:p-2.5 md-p-2.5 lg-p-2.5 xl-p-2.5 2xl-p-2.5 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            {{-- Product Name --}}
                            <h4 class="font-medium text-[12px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                                {{ $product->name }}
                            </h4>

                            {{-- Description --}}
                            <div class=" text-[11px] sm:text-xs text-gray-600 line-clamp-1 sm:line-clamp-1 mt-0.5 ">
                                {!! $product->description !!}
                            </div>

                            {{-- Rating Section --}}
                            <div class="flex items-center gap-1 mt-0.5">
                                <div class="flex text-yellow-500 text-[10px] sm:text-xs gap-0.5">
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
                                <span class="text-[10px] sm:text-xs text-gray-700 font-semibold">({{ number_format($avgRating, 1) }})</span>
                            </div>
                        </div>

                        {{-- Price & Stock Section (Dynamic Calculation based on Percentage) --}}
                        <div class="flex items-center justify-between gap-2 -mt-1">
                            @php
                                $variant = $product->mainVariant ?? $product->variants->first();
                                $originalPrice = $variant->cut_price ?? $variant->price ?? 0;
                                $discountPercent = $product->flashSale->discount_percentage ?? 0;

                                if ($discountPercent > 0 && !empty($variant->cut_price)) {
                                    $discountedPrice = $originalPrice - ($originalPrice * ($discountPercent / 100));
                                } else {
                                    $discountedPrice = $variant->price ?? 0;
                                }
                            @endphp

                            <div class="flex flex-col">
                                {{-- Discounted / Main Sale Price --}}
                                <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                                Rs {{ number_format($discountedPrice) }}
                            </span>

                                {{-- Original / Cut Price --}}
                                @if($discountPercent > 0 && !empty($variant->cut_price) && $variant->cut_price > $discountedPrice)
                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                    Rs {{ number_format($variant->cut_price) }}
                                </span>
                                @endif
                            </div>

                            <div class="flex-shrink-0">
                                @php $totalStock = $product->variants->sum('stock'); @endphp
                                @if($totalStock <= 0)
                                    <span class="inline-block bg-red-100 text-red-700 text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                    Out of Stock
                                </span>
                                @else
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                    <span class="text-emerald-800 font-bold text-[10px]">{{ $totalStock }}</span> In Stock
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-8 text-center text-gray-400 text-sm bg-white rounded-lg border border-gray-200">
                No active flash sales right now. Check back later!
            </div>
        @endforelse
    </div>

    <!-- 2. TOP 8 MOST ORDERED PRODUCTS (BESTSELLERS) -->
    <!-- BESTSELLING PRODUCTS SECTION -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 sm:pt-2 mb-2 sm:mb-2 lg:mb-1 flex justify-between items-center">
        <div>
            <h2 class="text-xl sm:text-2xl mt-1 sm:mt-2 font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-fire text-rose-500 animate-pulse"></i> Bestselling Products
            </h2>
            <p class="text-xs sm:text-sm text-gray-500">Our top 8 most popular and ordered items</p>
        </div>

        <!-- Yeh raha Bestselling ke liye More Products ka link -->
        <a href="{{ route('products.more', ['type' => 'bestselling']) }}" class="text-xs sm:text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition">
            <span>More Products</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div
        class="container mx-auto px-3 sm:px-6 md:px-7 sm:pt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mb-4 gap-3">
        @foreach($topOrderedProducts as $index => $product)
            @php
                $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                $avgRating = $product->reviews->avg('rating') ?? 0;

                // CORRECTED ROW-BASED RESPONSIVE LOGIC:
                // - Mobile (2 cols): 6 rows = 12 items (0 to 11)
                // - SM (3 cols): 4 rows = 12 items (0 to 11)
                // - MD (4 cols): 3 rows = 12 items (0 to 11) -> 4 columns x 3 rows = 12 items
                // - LG (5 cols): 2 rows = 10 items (0 to 9) -> 5 columns x 2 rows = 10 items
                // - XL (6 cols): 2 rows = 12 items (0 to 11) -> 6 columns x 2 rows = 12 items

                if ($index < 10) {
                    // Pehle 10 items sabhi screens par dikhenge (LG ki 2 rows pori - 5x2=10)
                    $displayClass = 'flex';
                } elseif ($index >= 10 && $index < 12) {
                    // 11th aur 12th items: LG screen par hidden rahenge (kyunki LG ko sirf 10 chahiye),
                    // lekin Mobile, SM, MD aur XL par visible honge taake unki rows pori ho sakein.
                    $displayClass = 'flex sm:flex md:flex lg:hidden xl:flex';
                } else {
                    // 13th item aur uske baad sabhi hidden
                    $displayClass = 'hidden';
                }
            @endphp

            <a href="{{ route('product.detail', $product->slug) }}" class="group {{ $displayClass }}">
                {{-- Card Container --}}
                <div
                    class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

            <span
                class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 z-10 bg-rose-500 text-white text-[9px] sm:text-[10px] font-bold uppercase px-1.5 py-0.5 rounded-md shadow">
                Top Seller
            </span>

                    {{-- IMAGE CONTAINER --}}
                    <div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">
                        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <button type="submit"
                                    class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10"
                                    style="padding: 4px 9px 4px 9px !important;">
                                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                            </button>
                        </form>

                        @if($product->mainVariantImage)
                            <img src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300">
                        @else
                            <img src="{{ asset('upload/no-image.jpg') }}"
                                 alt="No Image Available"
                                 class="w-full h-full object-cover">
                        @endif
                    </div>

                    {{-- CARD CONTENT --}}
                    <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            {{-- Product Name --}}
                            <h4 class="font-medium xs:text-[14px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                                {{ $product->name }}
                            </h4>

                            {{-- Description --}}
                            <div class="text-[12px] sm:text-xs text-gray-500 line-clamp-1 sm:line-clamp-1 mt-0.5">
                                {!! $product->description !!}
                            </div>

                            {{-- Rating Section --}}
                            <div class="flex items-center gap-1 sm:mt-1.5">
                                <div class="flex text-yellow-400 text-[10px] sm:text-xs gap-0.5">
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
                                <span class="text-[10px] sm:text-xs text-gray-500 font-medium">({{ number_format($avgRating, 1) }})</span>
                            </div>
                        </div>

                        {{-- Price & Stock Section --}}
                        <div class="flex items-center justify-between gap-2 mt-auto">
                            @php $variant = $product->mainVariant ?? $product->variants->first(); @endphp
                            <div class="flex flex-col">
                        <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                            Rs {{ number_format($variant->price ?? 0) }}
                        </span>
                                @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                Rs {{ number_format($variant->cut_price) }}
                            </span>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                @php $totalStock = $product->variants->sum('stock'); @endphp
                                @if($totalStock <= 0)
                                    <span
                                        class="inline-block bg-red-100 text-red-600 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                Out of Stock
                            </span>
                                @else
                                    <span
                                        class="inline-block bg-emerald-100 text-emerald-700 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                <span class="text-emerald-800 font-bold text-[10px]">{{ $totalStock }}</span> In Stock
                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if(isset($setting) && $setting->show_mid_banners == 1)
        @php
            // Sort order ke mutabik banners ko safely map karna agar $banners mojood ho
            $banner1 = isset($banners) ? $banners->where('sort_order', 1)->first() : null;
            $banner2 = isset($banners) ? $banners->where('sort_order', 2)->first() : null;
            $banner3 = isset($banners) ? $banners->where('sort_order', 3)->first() : null;

            // Check conditions for images (with is_image toggle check)
            $hasImage1 = ($banner1 && $banner1->is_image == 1 && !empty($banner1->image) && file_exists(public_path('storage/' . $banner1->image)));
            $hasImage2 = ($banner2 && $banner2->is_image == 1 && !empty($banner2->image) && file_exists(public_path('storage/' . $banner2->image)));
            $hasImage3 = ($banner3 && $banner3->is_image == 1 && !empty($banner3->image) && file_exists(public_path('storage/' . $banner3->image)));
        @endphp

        <div class="container mx-auto px-3 sm:px-6 md:px-7 sm:py-2 mb-2">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

                <!-- 1st Banner (Large Left Side) -->
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden group h-[280px] sm:h-[400px] lg:h-[500px] bg-gradient-to-br from-gray-900 via-gray-800 to-black">
                    @if(!$hasImage1)
                        <span class="absolute top-3 right-3 z-20 bg-amber-500 text-black text-[10px] font-bold px-2.5 py-1 rounded-md shadow-md uppercase tracking-wider">
                        Default View
                    </span>
                    @else
                        <img src="{{ asset('storage/' . $banner1->image) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif

                <!-- Content Overlay (Title & Description checks) -->
                    @php
                        $showTitle1 = $banner1 && $banner1->is_title == 1 && (!empty($banner1->name) || !empty($banners[0]->name));
                        $showDesc1  = $banner1 && $banner1->is_description == 1 && (!empty($banner1->description) || !empty($banners[0]->description));
                    @endphp

                    @if($showTitle1 || $showDesc1)
                        <div class="absolute inset-0 bg-black/40 flex items-end p-5 sm:p-8">
                            <div>
                                @if($banner1->is_title == 1)
                                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
                                        {{ $banner1->name ?? ($banners[0]->name ?? '') }}
                                    </h2>
                                @endif

                                @if($banner1->is_description == 1)
                                    <p class="text-xs sm:text-sm lg:text-base text-white/90 mt-1 sm:mt-2">
                                        {{ $banner1->description ?? ($banners[0]->description ?? '') }}
                                    </p>
                                @endif

                                <a href="{{ route('frontendProduct') }}"
                                   class="inline-block mt-3 sm:mt-4 bg-white text-black px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm font-semibold hover:bg-gray-200 transition">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Small Banners (Right Side Stack) -->
                <div class="grid grid-rows-2 gap-4 sm:gap-6 h-[280px] sm:h-[400px] lg:h-[500px]">

                    <!-- 2nd Banner (Top Right) -->
                    <div class="relative rounded-xl sm:rounded-2xl overflow-hidden h-full group bg-gradient-to-br from-gray-900 via-gray-800 to-black">
                        @if(!$hasImage2)
                            <span class="absolute top-3 right-3 z-20 bg-amber-500 text-black text-[10px] font-bold px-2.5 py-1 rounded-md shadow-md uppercase tracking-wider">
                            Default View
                        </span>
                        @else
                            <img src="{{ asset('storage/' . $banner2->image) }}"
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                        @endif

                        @php
                            $showTitle2 = $banner2 && $banner2->is_title == 1 && (!empty($banner2->name) || !empty($banners[1]->name));
                            $showDesc2  = $banner2 && $banner2->is_description == 1 && (!empty($banner2->description) || !empty($banners[1]->description));
                        @endphp

                        @if($showTitle2 || $showDesc2)
                            <div class="absolute inset-0 bg-black/40 flex items-end p-4 sm:p-6">
                                <div>
                                    @if($banner2->is_title == 1)
                                        <h3 class="text-xl sm:text-2xl font-bold text-white">
                                            {{ $banner2->name ?? ($banners[1]->name ?? '') }}
                                        </h3>
                                    @endif

                                    @if($banner2->is_description == 1)
                                        <p class="text-xs sm:text-sm text-white/95 mt-1">
                                            {{ $banner2->description ?? ($banners[1]->description ?? '') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- 3rd Banner (Bottom Right) -->
                    <div class="relative rounded-xl sm:rounded-2xl overflow-hidden h-full group bg-gradient-to-br from-gray-900 via-gray-800 to-black">
                        @if(!$hasImage3)
                            <span class="absolute top-3 right-3 z-20 bg-amber-500 text-black text-[10px] font-bold px-2.5 py-1 rounded-md shadow-md uppercase tracking-wider">
                            Default View
                        </span>
                        @else
                            <img src="{{ asset('storage/' . $banner3->image) }}"
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                        @endif

                        @php
                            $showTitle3 = $banner3 && $banner3->is_title == 1 && (!empty($banner3->name) || !empty($banners[2]->name));
                            $showDesc3  = $banner3 && $banner3->is_description == 1 && (!empty($banner3->description) || !empty($banners[2]->description));
                        @endphp

                        @if($showTitle3 || $showDesc3)
                            <div class="absolute inset-0 bg-black/40 flex items-end p-4 sm:p-6">
                                <div>
                                    @if($banner3->is_title == 1)
                                        <h3 class="text-xl sm:text-2xl font-bold text-white">
                                            {{ $banner3->name ?? ($banners[2]->name ?? '') }}
                                        </h3>
                                    @endif

                                    @if($banner3->is_description == 1)
                                        <p class="text-xs sm:text-sm text-white/95 mt-1">
                                            {{ $banner3->description ?? ($banners[2]->description ?? '') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    @endif

    <!-- 4. FEATURED PRODUCTS -->
    @if($featuredProducts->isNotEmpty())
        <!-- FEATURED PRODUCTS SECTION -->
        <div class="container mx-auto px-3 sm:px-6 md:px-7 sm:py-1 mt-2 sm:mt-4 lg:mt-3 mx-auto flex justify-between items-center">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center mt-1 gap-2">
                    <i class="fa-solid fa-star text-amber-500 animate-spin-slow"></i> Featured Products
                </h2>
                <p class="text-xs sm:text-sm text-gray-500">Handpicked top quality items selected just for you</p>
            </div>

            <!-- Yeh raha Featured ke liye More Products ka link -->
            <a href="{{ route('products.more', ['type' => 'featured']) }}" class="text-xs sm:text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 transition">
                <span>More Products</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div
            class="container mx-auto px-3 sm:px-10 md:px-7 py-2 sm:py-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 mb-2 sm:mb-8">
            @foreach($featuredProducts as $index => $product)
                @php
                    $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                    $avgRating = $product->reviews->avg('rating') ?? 0;

                    // EXACT ROW-BASED RESPONSIVE LOGIC FOR FEATURED PRODUCTS:
                    // - Mobile (2 cols): 6 rows = 12 items (0 to 11)
                    // - SM (3 cols): 4 rows = 12 items (0 to 11)
                    // - MD (4 cols): 3 rows = 12 items (0 to 11)
                    // - LG (5 cols): 2 rows = 10 items (0 to 9)
                    // - XL (6 cols): 2 rows = 12 items (0 to 11)

                    if ($index < 10) {
                        // Pehle 10 items sabhi screens par dikhenge (LG ki 2 rows pori - 5x2=10)
                        $displayClass = 'flex';
                    } elseif ($index >= 10 && $index < 12) {
                        // 11th aur 12th items: LG screen par hidden, baaki sab par visible
                        $displayClass = 'flex sm:flex md:flex lg:hidden xl:flex';
                    } else {
                        // Baaki sabhi items hidden
                        $displayClass = 'hidden';
                    }
                @endphp

                <a href="{{ route('product.detail', $product->slug) }}" class="group {{ $displayClass }}">
                    {{-- Border aur shadow ko mazeed prominent kar diya hai taaki border saaf nazar aaye --}}
                    <div
                        class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-300 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

    <span
        class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 z-10 bg-amber-500 text-white text-[9px] sm:text-[10px] font-bold uppercase px-1.5 py-0.5 rounded-md shadow">
        Featured
    </span>

                        {{-- IMAGE CONTAINER --}}
                        <div class="relative bg-gray-100 overflow-hidden h-50 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">
                            <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                        class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10"
                                        style="padding: 4px 9px 4px 9px !important;">
                                    <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                                </button>
                            </form>
                            @if($product->mainVariantImage)
                                <img
                                    class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300"
                                    src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}"
                                    alt="{{ $product->name }}">
                            @else
                                <img class="w-full h-full object-cover" src="{{ asset('upload/no-image.jpg') }}"
                                     alt="No Image Available">
                            @endif
                        </div>

                        {{-- CARD CONTENT --}}
                        <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2">
                            <div>
                                {{-- Product Name --}}
                                <h4 class="font-medium xs:text-[14px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                                    {{ $product->name }}
                                </h4>

                                {{-- Description --}}
                                <div class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 sm:line-clamp-1 mt-0.5">
                                    {!! $product->description !!}
                                </div>

                                {{-- Rating Section --}}
                                <div class="flex items-center gap-1 sm:mt-1.5">
                                    <div class="flex text-yellow-400 text-[10px] sm:text-xs gap-0.5">
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
                                    <span class="text-[10px] sm:text-xs text-gray-500 font-medium">({{ number_format($avgRating, 1) }})</span>
                                </div>
                            </div>

                            {{-- Price & Stock Section --}}
                            <div class="flex items-center justify-between gap-2 mt-auto">
                                @php $variant = $product->mainVariant ?? $product->variants->first(); @endphp
                                <div class="flex flex-col">
                <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                    Rs {{ number_format($variant->price ?? 0) }}
                </span>
                                    @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                        <span
                                            class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                        Rs {{ number_format($variant->cut_price) }}
                    </span>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    @php $totalStock = $product->variants->sum('stock'); @endphp
                                    @if($totalStock <= 0)
                                        <span
                                            class="inline-block bg-red-100 text-red-600 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                        Out of Stock
                    </span>
                                    @else
                                        <span
                                            class="inline-block bg-emerald-100 text-emerald-700 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                        <span class="text-emerald-800 font-bold text-[10px]">{{ $totalStock }}</span> In Stock
                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <!-- 4.1 STICKY BACKGROUND FULL-WIDTH CONTAINER -->
    @if(isset($setting) && $setting->show_featured_banner == 1)
        @php
            // Check if image is allowed via toggle and exists
            $hasFeaturedImage = ($featuredBanner && $featuredBanner->is_image == 1 && !empty($featuredBanner->image));
        @endphp

        <div class="relative w-full sm:my-1 bg-fixed bg-center bg-cover h-[220px] sm:h-[350px] md:h-[400px] flex items-center justify-center"
             @if($hasFeaturedImage) style="background-image: url('{{ asset('storage/' . $featuredBanner->image) }}');" @endif>

            <div class="absolute inset-0 bg-black/85"></div>

            <div class="relative z-10 text-center text-white px-4 sm:px-6 max-w-3xl mx-auto">
            <span class="bg-amber-500 text-black text-[10px] sm:text-xs font-bold uppercase px-3 py-1 rounded-full tracking-wider mb-1 sm:mb-3 inline-block">
                Special Selection
            </span>

                <!-- Title Check (is_title) -->
                @if(!$featuredBanner || $featuredBanner->is_title == 1)
                    <h2 class="text-xl sm:text-3xl md:text-5xl font-extrabold tracking-tight mb-1 sm:mb-4">
                        {{ $featuredBanner->name ?? 'Default Banner Title Here' }}
                    </h2>
                @endif

            <!-- Description Check (is_description) -->
                @if(!$featuredBanner || $featuredBanner->is_description == 1)
                    <p class="text-gray-300 text-[11px] sm:text-sm md:text-base leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                        {{ $featuredBanner->description ?? 'Yeh default description hai jo backend se data na milne par automatically show hogi.' }}
                    </p>
                @endif

                <a href="{{ route('frontendProduct') }}"
                   class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold px-5 sm:px-8 py-2 sm:py-3.5 rounded-xl hover:bg-amber-400 hover:text-black transition duration-300 shadow-lg group text-xs sm:text-base">
                    <i class="fa-solid fa-cart-shopping transition-transform group-hover:scale-105"></i>
                    <span>{{ $featuredBanner->button_name ?? 'Shop Now' }}</span>
                </a>
            </div>
        </div>
    @endif

    <!-- 5. TOP BRANDS SECTION -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-2 sm:py-2 mx-auto sm:mt-4">
        <div class="flex justify-between items-center">
            <div>
                <!-- Header updated with icon -->
                <h2 class="text-base sm:text-lg font-bold text-gray-800 tracking-tight flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-award text-xs sm:text-sm"></i>
        </span>
                    <span>Our Top Brands</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 mb-2">Browse items from your favorite trustworthy
                    labels</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-3 sm:px-6 md:px-7 pt-0 sm:mb-8 mb-3 lg:mb-12" style="margin-bottom: 20px !important;">
    @php $brandCount = count($brands ?? []); @endphp
    @if($brandCount > 6)
        <!-- Swiper Container with pb-12 for padding bottom -->
            <div class="swiper brandsSwiper relative overflow-hidden" style="margin-bottom: -22px">
                <div class="swiper-wrapper">
                    @foreach($brands as $brand)
                        <div class="swiper-slide h-auto pr-2 sm:pr-3">
                            <a href="{{ route('categories', ['brand' => $brand->slug]) }}"
                               class="bg-white rounded-xl p-3 sm:p-4 border border-gray-200 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition duration-300 group h-full">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center mb-2 sm:mb-3">
                                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                                         class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition duration-300">
                                </div>
                                <span class="text-xs sm:text-sm font-semibold text-gray-700 group-hover:text-black">
                            {{ $brand->name }}
                        </span>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Dots Container -->
                <div class="swiper-pagination brands-swiper-pagination"></div>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-6">
                @forelse($brands as $brand)
                    <a href="{{ route('categories', ['brand' => $brand->slug]) }}"
                       class="bg-white rounded-xl p-3 sm:p-4 border border-gray-200 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition duration-300 group">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center mb-2 sm:mb-3">
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                                 class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition duration-300">
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-gray-700 group-hover:text-black">
                    {{ $brand->name }}
                </span>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-4 text-sm">No Brands Found</div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Custom CSS to push pagination below cards -->
    <style>
        .brandsSwiper {
            padding-bottom: 40px !important; /* Space for dots at the bottom */
        }
        .brands-swiper-pagination {
            bottom: 0px !important; /* Position dots at the very bottom edge */
        }
        .brandsSwiper {
            padding-bottom: 40px !important;
        }

        .brands-swiper-pagination {
            bottom: 0px !important;
        }

        /* Active Dot ka Color (Emerald Green) */
        .brands-swiper-pagination .swiper-pagination-bullet-active {
            background-color: #10b981 !important; /* Emerald-500 */
            opacity: 1 !important;
        }

        /* Inactive Dots ka Color (Thoda halka background) */
        .brands-swiper-pagination .swiper-pagination-bullet {
            background-color: #9ca3af; /* Gray-400 */
        }

        .swiper-horizontal>.swiper-pagination-bullets, .swiper-pagination-bullets.swiper-pagination-horizontal, .swiper-pagination-custom, .swiper-pagination-fraction{
            margin-bottom: 10px;
        }

    </style>

    <!-- 5.1 FULL-WIDTH 50/50 STICKY PARALLAX BRAND SHOWCASE -->
    @if(isset($setting) && $setting->show_brand_banner == 1)
        @php
            // Check if image is allowed via toggle and exists
            $hasBrandBannerImage = ($brandBanner && $brandBanner->is_image == 1 && !empty($brandBanner->image));
        @endphp

        <div class="relative w-full mt-4 mb-4 sm:mb-6 bg-gray-900 overflow-hidden shadow-2xl">
            <div class="container mx-auto px-3 sm:px-6 md:px-7 py-3 sm:py-6 grid grid-cols-1 lg:grid-cols-2 min-h-[220px] sm:min-h-[400px] lg:min-h-[480px]">

                <div class="flex flex-col justify-center py-4 sm:py-10 lg:py-16 text-white z-10 bg-gradient-to-r from-gray-900 via-gray-900 to-gray-900/90">
                <span class="inline-flex items-center gap-1.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 text-[10px] sm:text-xs font-bold uppercase px-3 sm:px-3.5 py-0.5 sm:py-1.5 rounded-full tracking-wider mb-2 sm:mb-6 w-max">
                    <i class="fa-solid fa-crown text-rose-500"></i> Official Partner Showcase
                </span>

                    <!-- Title Check (is_title) -->
                    @if(!$brandBanner || $brandBanner->is_title == 1)
                        <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight mb-2 sm:mb-6 leading-tight">
                            {{ $brandBanner->name ?? 'Exclusive Brand Showcase' }}
                        </h2>
                    @endif

                <!-- Description Check (is_description) -->
                    @if(!$brandBanner || $brandBanner->is_description == 1)
                        <p class="text-gray-300 text-xs sm:text-sm md:text-base lg:text-lg leading-relaxed mb-3 sm:mb-8 line-clamp-2 sm:line-clamp-none">
                            {{ $brandBanner->description ?? 'Discover our premium collections and official brand partners with exceptional quality and style tailored just for you.' }}
                        </p>
                    @endif

                <!-- Button Check (is_button) -->
                    @if(!$brandBanner || $brandBanner->is_button == 1)
                        <div>
                            <a href="{{ route('frontendProduct') }}"
                               class="inline-flex items-center gap-2 sm:gap-3 bg-white text-gray-900 font-bold px-5 sm:px-8 py-2 sm:py-4 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-xl group text-xs sm:text-base">
                                <span>{{ $brandBanner->button_name ?? 'Explore Collection' }}</span>
                                <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Dynamic Background Image with toggle check (is_image) -->
                <div class="relative hidden lg:block min-h-[300px] sm:min-h-[350px] lg:min-h-full">
                    @if($hasBrandBannerImage)
                        <div class="absolute inset-0 bg-scroll sm:bg-fixed bg-center bg-cover"
                             style="background-image: url('{{ asset('storage/' . $brandBanner->image) }}');">
                            <div class="absolute inset-0 bg-black/65 lg:bg-gradient-to-r lg:from-gray-900 lg:via-black/50 lg:to-black/60"></div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif

    <!-- 6. "FOR YOU" PERSONALIZED PRODUCTS SECTION -->
    @if(isset($products) && $products->isNotEmpty())
        <div class="container mx-auto px-3 sm:px-6 md:px-7 mx-auto sm:mt-2 last:mb-9 md:last:mb-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i> Handpicked For You
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Personalized recommendations tailored specially to your taste</p>
            </div>
        </div>

        <!-- GRID CONTAINER WITH ID -->
        <div id="for-you-grid" class="container mx-auto px-3 sm:px-6 md:px-7 py-2 sm:py-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 mb-4">
            @include('frontend.partials.for-you-cards', ['products' => $products])
        </div>

        <!-- NO MORE PRODUCTS BUTTON -->
        <div id="no-more-products" class="text-center -mt-3 mb-16 sm:mb-5 lg:mb-6 md:mt-3 hidden">
            <span class="inline-flex items-center gap-2 bg-gray-700 text-white text-xs sm:text-sm font-medium px-5 py-2.5 rounded-md shadow-md cursor-default">
                <i class="fa-solid fa-circle-check text-emerald-400"></i> No More Products
            </span>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        let page = 1;
        let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;

        $('main').scroll(function() {
            let $main = $(this);

            if (!hasMorePages) return;

            if($main.scrollTop() + $main.innerHeight() >= $main[0].scrollHeight - 300) {
                if(isLoading) return;

                isLoading = true;
                page++;

                // Shimmer Effect HTML (Spinner ki jagah grid mein append hoga)
                let shimmerHtml = `
                    @for($i = 0; $i < 6; $i++)
                <div class="product-shimmer bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">
                    <div class="bg-gray-200 h-50 xs:h-44 sm:h-60 2xl:h-57 md:h-52 lg:h-55 w-full"></div>
                    <div class="px-2 py-2 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-full"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="h-5 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-5 bg-gray-200 rounded w-1/4"></div>
                        </div>
                    </div>
                </div>
@endfor
                `;

                $('#for-you-grid').append(shimmerHtml);

                $.ajax({
                    url: "{{ route('index') }}?page=" + page,
                    type: "GET",
                    success: function(response) {
                        // Response aate hi shimmer cards hata dena
                        $('.product-shimmer').remove();

                        if($.trim(response) === "") {
                            hasMorePages = false;
                            $('#no-more-products').removeClass('hidden');
                        } else {
                            $('#for-you-grid').append(response);
                            isLoading = false;
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // Error aane par bhi shimmer hata dena
                        $('.product-shimmer').remove();
                        isLoading = false;
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Hero Swiper Initialization
            new Swiper(".heroSwiper", {
                loop: true,
                effect: "fade",
                speed: 1000,
                autoplay: { delay: 3000, disableOnInteraction: false },
                pagination: { el: ".swiper-pagination", clickable: true },
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            });

            // Brands Swiper Initialization with Pagination
            new Swiper(".brandsSwiper", {
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                pagination: {
                    el: ".brands-swiper-pagination",
                    clickable: true,
                },
                slidesPerView: 2,
                spaceBetween: 12,
                breakpoints: {
                    640: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    1024: { slidesPerView: 6 }
                }
            });
        });
    </script>
@endpush
