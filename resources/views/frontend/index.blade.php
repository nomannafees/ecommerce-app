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



    <div class="w-full">
        <div
            class="swiper heroSwiper w-full h-[180px] xs:h-[220px] sm:h-[320px] md:h-[380px] lg:h-[435px] relative overflow-hidden">
            <div class="swiper-wrapper">
                @forelse($sliders as $slider)
                    <div class="swiper-slide relative">
                        <img src="{{ asset('storage/' . $slider->image) }}"
                             class="w-full h-[180px] xs:h-[220px] sm:h-[320px] md:h-[380px] lg:h-[435px] object-cover">

                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <div class="text-center text-white px-3 sm:px-6 max-w-3xl mx-auto">
                                <h1 class="text-sm xs:text-base sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-3 drop-shadow-md">
                                    {{ $slider->heading }}
                                </h1>
                                <p class="text-[10px] xs:text-xs sm:text-sm md:text-base max-w-xs sm:max-w-xl mx-auto mb-2 sm:mb-5 line-clamp-2 sm:line-clamp-none opacity-90">
                                    {{ $slider->description }}
                                </p>
                                <a href="{{ route('frontendProduct') }}"
                                   class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-white text-black text-[10px] sm:text-sm px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg font-semibold hover:bg-gray-200 transition group shadow-lg">
                                    <i class="fa-solid fa-bag-shopping text-[10px] sm:text-sm group-hover:scale-105 transition-transform"></i>
                                    <span>Shop Now</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide flex items-center justify-center h-[180px] sm:h-[350px] bg-gray-900">
                        <h2 class="text-white text-xs sm:text-lg font-medium">No Slider Found</h2>
                    </div>
                @endforelse
            </div>

            <div
                class="swiper-button-next !text-white !w-6 !h-6 sm:!w-10 sm:!h-10 after:!text-xs sm:after:!text-lg !right-1 sm:!right-3"></div>
            <div
                class="swiper-button-prev !text-white !w-6 !h-6 sm:!w-10 sm:!h-10 after:!text-xs sm:after:!text-lg !left-1 sm:!left-3"></div>
            <div class="swiper-pagination !bottom-1 sm:!bottom-3"></div>
        </div>
    </div>
    <!-- 2. TOP 8 MOST ORDERED PRODUCTS (BESTSELLERS) -->
    <div
        class="container mx-auto px-3 sm:px-6 md:px-7 sm:pt-4 mb-2 sm:mb-4 lg:mb-2 mx-auto  flex justify-between items-center">
        <div>
            <h2 class="text-xl sm:text-2xl mt-2 sm:mt-4 font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-fire text-rose-500"></i> Bestselling Products
            </h2>
            <p class="text-xs sm:text-sm text-gray-500">Our top 8 most popular and ordered items</p>
        </div>
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
                        <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
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

    <!-- 3. PROMOTIONAL MID BANNERS GRID -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7  sm:py-2 mb-2">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            <div class="relative rounded-xl sm:rounded-2xl overflow-hidden group h-[280px] sm:h-[400px] lg:h-[500px]">
                <img src="{{ asset('storage/banner/1721825245.png') }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/40 flex items-end p-5 sm:p-8">
                    <div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">Summer Collection</h2>
                        <p class="text-xs sm:text-sm lg:text-base text-white mt-1 sm:mt-2">Up to 50% OFF</p>
                        <a href="{{ route('frontendProduct') }}"
                           class="inline-block mt-3 sm:mt-4 bg-white text-black px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-xs sm:text-sm font-semibold hover:bg-gray-200 transition">
                            Shop Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Small Banners (Right Side Stack) -->
            <div class="grid grid-rows-2 gap-4 sm:gap-6 h-[280px] sm:h-[400px] lg:h-[500px]">
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden h-full group">
                    <img src="{{ asset('storage/banner/1721825256.png') }}"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 flex items-end p-4 sm:p-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-white">New Arrival</h3>
                    </div>
                </div>
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden h-full group">
                    <img src="{{ asset('storage/banner/1721825269.png') }}"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 flex items-end p-4 sm:p-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-white">Exclusive Deals</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 4. FEATURED PRODUCTS -->
    @if($featuredProducts->isNotEmpty())
        <div
            class="container mx-auto px-3 sm:px-6 md:px-7 sm:py-1 mt-2 sm:mt-4 lg:mt-3 mx-auto  flex justify-between items-center">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center mt-1 gap-2">
                    <i class="fa-solid fa-star text-amber-500"></i> Featured Products
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 ">Handpicked top quality items selected just for you</p>
            </div>
        </div>

        <div
            class="container mx-auto px-6 sm:px-10 md:px-7 py-2 sm:py-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 mb-2 sm:mb-8">
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
                <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
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
    <div
        class="relative w-full sm:my-1 bg-fixed bg-center bg-cover h-[220px] sm:h-[350px] md:h-[400px] flex items-center justify-center"
        style="background-image: url('{{ asset('storage/banner/1721825245.png') }}');">

        <div class="absolute inset-0 bg-black/85"></div>

        <div class="relative z-10 text-center text-white px-4 sm:px-6 max-w-3xl mx-auto">
        <span
            class="bg-amber-500 text-black text-[10px] sm:text-xs font-bold uppercase px-3 py-1 rounded-full tracking-wider mb-1 sm:mb-3 inline-block">
            Special Selection
        </span>

            <h2 class="text-xl sm:text-3xl md:text-5xl font-extrabold tracking-tight mb-1 sm:mb-4">
                Discover Excellence in Every Detail
            </h2>

            <p class="text-gray-300 text-[11px] sm:text-sm md:text-base leading-relaxed mb-3 sm:mb-6 line-clamp-2 sm:line-clamp-none">
                Our handpicked featured collection brings you unmatched quality, trendsetting designs, and everyday
                reliability. Upgrade your style and elevate your lifestyle today.
            </p>

            <a href="{{ route('frontendProduct') }}"
               class="inline-flex items-center gap-2 bg-white text-gray-900 font-semibold px-5 sm:px-8 py-2 sm:py-3.5 rounded-xl hover:bg-amber-400 hover:text-black transition duration-300 shadow-lg group text-xs sm:text-base">
                <i class="fa-solid fa-cart-shopping transition-transform group-hover:scale-105"></i>
                <span>Explore Featured Range</span>
            </a>
        </div>
    </div>

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

    <div class="container mx-auto px-3 sm:px-6 md:px-7 mx-auto pt-0  sm:mb-8 mb-3 lg:mb-12">
        @php $brandCount = count($brands ?? []); @endphp
        @if($brandCount > 6)
            <div class="brands-carousel relative">
            @foreach($brands as $brand)
                <!-- pr-2 sm:pr-3 se carousel items ke darmiyan spacing maintain ki gayi hai -->
                    <div class="brand-cell w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 pr-2 sm:pr-3">
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

    <!-- 5.1 FULL-WIDTH 50/50 STICKY PARALLAX BRAND SHOWCASE -->
    <div class="relative w-full mt-4 mb-4 sm:mb-6 bg-gray-900 overflow-hidden shadow-2xl">
        <div
            class="container mx-auto px-3 sm:px-6 md:px-7 py-3 sm:py-6 mx-auto grid grid-cols-1 lg:grid-cols-2 min-h-[220px] sm:min-h-[400px] lg:min-h-[480px]">
            <div
                class="flex flex-col justify-center py-4 sm:py-10 lg:py-16 text-white z-10 bg-gradient-to-r from-gray-900 via-gray-900 to-gray-900/90">
            <span
                class="inline-flex items-center gap-1.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 text-[10px] sm:text-xs font-bold uppercase px-3 sm:px-3.5 py-0.5 sm:py-1.5 rounded-full tracking-wider mb-2 sm:mb-6 w-max">
                <i class="fa-solid fa-crown text-rose-500"></i> Official Partner Showcase
            </span>
                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight mb-2 sm:mb-6 leading-tight">
                    Shop Premium Brands You Trust
                </h2>
                <p class="text-gray-300 text-xs sm:text-sm md:text-base lg:text-lg leading-relaxed mb-3 sm:mb-8 line-clamp-2 sm:line-clamp-none">
                    Explore products directly sourced from top-rated industry leaders. Guaranteed authenticity, premium
                    build, and exclusive collection updates waiting just for you.
                </p>
                <div>
                    <a href="{{ route('frontendProduct') }}"
                       class="inline-flex items-center gap-2 sm:gap-3 bg-white text-gray-900 font-bold px-5 sm:px-8 py-2 sm:py-4 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300 shadow-xl group text-xs sm:text-base">
                        <span>Explore All Brands</span>
                        <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1.5"></i>
                    </a>
                </div>
            </div>
            <!-- Mobile par image hide aur Large screen par show karne ke liye 'hidden lg:block' lagaya hai -->
            <div class="relative hidden lg:block min-h-[300px] sm:min-h-[350px] lg:min-h-full">
                <div class="absolute inset-0 bg-scroll sm:bg-fixed bg-center bg-cover"
                     style="background-image: url('{{ asset('storage/banner/1721825256.png') }}');">
                    <div
                        class="absolute inset-0 bg-black/65 lg:bg-gradient-to-r lg:from-gray-900 lg:via-black/50 lg:to-black/60"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. "FOR YOU" PERSONALIZED PRODUCTS SECTION -->
    @if(isset($products) && $products->isNotEmpty())
        <div
            class="container mx-auto px-3 sm:px-6 md:px-7  mx-auto  sm:mt-2 last:mb-9 md:last:mb-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <!-- Icon Color Changed to Emerald Green -->
                    <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i> Handpicked For You
                </h2>
                <p class="text-xs sm:text-sm text-gray-500  mb-1">Personalized recommendations tailored specially to
                    your taste</p>
            </div>
        </div>

        <!-- Mobile par mb-20 spacing hogi aur desktop (md) par mb-0 ho jayegi -->
        <div
            class="container mx-auto px-3 sm:px-6 md:px-7 py-2 sm:py-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 mb-15 md:mb-10">
            @foreach($products as $product)
                @php
                    $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                    $avgRating = $product->reviews->avg('rating') ?? 0;
                @endphp

                <a href="{{ route('product.detail', $product->slug) }}" class="group flex">
                    <div
                        class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-300 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

                        {{-- IMAGE CONTAINER --}}
                        <div class="relative bg-gray-100 overflow-hidden h-50 xs:h-44 sm:h-50 2xl:h-50 md:h-47 lg:h-50">
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
                        <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
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

    <!-- STYLES & SCRIPTS -->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <style>
        :root {
            --swiper-theme-color: white;
        }

        .brands-carousel .flickity-page-dots {
            bottom: -30px;
        }

        .brands-carousel .flickity-page-dots .dot {
            width: 8px;
            height: 8px;
            opacity: 0.4;
            background: #4B5563;
            transition: all 0.3s ease;
        }

        .brands-carousel .flickity-page-dots .dot.is-selected {
            width: 24px;
            border-radius: 6px;
            opacity: 1;
            background: #000000;
        }

        .brands-carousel .flickity-prev-next-button {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 50%;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brands-carousel .flickity-prev-next-button:hover {
            background: #000000;
            border-color: #000000;
            transform: translateY(-50%) scale(1.08);
        }

        .brands-carousel .flickity-prev-next-button .flickity-button-icon {
            fill: #374151;
            width: 38%;
            height: 38%;
            left: 31%;
            top: 31%;
        }

        .brands-carousel .flickity-prev-next-button:hover .flickity-button-icon {
            fill: #FFFFFF;
        }

        .brands-carousel .flickity-prev-next-button.previous {
            left: -20px;
        }

        .brands-carousel .flickity-prev-next-button.next {
            right: -20px;
        }


        @media (max-width: 640px) {
            .brands-carousel .flickity-prev-next-button.previous {
                left: -8px;
            }

            .brands-carousel .flickized-prev-next-button.next {
                right: -8px;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Hero Swiper Initialization
            new Swiper(".heroSwiper", {
                loop: true,
                effect: "fade",
                speed: 1000,
                autoplay: {delay: 3000, disableOnInteraction: false},
                pagination: {el: ".swiper-pagination", clickable: true},
                navigation: {nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev"},
            });

            // 2. Brands Flickity Carousel Initialization
            var brandCarousel = document.querySelector('.brands-carousel');
            if (brandCarousel) {
                // Check if screen is mobile (less than 768px)
                var isMobile = window.innerWidth < 768;

                new Flickity(brandCarousel, {
                    cellAlign: 'left',
                    contain: true,
                    // Mobile par dots/arrows off kar sakte hain agar clean look chahiye
                    pageDots: !isMobile,
                    prevNextButtons: !isMobile,
                    autoPlay: 2500,
                    wrapAround: true,
                    pauseAutoPlayOnHover: true
                });
            }
        });

    </script>

@endsection
