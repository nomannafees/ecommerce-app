@forelse($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $variant = $product->mainVariant ?? $product->variants->first();
        $discountPercent = $product->flashSale->discount_percentage ?? 0;

        $originalPrice = $variant->cut_price ?? $variant->price ?? 0;
        if (request()->segment(3) == 'flash-sale' && $discountPercent > 0 && !empty($variant->cut_price)) {
            $sellingPrice = $originalPrice - ($originalPrice * ($discountPercent / 100));
        } else {
            $sellingPrice = $variant->price ?? 0;
        }
    @endphp

    <a href="{{ route('product.detail', $product->slug) }}" class="group">
        <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

            @if(request()->segment(3) == 'flash-sale' && $product->flashSale)
                <div class="absolute top-2 -left-1 z-20 bg-gradient-to-r from-orange-600 to-amber-500 text-white pl-3 pr-3.5 py-1 rounded-r-full text-[10px] sm:text-[11px] font-extrabold shadow-md flex items-center gap-1 tracking-wide">
                    <i class="fa-solid fa-bolt text-yellow-200 text-[10px]"></i>
                    <span>{{ number_format($discountPercent, 0) }}% OFF</span>
                </div>
            @elseif(request()->segment(3) == 'bestselling')
                <span class="absolute top-2 left-2 z-10 bg-rose-500 text-white text-[9px] sm:text-[10px] font-bold uppercase px-2 py-1 rounded-md shadow-md">
        Top Seller
    </span>
            @endif

            <div class="relative bg-gray-100 overflow-hidden h-50 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">
                <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm" onclick="event.preventDefault();">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10 hover:bg-gray-50 transition" style="padding: 4px 9px 4px 9px !important;">
                        <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-600' }}"></i>
                    </button>
                </form>

                @if($product->mainVariantImage)
                    <img src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300">
                @else
                    <img src="{{ asset('upload/no-image.jpg') }}" alt="No Image" class="w-full h-full object-cover">
                @endif
            </div>

            <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2">
                <div>
                    <h4 class="font-medium xs:text-[14px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                        {{ $product->name }}
                    </h4>
                    <div class="text-[11px] sm:text-xs text-gray-600 line-clamp-1 mt-0.5">
                        {!! $product->description !!}
                    </div>
                    <div class="flex items-center gap-1 sm:mt-1.5">
                        <div class="flex text-yellow-500 text-[10px] sm:text-xs gap-0.5">
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
                        <span class="text-[10px] sm:text-xs text-gray-700 font-semibold">({{ number_format($avgRating, 1) }})</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 mt-auto">
                    <div class="flex flex-col">
                        <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                            Rs {{ number_format($sellingPrice) }}
                        </span>
                        @if(!empty($variant->cut_price) && $variant->cut_price > $sellingPrice)
                            <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                Rs {{ number_format($variant->cut_price) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        @php $totalStock = $product->variants->sum('stock'); @endphp
                        @if($totalStock <= 0)
                            <span class="inline-block bg-red-100 text-red-700 text-[9px] sm:text-[11px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">Out of Stock</span>
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
@endforelse