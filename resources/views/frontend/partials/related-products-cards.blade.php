@foreach($relatedProducts as $relatedProduct)
    @php
        $isWishlisted = in_array($relatedProduct->id, $wishlistProductIds ?? []);
        $avgRating = $relatedProduct->reviews->avg('rating') ?? 0;
        $reviewsCount = $relatedProduct->reviews_count ?? $relatedProduct->reviews->count();

        // Product ki saari variant images ka array tayar karna
        $allImages = [];
        if ($relatedProduct->mainVariantImage) {
            $allImages[] = asset('storage/' . $relatedProduct->mainVariantImage->image_path);
        }
        foreach ($relatedProduct->variantImages as $vImg) {
            $imgPath = asset('storage/' . $vImg->image_path);
            if (!in_array($imgPath, $allImages)) {
                $allImages[] = $imgPath;
            }
        }
        if (empty($allImages)) {
            $allImages[] = asset('upload/no-image.jpg');
        }
    @endphp

    {{-- Card Container --}}
    <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full group">

        {{-- Image Card Include with Variables Passed --}}
        @include('frontend.partials.product-images-card', ['product' => $relatedProduct, 'allImages' => $allImages, 'isWishlisted' => $isWishlisted])

        {{-- CARD CONTENT --}}
        @include('frontend.partials.product-content-card', ['product' => $relatedProduct, 'avgRating' => $avgRating, 'reviewsCount' => $reviewsCount])
    </div>
@endforeach