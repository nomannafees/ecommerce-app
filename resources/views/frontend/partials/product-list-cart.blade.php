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

        // --- Yahan aapne images ka array wala code add kar diya hai ---
        $allImages = [];
        if ($product->mainVariantImage) {
            $allImages[] = asset('storage/' . $product->mainVariantImage->image_path);
        }
        foreach ($product->variantImages as $vImg) {
            $imgPath = asset('storage/' . $vImg->image_path);
            if (!in_array($imgPath, $allImages)) {
                $allImages[] = $imgPath;
            }
        }
        if (empty($allImages)) {
            $allImages[] = asset('upload/no-image.jpg');
        }
    @endphp

    <div class="relative group">

        {{-- Wishlist --}}
        <form action="{{ route('wishlists.store') }}"
              method="POST"
              class="wishlistForm absolute top-2 right-2 z-10">
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


                @include('frontend.partials.product-images-card')

                {{-- CARD CONTENT --}}
                @include('frontend.partials.product-content-card')

            </div>

        </a>

    </div>

@empty
    <div class="col-span-full py-8 text-center text-gray-400 text-sm bg-white rounded-lg border border-gray-200">
        No products found.
    </div>
@endforelse