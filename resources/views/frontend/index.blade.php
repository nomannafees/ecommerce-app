@extends('frontend.layouts.app')
@section('content')

    <!-- HERO -->
    <div class="swiper heroSwiper w-full h-[500px]">

        <div class="swiper-wrapper">
            @forelse($sliders as $slider)
                <div class="swiper-slide relative">
                    <img src="{{ asset('storage/' . $slider->image) }}"
                         class="w-full h-[500px] object-cover">

                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <div class="text-center text-white px-6">

                            <h1 class="text-5xl md:text-6xl font-bold mb-4">
                                {{ $slider->heading }}
                            </h1>

                            <p class="text-lg md:text-xl max-w-2xl mx-auto mb-6">
                                {{ $slider->description }}
                            </p>

                            <a href="{{ route('frontendProduct') }}"
                               class="inline-block bg-white text-black px-8 py-3 rounded-lg font-semibold hover:bg-gray-200 transition">
                                Shop Now
                            </a>

                        </div>
                    </div>

                </div>

            @empty

                <div class="swiper-slide">
                    <h2 class="text-white text-center">No Slider Found</h2>
                </div>

            @endforelse

        </div>

        <!-- Navigation -->
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

    </div>

    <!-- TITLE -->
    <div class="flex px-12 mt-8 mb-6">
        <h2 class="text-2xl font-bold">Related Products</h2>
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 px-12 gap-6">

    @foreach($records as $product)

        @php
            $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        @endphp

        <!-- CARD -->
            <a href="{{ route('product.detail', $product->slug) }}" class="group">

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300">

                    <!-- IMAGE -->
                    <div class="relative bg-gray-100 overflow-hidden">

                        <!-- WISHLIST -->
                        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <button type="submit"
                                    class="wishlistBtn absolute top-2 right-2 bg-white px-2 py-1 rounded-full shadow z-10">

                                <i class="wishlistIcon fa-heart transition duration-200
                                {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}">
                                </i>

                            </button>
                        </form>

                        @if($product->mainVariantImage)
                            <img class="h-70 object-fit-cover w-full object-cover"  src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}" alt="{{ $product->name }}">
                        @else
                            {{-- Fallback image agar kisi product ki main variant image set na ho --}}
                            <img class="h-70 w-full object-contain" src="{{ asset('upload/no-image.jpg') }}" alt="No Image Available">
                        @endif

                    </div>

                    <!-- CONTENT -->
                    <div class="p-4">

                        <!-- NAME -->
                        <h4 class="font-medium text-gray-800 truncate group-hover:text-black capitalize">
                            {{ $product->name }}
                        </h4>

                        <!-- DESCRIPTION -->
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2 mb-0">
                            {!! str($product->description)->limit(65) !!}
                        </p>


                        <!-- PRICE + STOCK -->
                        <div class="flex items-center justify-between mt-3 gap-2">

                        @php
                            // Direct link check: Agar mainVariant hai to thik, nahi to fallback ke liye pehla variant
                            $variant = $product->mainVariant ?? $product->variants->first();
                        @endphp

                        <!-- PRICE (Left Side) -->
                            <div class="flex items-baseline gap-2">

                                    <span class="text-base text-lg font-bold text-green-600 whitespace-nowrap">
                                        Rs {{ number_format($variant->price) }}
                                    </span>

                                    @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                        <span class="text-xs text-gray-400 line-through whitespace-nowrap">
                                            Rs {{ number_format($variant->cut_price) }}
                                        </span>
                                    @endif

                            </div>

                            <!-- STOCK STATUS (Right Side) -->
                            <div class="flex-shrink-0">
                                @php $totalStock = $product->variants->sum('stock'); @endphp
                                @if($totalStock <= 0)
                                    <span class="inline-block bg-red-100 text-red-600 text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                Out of Stock
            </span>
                                @else
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                <span class="text-emerald-800 font-bold text-xs">{{ $totalStock }}</span> In Stock
            </span>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>

            </a>

            @if($loop->iteration == 8)

                <div class="col-span-1 sm:col-span-2 lg:col-span-4 my-10">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[500px]">

                        <!-- Left Big Banner -->
                        <div class="relative rounded-2xl overflow-hidden">
                            <img src="{{ asset('storage/banner/1721825245.png') }}"
                                 class="w-full h-full object-cover">

                            <div class="absolute inset-0 bg-black/40 flex items-end p-8">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Summer Collection</h2>
                                    <p class="text-white mt-2">Up to 50% OFF</p>

                                    <a href="{{ route('frontendProduct') }}"
                                       class="inline-block mt-4 bg-white text-black px-6 py-3 rounded-lg font-semibold">
                                        Shop Now
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="grid grid-rows-2 gap-6 h-[500px]">

                            <!-- Top Banner -->
                            <div class="relative rounded-2xl overflow-hidden h-full">
                                <img src="{{ asset('storage/banner/1721825256.png') }}"
                                     class="w-full h-full object-cover object-center">

                                <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                                    <h3 class="text-2xl font-bold text-white">New Arrival</h3>
                                </div>
                            </div>

                            <!-- Bottom Banner -->
                            <div class="relative rounded-2xl overflow-hidden h-full">
                                <img src="{{ asset('storage/banner/1721825269.png') }}"
                                     class="w-full h-full object-cover object-center">

                                <div class="absolute inset-0 bg-black/40 flex items-end p-6">
                                    <h3 class="text-2xl font-bold text-white">Exclusive Deals</h3>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            @endif
        @endforeach

    </div> <!-- GRID END -->


    <!-- 👇 BRANDS SECTION ADDED HERE -->
    <div class="px-12 mt-16 mb-12">

        <!-- Section Title -->
        <div class="flex mb-6">
            <h2 class="text-2xl font-bold">Our Top Brands</h2>
        </div>

        <!-- Home Page (index.blade.php) me Brands ka loop -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @forelse($brands as $brand)
            <!-- 👇 Route categories par hi bheja aur brand_id query string me pass kar di -->
                <a href="{{ route('categories', ['brand' => $brand->slug]) }}"
                   class="bg-white rounded-xl p-4 border border-gray-200 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition duration-300 group">

                    <!-- Brand Image -->
                    <div class="w-20 h-20 flex items-center justify-center mb-3">
                        <img src="{{ asset('storage/' . $brand->image) }}"
                             alt="{{ $brand->name }}"
                             class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition duration-300">
                    </div>

                    <!-- Brand Name -->
                    <span class="text-sm font-semibold text-gray-700 group-hover:text-black">
                {{ $brand->name }}
            </span>

                </a>
            @empty
                <div class="col-span-full text-center text-gray-500 py-4">
                    No Brands Found
                </div>
            @endforelse
        </div>

    </div>
    <!-- 👆 BRANDS SECTION END -->


    <style>
        :root {
            --swiper-theme-color: white;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            new Swiper(".heroSwiper", {
                loop: true,
                effect: "fade",
                speed: 1000,

                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },

                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },

                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });

        });
    </script>

@endsection
