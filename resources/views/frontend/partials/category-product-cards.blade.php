@foreach($records as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $totalStock = $product->variants->sum('stock');

        $variant = $product->active_variant;
        $imagePath = $product->custom_image_path;
    @endphp

    <div class="relative bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 group flex flex-col h-full w-full">

        <form action="{{ route('wishlists.store') }}" method="POST"
              class="wishlistForm absolute top-2 right-2 sm:top-2 sm:right-2 z-30">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit"
                    class="wishlistBtn bg-white/90 hover:bg-white rounded-full shadow-sm transition flex items-center justify-center"
                    style="padding: 7px 7px 6px 7px !important;">
                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
            </button>
        </form>

        @php
            $selectedColorForLink = is_array(request('color')) ? (request('color')[0] ?? null) : request('color');
        @endphp
        <a href="{{ route('product.detail', $product->slug) }}{{ $selectedColorForLink ? '?color='.$selectedColorForLink : '' }}"
           class="block z-10 flex flex-col h-full">
            <div class="bg-gray-100 overflow-hidden relative h-46 xs:h-44 sm:h-50 2xl:h-47 md:h-45 lg:h-55">
                @if(!empty($imagePath))
                    <img src="{{ asset('storage/'.$imagePath) }}"
                         class="w-full h-full object-cover group-hover:scale-104 transition duration-300"
                         alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs sm:text-base">
                        No Image
                    </div>
                @endif
            </div>
            <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2">
                <div>
                    <h4 class="font-medium xs:text-[14px] md:text-[16px] text-gray-800 truncate group-hover:text-black capitalize">
                        {{ $product->name }}
                    </h4>
                    <div class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 mt-0.5">
                        {!! Str::limit(strip_tags($product->description), 150) !!}
                    </div>
                </div>
                @php
                    $avgRating = $product->avgRating ?? 0;
                @endphp
                <div class="flex items-center gap-1 sm:mt-1.5">
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

                <div class="flex items-center justify-between gap-2 mt-auto">
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
                            <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
                                Rs {{ number_format($product->base_price ?? 0) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-shrink-0">
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
        </a>
    </div>
@endforeach
