@forelse($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews_count ?? $product->reviews->count();
        $variant = $product->mainVariant ?? $product->variants->first();

        $salePrice = $variant->price ?? 0;
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

    <!-- CARD -->
    <a href="{{ route('product.detail', $product->slug) }}" class="group flex">
        <div class="bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full w-full">

            @include('frontend.partials.product-images-card')

            {{-- CARD CONTENT --}}
            @include('frontend.partials.product-content-card')
        </div>
    </a>
@empty
    <div class="col-span-full text-center py-16">
        <i class="fa-solid fa-box-open text-5xl sm:text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl sm:text-2xl font-semibold text-gray-600">No Products Found</h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-2">Products will appear here once they are added.</p>
    </div>
@endforelse