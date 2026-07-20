@extends('frontend.layouts.app')

@section('content')

    <div class="flex flex-col lg:flex-row gap-6 px-6 lg:px-10 py-10">

        <!-- ================= HEADER ================= -->
        <div class="w-full mb-6 lg:mb-0 lg:col-span-3">

            <div class="text-center mb-10">
                <h2 class="text-4xl font-bold text-gray-900">
                    {{ $category->name ?? 'All Products' }}
                </h2>

                <p class="text-gray-500 mt-3">
                    Products available in this category
                </p>
            </div>

            <!-- ================= PRODUCTS GRID ================= -->
            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @forelse($records as $product)

                    @php
                        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                        $totalStock = $product->variants->sum('stock');
                    @endphp

                    <a href="{{ route('product.detail', $product->slug) }}" class="group">

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300">

                            <!-- IMAGE -->
                            <div class="relative bg-gray-100 overflow-hidden">

                                <!-- WISHLIST -->
                                <form action="{{ route('wishlists.store') }}" method="POST"
                                      class="absolute top-2 right-2 z-10">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit"
                                            class="bg-white px-2 py-1 rounded-full shadow">

                                        <i class="fa-heart transition
                                        {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}">
                                        </i>

                                    </button>
                                </form>

                                <img src="{{ asset('upload/product/'.$product->main_image) }}"
                                     class="w-full h-64 object-cover group-hover:scale-105 transition duration-300"
                                     alt="{{ $product->name }}">

                            </div>

                            <!-- CONTENT -->
                            <div class="p-4">

                                <h4 class="font-medium text-gray-800 truncate group-hover:text-black">
                                    {{ $product->name }}
                                </h4>

                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                    {{ $product->description }}
                                </p>

                                <!-- PRICE + STOCK -->
                                <div class="flex justify-between items-center mt-3">

                                <span class="text-lg font-bold text-green-600">
                                    Rs {{ number_format($product->base_price) }}
                                </span>

                                    @if($totalStock <= 0)
                                        <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">
                                        Out of Stock
                                    </span>
                                    @else
                                        <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full">
                                        {{ $totalStock }} In Stock
                                    </span>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </a>

                @empty

                <!-- EMPTY -->
                    <div class="col-span-full text-center py-20">
                        <h3 class="text-xl font-semibold text-gray-600">No products found</h3>
                        <p class="text-gray-400 mt-2">This category has no products yet.</p>
                    </div>

                @endforelse

            </div>

        </div>

    </div>

@endsection
