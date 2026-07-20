@extends('frontend.layouts.app')

@section('content')

<div class="px-4 md:px-10 py-10 bg-gray-50 ">

    <!-- HEADER -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-800">My Wishlist</h2>
        <p class="text-sm text-gray-500 mt-1">
            Save your favorite products and purchase them later anytime
        </p>
    </div>

    @if($wishlists->isEmpty())

    <!-- EMPTY STATE -->
    <div class="text-center py-10 bg-white rounded-xl shadow-sm">
        <i class="fa-regular fa-heart text-5xl text-gray-300"></i>
        <h2 class="text-xl font-semibold mt-4 text-gray-700">Your wishlist is empty</h2>
        <p class="text-sm text-gray-500 mt-1">Start adding products you love ❤️</p>
    </div>

    @else

    <div class="space-y-4">

        @foreach($wishlists as $item)

        @php
        $product = $item->product;
        @endphp

        @if($product)

        <!-- CARD -->
        <div class="wishlist-item flex flex-col md:flex-row items-center justify-between bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <!-- LEFT SIDE -->
            <div class="flex items-center gap-5 w-full md:w-2/3">

                <!-- IMAGE -->
                <a href="{{ route('product.detail', $product->slug) }}">
                    <img src="{{ asset('storage/'.$product->mainVariantImage->image_path) }}"
                        class="w-20 h-20 object-cover rounded-lg hover:scale-105 transition">
                </a>

                <!-- INFO -->
                <div>

                    <h3 class="font-semibold text-gray-900 text-base">
                        {{ $product->name }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                        {{ Str::limit(strip_tags($product->description), 100) }}
                    </p>

                    <!-- PRICE -->
                    <p class="text-green-600 font-bold mt-2">
                        @php
                            // Product ke kisi bhi pehle variant ki price uthao, agar table me na ho to base_price ko use karo
                            $variantPrice = $product->variants->first()->price ?? ($product->base_price ?? 0);
                        @endphp
                        Rs {{ number_format($variantPrice) }}
                    </p>

                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-3 mt-4 md:mt-0">

                <!-- View Product -->
                <a href="{{ route('product.detail', $product->slug) }}"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-sm">

                    <i class="fa-solid fa-eye"></i>
                    <span>View Product</span>
                </a>

                <!-- Delete -->
                <button type="button"
                        data-id="{{ $item->id }}"
                        class="deleteWishlist w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100   transition-all duration-300 shadow-xs cursor-pointer"
                        title="Remove from wishlist">

                    <i class="fa-solid fa-times text-sm"></i>
                </button>

            </div>

        </div>

        @endif

        @endforeach

    </div>

    @endif

</div>

@endsection
