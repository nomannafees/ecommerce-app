@forelse($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
    @endphp

    <!-- CARD -->
    <a href="{{ route('product.detail', $product->slug) }}" class="group flex">
        <div class="bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full w-full">

            <!-- IMAGE -->
            <div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">
                <!-- WISHLIST -->
                <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                            class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10"
                            style="padding: 4px 9px 4px 9px !important;">
                        <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                    </button>
                </form>

                <img src="{{ $product->mainVariantImage && $product->mainVariantImage->image_path ? asset('storage/'. $product->mainVariantImage->image_path) : asset('images/no-image.png') }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                     alt="{{ $product->name }}">
            </div>

            <!-- CONTENT -->
            <div class="p-1.5 sm:p-2.5 xs:p-2.5 md-p-2.5 lg-p-2.5 xl-p-2.5 2xl-p-2.5 flex-grow flex flex-col justify-between gap-2">
                <div>
                    <!-- NAME -->
                    <h4 class="font-medium text-[12px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                        {{ $product->name }}
                    </h4>

                    <!-- DESCRIPTION -->
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5 mb-0 line-clamp-1 overflow-hidden leading-relaxed">
                        {!! Str::limit(strip_tags($product->description), 150) !!}
                    </p>
                </div>

                @php $avgRating = $product->avgRating ?? 0; @endphp

                {{-- Rating Section --}}
                <div class="flex items-center gap-1 -mt-1">
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
                <div class="flex items-center justify-between -mt-1 gap-2">
                @php $variant = $product->mainVariant ?? $product->variants->first(); @endphp

                <!-- PRICE -->
                    <div class="flex flex-col">
                        @if($variant)
                            <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
                                Rs {{ number_format($variant->price) }}
                            </span>
                            @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                    Rs {{ number_format($variant->cut_price) }}
                                </span>
                            @endif
                        @else
                            <span class="text-xs sm:text-base font-bold text-gray-500 whitespace-nowrap">
                                Rs {{ number_format($product->base_price ?? 0) }}
                            </span>
                        @endif
                    </div>

                    <!-- STOCK STATUS -->
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
        </div>
    </a>
@empty
    <div class="col-span-full text-center py-16">
        <i class="fa-solid fa-box-open text-5xl sm:text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl sm:text-2xl font-semibold text-gray-600">No Products Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-2">Products will appear here once they are added.</p>
    </div>
@endforelse
