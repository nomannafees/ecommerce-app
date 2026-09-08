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

{{-- IMAGE CONTAINER with Professional Smooth Cross-Fade Animation --}}
<div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50"
     x-data="{
                        images: {{ json_encode(@$allImages) }},
                        currentIndex: 0,
                        nextIndex: null,
                        isFading: false,
                        updateImage(event) {
                            if (this.images.length <= 1) return;
                            let rect = event.currentTarget.getBoundingClientRect();
                            let xPos = event.clientX - rect.left;
                            let width = rect.width;
                            let index = Math.floor((xPos / width) * this.images.length);
                            let targetIndex = Math.min(Math.max(index, 0), this.images.length - 1);

                            if (targetIndex !== this.currentIndex && !this.isFading) {
                                this.nextIndex = targetIndex;
                                this.isFading = true;
                                setTimeout(() => {
                                    this.currentIndex = this.nextIndex;
                                    this.isFading = false;
                                }, 300); // Professional smooth transition duration
                            }
                        },
                        resetImage() {
                            if (this.currentIndex !== 0 && !this.isFading) {
                                this.nextIndex = 0;
                                this.isFading = true;
                                setTimeout(() => {
                                    this.currentIndex = 0;
                                    this.isFading = false;
                                }, 300);
                            }
                        }
                     }"
     @mousemove="updateImage(event)"
     @mouseleave="resetImage()">

    <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm" @click.stop>
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <button type="submit"
                class="wishlistBtn  absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-white rounded-full shadow z-10 hover:bg-gray-50 transition"
                style="padding: 4px 9px 4px 9px !important;
                        cursor: pointer;">
            <i class="wishlistIcon  fa-heart text-xs sm:text-sm transition duration-200 {{ @$isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-600' }}"></i>
        </button>
    </form>

    {{-- Base Image --}}
    <img :src="images[currentIndex]"
         alt="{{ $product->name }}"
         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-104">

    {{-- Overlay Image for Smooth Fade Transition Effect --}}
    <template x-if="isFading && nextIndex !== null">
        <img :src="images[nextIndex]"
             alt="{{ $product->name }}"
             class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 ease-in-out"
             x-init="setTimeout(() => $el.style.opacity = '1', 20)">
    </template>
</div>