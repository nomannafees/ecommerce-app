@extends('frontend.layouts.app')

@section('content')

<div class="px-12 py-10">

    <!-- TITLE -->
    <div class="text-center mb-8">

        <h2 class="text-4xl font-bold text-gray-900">
            Shop Products
        </h2>

        <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
            Choose your favorite products and add them to your cart.
        </p>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($products as $product)

        @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        @endphp

        <!-- CARD -->
        <a href="{{ route('product.detail', $product->slug) }}" class="group">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300">

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

                    <img
                        src="{{ $product->mainVariantImage->image_path ? asset('storage/'. $product->mainVariantImage->image_path) : asset('images/no-image.png') }}"
                        class="w-full h-70 object-cover group-hover:scale-105 transition duration-300"
                        alt="{{ $product->name }}">

                </div>

                <!-- CONTENT -->
                <div class="p-4">

                    <!-- NAME -->
                    <h4 class="font-medium text-gray-800 truncate group-hover:text-black">
                        {{ $product->name }}
                    </h4>

                    <!-- DESCRIPTION -->
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2 mb-0">
                        {!! Str::limit($product->description, 80) !!}
                    </p>

                    <!-- PRICE + STOCK -->
                    <div class="flex items-center justify-between mt-3 gap-2">

                    @php
                        // Direct link check: Agar mainVariant hai to thik, nahi to fallback ke liye pehla variant
                        $variant = $product->mainVariant ?? $product->variants->first();
                    @endphp

                    <!-- PRICE (Left Side) -->
                        <div class="flex items-baseline gap-2">
                            @if($variant)
                                <span class="text-base font-bold text-lg text-green-600 whitespace-nowrap">
                                        Rs {{ number_format($variant->price) }}
                                    </span>

                                @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                    <span class="text-xs text-gray-400 line-through whitespace-nowrap">
                                            Rs {{ number_format($variant->cut_price) }}
                                        </span>
                                @endif
                            @else
                                <span class="text-base font-bold text-gray-500 whitespace-nowrap">
                                        Rs {{ number_format($product->base_price ?? 0) }}
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

        @empty

        <div class="col-span-full text-center py-16">

            <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>

            <h3 class="text-2xl font-semibold text-gray-600">
                No Products Found
            </h3>

            <p class="text-gray-500 mt-2">
                Products will appear here once they are added.
            </p>

        </div>

        @endforelse

    </div>

</div>

@endsection
