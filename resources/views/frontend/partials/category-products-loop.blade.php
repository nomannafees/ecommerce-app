@forelse($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews_count ?? $product->reviews->count();

        $variant = $product->mainVariant ?? $product->variants->first();
        $salePrice = $variant->price ?? ($product->base_price ?? 0);
        $originalPrice = $variant->cut_price ?? 0;

        $calculatedDiscount = 0;
        if ($originalPrice > 0 && $originalPrice > $salePrice) {
            $calculatedDiscount = round((($originalPrice - $salePrice) / $originalPrice) * 100);
        }
    @endphp

    <div class="group relative bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full w-full">
        <!-- WISHLIST BUTTON -->
        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm absolute top-2 right-2 z-20">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="wishlistBtn bg-white/90 hover:bg-white rounded-full shadow-sm transition"
                    style="padding: 4px 9px 4px 9px !important; cursor: pointer;">
                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
            </button>
        </form>

        <a href="{{ route('product.detail', $product->slug) }}" class="flex flex-col h-full w-full">
            <div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">
                <img src="{{ $product->mainVariantImage && $product->mainVariantImage->image_path ? asset('storage/'. $product->mainVariantImage->image_path) : asset('images/no-image.png') }}"
                     class="w-full h-full object-cover group-hover:scale-104 transition duration-300"
                     alt="{{ $product->name }}">
            </div>

            <div class="p-1.5 sm:p-2.5 xs:p-2.5 md-p-2.5 lg-p-2.5 xl-p-2.5 2xl-p-2.5 flex-grow flex flex-col justify-between gap-2">
                <div>
                    <h4 class="font-medium text-[12px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                        {{ $product->name }}
                    </h4>
                    <div class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 mt-0.5">
                        {!! $product->description !!}
                    </div>
                </div>

                {{-- Rating, Reviews Count & Sold Count Section --}}
                <div class="flex items-center justify-between gap-1 -mt-1">
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

                {{-- Price & Stock Section --}}
                <div class="flex items-center justify-between gap-2 -mt-1">
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                            Rs {{ number_format($salePrice) }}
                        </span>
                        @if($originalPrice > $salePrice && $calculatedDiscount > 0)
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                    Rs {{ number_format($originalPrice) }}
                                </span>
                                <span class="text-[12px] sm:text-[14px] font-medium text-gray-600 whitespace-nowrap">
                                    -{{ $calculatedDiscount }}%
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-shrink-0">
                        @php $totalStock = $product->variants->sum('stock'); @endphp
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
        </a>
    </div>
@empty
    <div class="col-span-full text-center py-16">
        <i class="fa-solid fa-box-open text-5xl sm:text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl sm:text-2xl font-semibold text-gray-600">No Products Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-2">Products will appear here once they are added.</p>
    </div>
@endforelse