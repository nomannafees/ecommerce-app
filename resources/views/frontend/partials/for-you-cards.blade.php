@foreach($products as $product)
    @php
        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        $avgRating = $product->reviews->avg('rating') ?? 0;
        $reviewsCount = $product->reviews_count ?? 0;


    @endphp

    <a href="{{ route('product.detail', $product->slug) }}" class="group flex">
        <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-300 overflow-hidden hover:shadow-lg transition duration-300 relative flex flex-col h-full w-full">

            @include('frontend.partials.product-images-card')

            {{-- CARD CONTENT --}}
            @include('frontend.partials.product-content-card')
        </div>
    </a>
@endforeach