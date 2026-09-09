@php
    // Product ki saari variant images ka array tayar karna
    $allImages = [];
    if ($product->mainVariantImage) {
        $allImages[] = asset('storage/' . $product->mainVariantImage->image_path);
    }
    foreach ($product->variantImages as $vImg) {
        $imgPath = asset('storage/' . $vImg->image_path);
        // Duplicate images avoid karne ke liye check
        if (!in_array($imgPath, $allImages)) {
            $allImages[] = $imgPath;
        }
    }
    // Agar koi image na ho toh default no-image lagayein
    if (empty($allImages)) {
        $allImages[] = asset('upload/no-image.jpg');
    }
@endphp

{{-- IMAGE CONTAINER with Hover & Touch Scrub Support (Auto-slide removed) --}}
<div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50 select-none"
     x-data="{
        images: {{ json_encode(@$allImages) }},
        currentIndex: 0,
        nextIndex: null,
        isFading: false,

        // Image change aur fade effect handle karne ka core function
        changeImage(targetIndex) {
            if (targetIndex !== this.currentIndex && !this.isFading) {
                this.nextIndex = targetIndex;
                this.isFading = true;
                setTimeout(() => {
                    this.currentIndex = this.nextIndex;
                    this.isFading = false;
                }, 300);
            }
        },

        // Mouse ya Touch move par position calculate karne ke liye
        handleMove(clientX) {
            if (this.images.length <= 1) return;
            let rect = this.$el.getBoundingClientRect();
            let xPos = clientX - rect.left;
            let width = rect.width;
            let index = Math.floor((xPos / width) * this.images.length);
            let targetIndex = Math.min(Math.max(index, 0), this.images.length - 1);
            this.changeImage(targetIndex);
        },

        resetImage() {
            this.changeImage(0);
        }
     }"
     @mouseleave="resetImage()"
     @touchstart="handleMove($event.touches[0].clientX)"
     @touchmove="handleMove($event.touches[0].clientX)"
     @mousemove="handleMove($event.clientX)">

    <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm" @click.stop>
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <button type="submit"
                class="wishlistBtn absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10 hover:bg-gray-50 transition"
                style="padding: 4px 9px 4px 9px !important; cursor: pointer;">
            <i class="wishlistIcon fa-heart text-xs sm:text-sm transition duration-200 {{ @$isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-600' }}"></i>
        </button>
    </form>

    {{-- Base Image --}}
    <img :src="images[currentIndex]"
         alt="{{ $product->name }}"
         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-104 pointer-events-none">

    {{-- Overlay Image for Smooth Fade Transition Effect --}}
    <template x-if="isFading && nextIndex !== null">
        <img :src="images[nextIndex]"
             alt="{{ $product->name }}"
             class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 ease-in-out pointer-events-none"
             x-init="setTimeout(() => $el.style.opacity = '1', 20)">
    </template>
</div>

<!-- Shimmer Effect Loader (Matching your exact product card) -->
<div id="product-shimmer" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 mb-4 gap-2 lg:gap-3 xl:gap-3 2xl:gap-3 md:gap-3">
    @for($i = 0; $i < 6; $i++)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">

            {{-- 1. Image Box Shimmer (Exact Height Match & Wishlist Circle) --}}
            <div class="relative bg-gray-200 h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50 w-full">
                {{-- Wishlist Button Placeholder --}}
                <div class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 w-7 h-7 bg-gray-300 rounded-full"></div>
            </div>

            {{-- 2. Content Area Shimmer --}}
            <div class="p-1.5 sm:p-2.5 flex flex-col justify-between gap-2 flex-grow">
                <div>
                    {{-- Title Shimmer --}}
                    <div class="bg-gray-200 h-4 rounded w-4/5 mb-1.5"></div>

                    {{-- Description Shimmer --}}
                    <div class="bg-gray-200 h-3 rounded w-3/5 mb-2"></div>

                    {{-- Rating & Sold Shimmer Row --}}
                    <div class="flex items-center justify-between gap-1 mt-1">
                        <div class="bg-gray-200 h-3 rounded w-16"></div>
                        <div class="bg-gray-200 h-3 rounded w-10"></div>
                    </div>
                </div>

                {{-- Price & Stock Shimmer Row --}}
                <div class="flex items-center justify-between gap-1.4 mt-2">
                    {{-- Price Side --}}
                    <div class="space-y-1">
                        <div class="bg-gray-200 h-5 rounded w-16"></div>
                        <div class="bg-gray-200 h-3 rounded w-12"></div>
                    </div>
                    {{-- Stock & Delivery Side --}}
                    <div class="flex flex-col items-end gap-1">
                        <div class="bg-gray-200 h-4 rounded w-16"></div>
                        <div class="bg-gray-200 h-3 rounded w-14"></div>
                    </div>
                </div>

            </div>

        </div>
    @endfor
</div>


@push('scripts')

    <script !src="">
        function loadMoreProducts() {
            // 1. Shimmer show karein
            document.getElementById('product-shimmer').classList.remove('hidden');

            fetch(`{{ url()->current() }}?page=` + page, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
                .then(response => response.text())
                .then(html => {
                    // 2. Shimmer hide karein
                    document.getElementById('product-shimmer').classList.add('hidden');

                    if (html.trim() !== '') {
                        // Products grid mein naye cards append karein
                        document.getElementById('products-grid').insertAdjacentHTML('beforeend', html);
                        isLoading = false;
                    } else {
                        hasMorePages = false;
                    }
                })
                .catch(error => {
                    console.error('Error loading more products:', error);
                    // Error ki surat mein bhi shimmer hide kar dein
                    document.getElementById('product-shimmer').classList.add('hidden');
                    isLoading = false;
                });
        }
    </script>

@endpush