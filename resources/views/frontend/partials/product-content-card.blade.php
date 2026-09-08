<div class="p-1.5 sm:p-2.5 xs:p-2.5 md-p-2.5 lg-p-2.5 xl-p-2.5 2xl-p-2.5 flex-grow flex flex-col justify-between gap-2 ">
    <div>
        {{-- Product Name --}}
        <h4 class="font-medium text-[12px] md:text-[16px] text-gray-800 truncate group-hover:text-black -mt-1 capitalize">
            {{ $product->name }}
        </h4>

        {{-- Description --}}
        <div class=" text-[11px] sm:text-xs text-gray-600 line-clamp-1 sm:line-clamp-1 ">
            {!! $product->description !!}
        </div>

        {{-- Rating & Sold Items Section --}}
        <div class="flex items-center justify-between gap-1 mt-0.5 sm:mt-1.5">
            {{-- Left Side: Rating & Total Reviews Count --}}
            <div class="flex items-center gap-1">
                <div class="flex text-yellow-500 text-[10px] sm:text-xs gap-0.5">

                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor(@$avgRating))
                                <i class="fa-solid fa-star"></i>
                            @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star text-gray-300"></i>
                            @endif
                        @endfor

                </div>
                <span class="text-[10px] sm:text-xs text-gray-700 font-semibold">
                        {{ number_format(@$avgRating, 1) }} <span class="text-gray-500 font-normal">({{ @$reviewsCount }})</span>
                    </span>
            </div>

            {{-- Right Side: Sold Items --}}
            <span class="text-[10px] sm:text-xs text-gray-500 font-medium whitespace-nowrap">
                    {{ $product->order_items_count ?? 0 }} Sold
                </span>
        </div>
    </div>

    {{-- Price & Stock Section (Fixed for Top Seller / Normal Products) --}}
    <div class="flex items-center justify-between gap-1.4 -mt-2">
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

        <div class="flex flex-col">
            {{-- Discounted / Main Sale Price --}}
            <span class="lg:text-xl text-base font-bold text-emerald-700 whitespace-nowrap">
                Rs {{ number_format($salePrice) }}
            </span>

            {{-- Original / Cut Price & Percentage --}}
            @if($originalPrice > $salePrice && $discountPercent > 0)
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                        Rs {{ number_format($originalPrice) }}
                    </span>
                    <span class="text-[11px] sm:text-[13px] font-medium text-red-700 whitespace-nowrap">
                        -{{ $discountPercent }}%
                    </span>
                </div>
            @endif
        </div>

        <div class="flex flex-col items-end flex-shrink-0 mt-1.3">
            @php
                $totalStock = $product->variants->sum('stock');
            @endphp

            @if($totalStock <= 0)
                {{-- Out of Stock (Red Ping Dot) --}}
                <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    Out of Stock
                </span>
            @elseif($totalStock < 30)
                {{-- Only 3 left (Rose/Red Ping Dot) --}}
                <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                    </span>
                    Only 3 left
                </span>
            @elseif($totalStock < 40)
                {{-- Only 5 left (Orange Ping Dot) --}}
                <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    Only 5 left
                </span>
            @else
                {{-- In Stock (Green Ping Dot) --}}
                <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    In Stock
                </span>
            @endif

            {{-- Free Delivery --}}
            <span class="inline-flex items-center gap-1 text-[10px] sm:text-xs text-gray-500 font-medium whitespace-nowrap mt-0.5">
                <i class="fa-solid fa-truck text-gray-500 text-[10px]"></i>
                Free Delivery
            </span>
        </div>
    </div>
</div>