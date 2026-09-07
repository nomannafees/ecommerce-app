@foreach($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews_count ?? 0;
    @endphp

    <a href="{{ route('product.detail', $product->slug) }}" class="group flex">
        <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-300 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

            {{-- IMAGE CONTAINER --}}
            <div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-47 lg:h-50">
                <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                            class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10"
                            style="padding: 4px 9px 4px 9px !important;
                            cursor: pointer;">
                        <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                    </button>
                </form>
                @if($product->mainVariantImage)
                    <img class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300"
                         src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}"
                         alt="{{ $product->name }}">
                @else
                    <img class="w-full h-full object-cover" src="{{ asset('upload/no-image.jpg') }}" alt="No Image Available">
                @endif
            </div>

            {{-- CARD CONTENT --}}
            <div class="p-1.5 sm:p-2.5 xs:p-2.5 md-p-2.5 lg-p-2.5 xl-p-2.5 2xl-p-2.5 flex-grow flex flex-col justify-between gap-2">
                <div>
                    <h4 class="font-medium text-[12px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                        {{ $product->name }}
                    </h4>
                    <div class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 sm:line-clamp-1 mt-0.5">
                        {!! $product->description !!}
                    </div>


                    {{-- Rating & Sold Items Section --}}
                    <div class="flex items-center justify-between gap-1 mt-0.5 sm:mt-1.5">
                        {{-- Rating & Total Reviews Count --}}
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
                            <span class="text-[10px] sm:text-xs text-gray-700 font-semibold">
                                ({{ number_format($avgRating, 1) }}) <span class="text-gray-400 font-normal">({{ $reviewsCount }})</span>
                            </span>
                        </div>

                        {{-- Sold Count --}}
                        <span class="text-[10px] sm:text-xs text-gray-500 font-medium whitespace-nowrap">
                            {{ $product->order_items_count ?? 0 }} Sold
                        </span>
                    </div>
                </div>

                {{-- Price & Stock Section with Discount Percentage --}}
                <div class="flex items-center justify-between gap-2 -mt-2">
                    @php
                        $variant = $product->mainVariant ?? $product->variants->first();

                        $salePrice = $variant->price ?? 0;
                        $originalPrice = $variant->cut_price ?? 0;

                        $discountPercent = 0;
                        if ($originalPrice > 0 && $originalPrice > $salePrice) {
                            $discountPercent = round(
                                (($originalPrice - $salePrice) / $originalPrice) * 100
                            );
                        }
                    @endphp

                    {{-- PRICE --}}
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                            Rs {{ number_format($salePrice) }}
                        </span>

                        @if($discountPercent > 0)
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                    Rs {{ number_format($originalPrice) }}
                                </span>
                                <span class="text-[12px] sm:text-[14px] font-medium text-gray-600 whitespace-nowrap">
                                    -{{ $discountPercent }}%
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- STOCK --}}
                    <div class="flex-shrink-0">
                        @php
                            $totalStock = $product->variants->sum('stock');
                        @endphp

                        @if($totalStock <= 0)
                            <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-gradient-to-br from-rose-300 to-red-600 shadow-sm shadow-red-500/50 animate-pulse"></span>
                                Out of Stock
                            </span>
                        @elseif($totalStock < 30)
                            <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-gradient-to-br from-rose-300 to-rose-600 shadow-sm shadow-rose-500/50 animate-pulse"></span>
                                Only 3 left
                            </span>
                        @elseif($totalStock < 40)
                            <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-gradient-to-br from-amber-300 to-orange-500 shadow-sm shadow-orange-500/50 animate-pulse"></span>
                                Only 5 left
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-gradient-to-br from-emerald-300 to-emerald-600 shadow-sm shadow-emerald-500/50 animate-pulse"></span>
                                In Stock
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach