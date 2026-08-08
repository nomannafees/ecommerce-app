@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER -->
    <div class="md:max-w-7xl lg:max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-6 sm:pb-10 sm:pt-6">

        <!-- FLEX LAYOUT -->
        <div class="flex flex-col gap-6">

            <!-- RIGHT SIDE / MAIN CONTENT -->
            <div class="flex-1">

                <!-- Dynamic Category Banner & Heading -->
                @if(isset($currentCategory))
                    <div class="relative w-full bg-cover bg-center rounded-xl p-8 mb-6 text-white shadow-md overflow-hidden"
                         style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ $currentCategory->image ? asset('storage/'.$currentCategory->image) : asset('default-banner.jpg') }}')">
                        <div class="relative z-10">
                            <h1 class="text-2xl sm:text-4xl font-bold mb-2">{{ $currentCategory->name }}</h1>
                            <p class="text-xs sm:text-sm text-gray-200">Explore our exclusive items in {{ $currentCategory->name }}.</p>
                        </div>
                    </div>

                    <!-- SUB-CATEGORIES SECTION -->
                    @if($currentCategory && $currentCategory->children->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight flex items-center gap-2">
                                    <span class="w-1.5 h-4.5 bg-emerald-600 rounded-full inline-block"></span>
                                    Explore Sub-Categories
                                </h3>
                                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                {{ $currentCategory->children->count() }} Items
            </span>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-3 sm:gap-4">
                                @foreach($currentCategory->children as $subCat)
                                    @php
                                        // CHANGE: Agar pehle se request mein category mojood hai, toh uske sath naya sub-cat slug append karein
                                        $currentRequestCategory = request('category');
                                        $nestedSlug = $currentRequestCategory ? $currentRequestCategory . '/' . $subCat->slug : $subCat->slug;
                                    @endphp
                                    <a href="{{ route('categories', array_merge(request()->except('page'), ['category' => $nestedSlug])) }}"
                                       class="group bg-white border border-gray-200/80 rounded-xl overflow-hidden shadow-xs hover:shadow-md  transition-all duration-300 flex flex-col text-center relative">

                                        <div class="w-full h-24 sm:h-30 bg-gray-50 overflow-hidden relative">
                                            <img src="{{ $subCat->image ? asset('storage/cat_image/' . $subCat->image) : asset('images/no-image.png') }}"
                                                 alt="{{ $subCat->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                                        </div>

                                        <div class="p-2.5 flex items-center justify-center bg-white border-t border-gray-100/60">
                                            <h4 class="font-medium text-xs sm:text-sm text-gray-800 group-hover:text-emerald-600 transition-colors duration-200 line-clamp-1">
                                                {{ $subCat->name }}
                                            </h4>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                @endif

            @else
                <!-- Fixed missing quote issue here -->
                    <div class="text-center mb-6 lg:mb-0">
                        <h2 class="text-2xl sm:text-4xl font-bold text-gray-900">
                            All Products
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-2 max-w-2xl mx-auto">
                            Choose your favorite products and add them to your cart.
                        </p>
                    </div>
            @endif


            <!-- PRODUCTS GRID SECTION -->
                <div class="mb-6">
                    <!-- Header with proper flex alignment and green bar indicator -->
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-5 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
                        <span>{{ isset($currentCategory) ? 'Products in ' . $currentCategory->name : 'All Products' }}</span>
                    </h3>

                    <!-- Grid configured for 5 columns on lg/xl screens -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                        @forelse($products as $product)
                            @php
                                $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                                $avgRating = $product->avgRating ?? 0;
                            @endphp

                            <div class="group relative bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full w-full">

                                <!-- WISHLIST BUTTON -->
                                <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm absolute top-2 right-2 z-20">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit"
                                            class="wishlistBtn bg-white/90 hover:bg-white p-1.5 rounded-full shadow-sm transition" style="padding: 3px 9px 4px 9px">
                                        <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                                    </button>
                                </form>

                                <!-- PRODUCT LINK WRAPPER -->
                                <a href="{{ route('product.detail', $product->slug) }}" class="flex flex-col h-full w-full">

                                    <!-- IMAGE CONTAINER -->
                                    <div class="relative bg-gray-100 overflow-hidden h-44 sm:h-52 w-full">
                                        <img src="{{ $product->mainVariantImage && $product->mainVariantImage->image_path ? asset('storage/'. $product->mainVariantImage->image_path) : asset('images/no-image.png') }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             alt="{{ $product->name }}">
                                    </div>

                                    <!-- CONTENT -->
                                    <div class="p-2.5 sm:p-3 flex-grow flex flex-col justify-between gap-1.5">
                                        <div>
                                            <h4 class="font-medium text-xs sm:text-sm text-gray-800 line-clamp-1 group-hover:text-black capitalize">
                                                {{ $product->name }}
                                            </h4>
                                            <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">
                                                {!! Str::limit(strip_tags($product->description), 100) !!}
                                            </p>
                                        </div>

                                        <!-- RATING SECTION -->
                                        <div class="flex items-center gap-1">
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

                                        <!-- PRICE + STOCK -->
                                        <div class="flex items-center justify-between mt-auto gap-1">
                                            @php
                                                $variant = $product->mainVariant ?? $product->variants->first();
                                            @endphp

                                            <div class="flex flex-col">
                                                @if($variant)
                                                    <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
                                                        Rs {{ number_format($variant->price) }}
                                                    </span>
                                                    @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                                        <span class="text-[10px] text-gray-400 line-through whitespace-nowrap">
                                                            Rs {{ number_format($variant->cut_price) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-xs sm:text-base font-bold text-gray-500 whitespace-nowrap">
                                                        Rs {{ number_format($product->base_price ?? 0) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex-shrink-0">
                                                @php $totalStock = $product->variants->sum('stock'); @endphp
                                                @if($totalStock <= 0)
                                                    <span class="inline-block bg-red-100 text-red-600 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
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

                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-16 bg-white rounded-lg border border-gray-100">
                                <i class="fa-solid fa-box-open text-5xl text-gray-300 mb-3"></i>
                                <h4 class="text-lg font-semibold text-gray-600">No Products Found</h4>
                                <p class="text-xs text-gray-500 mt-1">There are no products available under this category.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
