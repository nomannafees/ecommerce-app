@foreach($records as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $totalStock = $product->variants->sum('stock');

        $variant = $product->active_variant;
        $imagePath = $product->custom_image_path;

        $avgRating = $product->avgRating ?? 0;
        $reviewsCount = $product->reviews_count ?? $product->reviews->count();

        $salePrice = $variant->price ?? ($product->base_price ?? 0);
        $originalPrice = $variant->cut_price ?? 0;

        $calculatedDiscount = 0;
        if ($originalPrice > 0 && $originalPrice > $salePrice) {
            $calculatedDiscount = round((($originalPrice - $salePrice) / $originalPrice) * 100);
        }

      // Product ki saari variant images ka array tayar karna (Smooth Animation ke liye)
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

    <div class="relative bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 group flex flex-col h-full w-full">

        <form action="{{ route('wishlists.store') }}" method="POST"
              class="wishlistForm absolute top-2 right-2 sm:top-2 sm:right-2 z-15">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit"
                    class="wishlistBtn bg-white/90 hover:bg-white rounded-full shadow-sm transition flex items-center justify-center"
                    style="padding: 7px 7px 6px 7px !important;
                        cursor: pointer;">
                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
            </button>
        </form>

        @php
            $selectedColorForLink = is_array(request('color')) ? (request('color')[0] ?? null) : request('color');
        @endphp
        <a href="{{ route('product.detail', $product->slug) }}{{ $selectedColorForLink ? '?color='.$selectedColorForLink : '' }}"
           class="block z-10 flex flex-col h-full">
            @include('frontend.partials.product-images-card')

            {{-- CARD CONTENT --}}
            @include('frontend.partials.product-content-card')
        </a>
    </div>
@endforeach