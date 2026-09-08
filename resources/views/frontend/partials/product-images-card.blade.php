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

{{-- IMAGE CONTAINER with Auto-Slide, Hover & Touch Support --}}
<div class="relative bg-gray-100 overflow-hidden h-40 xs:h-44 sm:h-50 2xl:h-50 md:h-50 lg:h-50 select-none"
     x-data="{
        images: {{ json_encode(@$allImages) }},
        currentIndex: 0,
        nextIndex: null,
        isFading: false,
        timer: null,

        // Auto slide start karne ka function (har 3 seconds baad)
        startAutoSlide() {
            if (this.images.length <= 1) return;
            this.timer = setInterval(() => {
                let targetIndex = (this.currentIndex + 1) % this.images.length;
                this.changeImage(targetIndex);
            }, 3000);
        },

        // Auto slide rokne ka function
        stopAutoSlide() {
            clearInterval(this.timer);
            this.timer = null;
        },

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
     x-init="startAutoSlide()"
     @mouseenter="stopAutoSlide()"
     @mouseleave="stopAutoSlide(); resetImage(); startAutoSlide()"
     @touchstart="stopAutoSlide(); handleMove($event.touches[0].clientX)"
     @touchmove="handleMove($event.touches[0].clientX)"
     @touchend="startAutoSlide()"
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

    {{-- Overlay Image for Smooth Fade Transition Effect (Fixed typo 'logic') --}}
    <template x-if="isFading && nextIndex !== null">
        <img :src="images[nextIndex]"
             alt="{{ $product->name }}"
             class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 ease-in-out pointer-events-none"
             x-init="setTimeout(() => $el.style.opacity = '1', 20)">
    </template>
</div>