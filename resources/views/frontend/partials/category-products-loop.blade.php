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

    <div class="group relative bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full w-full">
        <!-- WISHLIST BUTTON -->
        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm absolute top-2 right-2 z-10">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="wishlistBtn bg-white/90 hover:bg-white rounded-full shadow-sm transition"
                    style="padding: 4px 9px 4px 9px !important; cursor: pointer;">
                <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
            </button>
        </form>

        <a href="{{ route('product.detail', $product->slug) }}" class="flex flex-col h-full w-full">

            @include('frontend.partials.product-images-card')

            {{-- CARD CONTENT --}}
            @include('frontend.partials.product-content-card')
        </a>
    </div>
@empty
    <div class="col-span-full text-center py-16">
        <i class="fa-solid fa-box-open text-5xl sm:text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl sm:text-2xl font-semibold text-gray-600">No Products Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-2">Products will appear here once they are added.</p>
    </div>
@endforelse