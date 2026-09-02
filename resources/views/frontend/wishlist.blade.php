@extends('frontend.layouts.app')

@section('content')

    <div class="px-4 md:px-10 pb-10 pt-6 min-h-[49vh]">

        <!-- HEADER -->
        <div class="hidden md:block mb-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800">My Wishlist</h2>

            <p class="text-sm text-gray-500 mt-1">
                Save your favorite products and purchase them later anytime
            </p>
        </div>


        <!-- EMPTY STATE -->
        <div id="emptyWishlist" class=" {{ $wishlists->isEmpty() ? '' : 'hidden' }} empty-wishlist flex items-center justify-center px-4 py-8">
            <div
                 class="text-center  ">

                <i class="fa-regular fa-heart text-5xl text-gray-300"></i>

                <h2 class="text-xl font-semibold mt-4 text-gray-700">
                    Your wishlist is empty
                </h2>

                <p class="text-sm text-gray-500">
                    Start adding products you love ❤️
                </p>

            </div>
        </div>

        <style>
            .empty-wishlist {
                height: 37.5vh;
            }

            @media (max-width: 767px) {
                .empty-wishlist {
                    height: 80vh;
                }
            }
        </style>



        <!-- WISHLIST ITEMS -->
        <div id="wishlistContainer"
             class="space-y-4 container mx-auto {{ $wishlists->isEmpty() ? 'hidden' : '' }}">

        @foreach($wishlists as $item)

            @php
                $product = $item->product;
            @endphp

            @if($product)

                <!-- CARD -->
                    <div class="wishlist-item last:mb-9 md:last:mb-4 flex flex-col md:flex-row items-start md:items-center justify-between bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 gap-4 md:gap-0">

                        <!-- LEFT SIDE -->
                        <div class="flex items-start md:items-center gap-5 w-full md:w-2/3">

                            <!-- IMAGE -->
                            <a href="{{ route('product.detail', $product->slug) }}"
                               class="flex-shrink-0">

                                <img src="{{ asset('storage/'.$product->mainVariantImage->image_path) }}"
                                     class="w-20 h-20 object-cover rounded-lg hover:scale-105 transition">

                            </a>


                            <!-- INFO -->
                            <div class="flex-1 min-w-0">

                                <a href="{{ route('product.detail', $product->slug) }}">
                                    <h3 class="font-semibold text-gray-900 text-base hover:text-green-600 transition truncate">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <p class="text-sm text-gray-500 mt-1 md:mt-2 line-clamp-2">
                                    {{ Str::limit(strip_tags($product->description), 100) }}
                                </p>


                                <!-- PRICE -->
                                <p class="text-green-600 font-bold mt-2">

                                    @php
                                        $variantPrice = $product->variants->first()->price
                                            ?? ($product->base_price ?? 0);
                                    @endphp

                                    Rs {{ number_format($variantPrice) }}

                                </p>

                            </div>

                        </div>


                        <!-- RIGHT SIDE -->
                        <div class="flex items-center justify-end w-full md:w-auto gap-3 mt-3 md:mt-0 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">

                            <!-- View Product -->
                            <a href="{{ route('product.detail', $product->slug) }}"
                               class="flex-1 md:flex-none flex items-center justify-center gap-2 w-10 h-10 bg-green-50 text-green-600 border border-green-100 rounded-xl hover:bg-green-100 transition-all duration-300 shadow-xs cursor-pointer">

                                <i class="fa-solid fa-eye"></i>

                            </a>


                            <!-- Delete -->
                            <button type="button"
                                    data-id="{{ $item->id }}"
                                    class="deleteWishlist w-10 h-10 flex-shrink-0 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-300 shadow-xs cursor-pointer"
                                    title="Remove from wishlist">

                                <i class="fa-solid fa-times text-sm"></i>

                            </button>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

@endsection
