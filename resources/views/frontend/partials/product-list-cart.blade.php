@forelse($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews->count(); // Total review dene wale logo ki tadad
        $variant = $product->mainVariant ?? $product->variants->first();
        $discountPercent = $product->flashSale->discount_percentage ?? 0;

        $originalPrice = $variant->cut_price ?? $variant->price ?? 0;

        // Flash sale check aur price calculation
        if (request()->segment(3) == 'flash-sale' && $discountPercent > 0 && !empty($variant->cut_price)) {
            $sellingPrice = $originalPrice - ($originalPrice * ($discountPercent / 100));
        } else {
            $sellingPrice = $variant->price ?? 0;
        }

        // Agar Flash sale nahi hai, toh regular cut_price aur price se discount calculate karo
        $regularCutPrice = $variant->cut_price ?? 0;
        $calculatedDiscount = 0;
        if (request()->segment(3) != 'flash-sale' && $regularCutPrice > 0 && $regularCutPrice > $sellingPrice) {
            $calculatedDiscount = round((($regularCutPrice - $sellingPrice) / $regularCutPrice) * 100);
        }
    @endphp

    <div class="relative group">

        {{-- Wishlist --}}
        <form action="{{ route('wishlists.store') }}"
              method="POST"
              class="wishlistForm absolute top-2 right-2 z-30">
            @csrf

            <input type="hidden"
                   name="product_id"
                   value="{{ $product->id }}">

            <button type="submit"
                    class="wishlistBtn bg-white rounded-full shadow hover:bg-gray-50 transition"
                    style="padding: 4px 9px !important;
                        cursor: pointer;">

                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200
                {{ $isWishlisted
                    ? 'fa-solid text-red-500'
                    : 'fa-regular text-gray-600' }}">
                </i>

            </button>
        </form>


        {{-- Product Link --}}
        <a href="{{ route('product.detail', $product->slug) }}"
           class="block h-full">

            <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

                {{-- Flash Sale / Bestselling --}}
                @if(request()->segment(3) == 'flash-sale' && $product->flashSale)

                    <div class="absolute top-2 -left-1 z-10 bg-gradient-to-r from-orange-600 to-amber-500 text-white pl-3 pr-3.5 py-1 rounded-r-full text-[10px] sm:text-[11px] font-extrabold shadow-md flex items-center gap-1 tracking-wide">
                        <i class="fa-solid fa-bolt text-yellow-200 text-[10px]"></i>
                        <span>{{ number_format($discountPercent, 0) }}% OFF</span>
                    </div>

                @elseif(request()->segment(3) == 'bestselling')

                    <span class="absolute top-2 left-2 z-10 bg-rose-500 text-white text-[9px] sm:text-[10px] font-bold uppercase px-2 py-1 rounded-md shadow-md">
                    Top Seller
                </span>

                @endif


                {{-- Image --}}
                <div class="relative bg-gray-100 overflow-hidden h-50 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50">

                    @if($product->mainVariantImage)

                        <img src="{{ asset('storage/' . $product->mainVariantImage->image_path) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300">

                    @else

                        <img src="{{ asset('upload/no-image.jpg') }}"
                             alt="No Image"
                             class="w-full h-full object-cover">

                    @endif

                </div>


                {{-- Content --}}
                <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2">

                    <div>

                        <h4 class="font-medium xs:text-[14px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                            {{ $product->name }}
                        </h4>

                        <div class="text-[11px] sm:text-xs text-gray-600 line-clamp-1 mt-0.5">
                            {!! $product->description !!}
                        </div>

                        {{-- Rating, Total Reviews Count & Sold Count Section --}}
                        <div class="flex items-center justify-between gap-1 mt-0.5 sm:mt-1.5">
                            <div class="flex items-center gap-1">
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


                    {{-- Price + Stock (With Blinking Dots Logic & Discount Percentage) --}}
                    <div class="flex items-center justify-between gap-2 -mt-1">

                        <div class="flex flex-col">

                            <span class="text-xs sm:text-base font-bold text-emerald-700 whitespace-nowrap">
                                Rs {{ number_format($sellingPrice) }}
                            </span>

                            {{-- Flash Sale ke liye purana style, baqi products ke liye naya calculated discount style --}}
                            @if(request()->segment(3) == 'flash-sale')
                                @if(!empty($variant->cut_price) && $variant->cut_price > $sellingPrice)
                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                        Rs {{ number_format($variant->cut_price) }}
                                    </span>
                                @endif
                            @else
                                @if($regularCutPrice > $sellingPrice && $calculatedDiscount > 0)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                            Rs {{ number_format($regularCutPrice) }}
                                        </span>
                                        <span class="text-[12px] sm:text-[14px] font-medium text-gray-600 whitespace-nowrap">
                                            -{{ $calculatedDiscount }}%
                                        </span>
                                    </div>
                                @endif
                            @endif

                        </div>


                        @php
                            $totalStock = $product->variants->sum('stock');
                        @endphp

                        <div class="flex-shrink-0">

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

    </div>

@empty
    <div class="col-span-full py-8 text-center text-gray-400 text-sm bg-white rounded-lg border border-gray-200">
        No products found.
    </div>
@endforelse