@extends('frontend.layouts.app')
@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="mb-6 text-sm text-gray-500">
            Home / Products / <span class="text-black font-medium">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <div>
                <!-- SWIPER MAIN SLIDER -->
                <div class="swiper mainImageSwiper bg-white rounded-2xl overflow-hidden shadow-md relative group">
                    <div class="swiper-wrapper cursor-pointer">
                        @foreach($product->variants->unique('variant_image_id') as $v)
                            @if($v->variantImage)
                                <div class="swiper-slide" data-color="{{ $v->color_name }}" data-image-url="{{ asset('storage/' . $v->variantImage->image_path) }}">
                                    <img src="{{ asset('storage/' . $v->variantImage->image_path) }}"
                                         class="w-full h-[500px] object-cover"
                                         alt="{{ $product->name }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div style="padding: 0px 22px" class="swiper-button-next !text-black bg-white/70 w-10 h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-sm"></div>
                    <div style="padding: 0px 22px" class="swiper-button-prev px-5 !text-black bg-white/70 w-10 h-10 rounded-full shadow-md !opacity-0 group-hover:!opacity-100 transition-opacity duration-300 after:!text-sm"></div>
                    <div class="swiper-pagination"></div>
                </div>

                <!-- THUMBNAIL CAROUSEL -->
                <div class="relative mt-4 group">
                    <div id="thumbCarousel" class="flex gap-3 overflow-x-auto scrollbar-hide scroll-smooth select-none py-2">
                        @php $thumbIndex = 0; @endphp
                        @foreach($product->variants->unique('variant_image_id') as $v)
                            @if($v->variantImage)
                                <div class="shrink-0 min-w-[20%]">
                                    <img onclick="changeSwiperSlide({{ $thumbIndex }}, this)"
                                         src="{{ asset('storage/' . $v->variantImage->image_path) }}"
                                         class="thumb cursor-pointer shadow-sm rounded-lg h-20 w-full object-cover border-2 {{ $v->variantImage->is_main ? 'border-gray-400' : 'border-transparent' }} hover:shadow-xl transition-all duration-300"
                                         data-index="{{ $thumbIndex }}">
                                </div>
                                @php $thumbIndex++; @endphp
                            @endif
                        @endforeach
                    </div>

                    <button onclick="moveCarousel(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-r opacity-0 group-hover:opacity-100 transition z-10">❮</button>
                    <button onclick="moveCarousel(1)" class="absolute right-0 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-l opacity-0 group-hover:opacity-100 transition z-10">❯</button>
                </div>
            </div>

            <!-- PRODUCT DETAILS SIDE -->
            <div class="lg:sticky lg:top-6 h-fit">

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 capitalize">
                    {{ $product->name }}
                </h1>

                <p class="mt-2 text-gray-500">
                    Brand: <span class="font-medium text-black">{{ ucfirst($product->prod_brand->name ?? 'Generic') }}</span>
                </p>

                <div class="flex items-center gap-1 mt-4 text-yellow-500">
                    ★★★★☆ <span class="ml-2 text-gray-500 text-sm">(4.5 Reviews)</span>
                </div>

                @php
                    $defaultVariant = $product->mainVariant ?? $product->variants->first();
                    $initialPrice = $defaultVariant ? $defaultVariant->price : ($product->base_price ?? 0);
                    $initialCutPrice = $defaultVariant ? $defaultVariant->cut_price : null;
                    $initialStock = $defaultVariant ? $defaultVariant->stock : 0;
                @endphp

                <div class="mt-6 flex items-end gap-4">
                    <span id="displayPrice" class="text-3xl font-bold text-green-600">
                        Rs {{ number_format($initialPrice) }}
                    </span>
                    <span id="displayCutPrice" class="text-lg text-gray-400 line-through {{ $initialCutPrice ? '' : 'hidden' }}">
                        Rs {{ $initialCutPrice ? number_format($initialCutPrice) : 0 }}
                    </span>
                </div>

                <div class="mt-4">
                    <span id="stockBadge" class="px-3 py-1 rounded-full text-sm font-semibold {{ $initialStock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $initialStock > 0 ? $initialStock . ' Items In Stock' : 'Out of Stock' }}
                    </span>
                </div>

                <div class="mt-8">
                    <h3 class="font-semibold text-lg mb-2">Description</h3>
                    <div class="text-gray-600 leading-7 prose prose-sm line-clamp-2">
                        {!! $product->description !!}
                    </div>
                </div>

                <input type="hidden" id="product_id" value="{{ $product->id }}">
                <input type="hidden" id="selectedVariantId" value="{{ $defaultVariant->id ?? '' }}">
                <input type="hidden" id="selectedVariantStock" value="{{ $initialStock }}">

                @if($product->variants && $product->variants->count() > 0)
                    <div class="mt-8">
                        <h3 class="font-semibold mb-2 text-gray-800">Select Color</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->variants->unique('color_name') as $variant)
                                @php
                                    $colorImg = $variant->variantImage ? asset('storage/' . $variant->variantImage->image_path) : '';
                                    $isDefaultColor = ($defaultVariant && $defaultVariant->color_name == $variant->color_name);
                                @endphp
                                <button type="button"
                                        onclick="selectColor(this, '{{ $variant->color_name }}')"
                                        data-image="{{ $colorImg }}"
                                        class="color-btn border cursor-pointer rounded-lg p-1 flex items-center gap-2 text-sm font-medium transition duration-200 hover:border-gray-400 {{ $isDefaultColor ? 'border-gray-300 bg-black/5 ring-1 ring-black/10' : 'border-gray-200' }}">
                                    @if($colorImg)
                                        <img src="{{ $colorImg }}" class="w-10 h-10 rounded object-cover">
                                    @endif
                                    <span class="pr-3 pl-1">{{ ucfirst($variant->color_name ?? 'Default') }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="font-semibold mb-2 text-gray-800">Select Size</h3>
                        <div id="sizeContainer" class="flex flex-wrap gap-3">
                        </div>
                    </div>
                @endif

                <div class="mt-8">
                    <h3 class="font-semibold mb-2">Quantity</h3>
                    <div class="flex items-center border border-gray-400 rounded-lg w-fit overflow-hidden">
                        <button type="button" onclick="qty(-1)" class="px-4 py-2 text-lg hover:bg-gray-100 cursor-pointer">-</button>
                        <input id="qtyInput" type="text" value="1" class="w-14 text-center outline-none border-x border-x-gray-400" readonly>
                        <button type="button" onclick="qty(1)" class="px-4 py-2 text-lg hover:bg-gray-100 cursor-pointer">+</button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="button" id="addToCartBtn" class="bg-black text-white px-8 py-3 cursor-pointer rounded-lg hover:bg-gray-800 transition">
                        Add To Cart
                    </button>
                    <button type="button" id="buyNowBtn" class="bg-[#ff4d2d] text-white px-8 py-3 rounded-lg hover:bg-[#e63e20] transition w-full sm:w-auto cursor-pointer">
                        <i class="fa-solid fa-bolt mr-2"></i> Buy Now
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- SweetAlert2 aur Swiper CDN (Taqeenan sab se pehle load honi chahiye) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const allVariants = @json($product->variants);
        let selectedColor = "{{ $defaultVariant->color_name ?? '' }}";
        let mainSwiper;

        // Swiper Initializer
        window.addEventListener('DOMContentLoaded', () => {
            mainSwiper = new Swiper('.mainImageSwiper', {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                on: {
                    slideChange: function () {
                        let activeIndex = this.realIndex;
                        let targetThumb = document.querySelector(`.thumb[data-index="${activeIndex}"]`);
                        if (targetThumb) {
                            updateThumbActiveState(targetThumb);
                        }
                    }
                }
            });

            // Set initial active thumb border
            let initialThumb = document.querySelector('.thumb[data-index="0"]');
            if (initialThumb) {
                updateThumbActiveState(initialThumb);
            }

            if (selectedColor) {
                renderSizesForColor(selectedColor, "{{ $defaultVariant->id ?? '' }}");
            }

            // --- BUY NOW CLICK HANDLER (VANILLA JS) ---
            const buyNowBtn = document.getElementById('buyNowBtn');
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    let productId = document.getElementById('product_id').value;
                    let variantId = document.getElementById('selectedVariantId').value;
                    let quantity = document.getElementById('qtyInput').value || 1;
                    let stock = parseInt(document.getElementById('selectedVariantStock').value) || 0;

                    // 1. Stock Validation
                    if (stock <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'This product variant is out of stock!',
                        });
                        return;
                    }

                    if (!variantId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Please select a color and size first.',
                        });
                        return;
                    }

                    // Loading State
                    buyNowBtn.disabled = true;
                    buyNowBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i> Processing...';

                    // 2. Fetch API (AJAX Alternative without JQuery dependency)
                    fetch("{{ route('buy.now') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variant_id: variantId,
                            quantity: quantity
                        })
                    })
                        .then(response => {
                            // Agar user login nahi hai, ya route authenticated nahi hai
                            if (response.status === 401) {
                                throw new Error('Please login first.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            buyNowBtn.disabled = false;
                            buyNowBtn.innerHTML = '<i class="fa-solid fa-bolt mr-2"></i> Buy Now';

                            if (data.success && data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Something went wrong.'
                                });
                            }
                        })
                        .catch(error => {
                            buyNowBtn.disabled = false;
                            buyNowBtn.innerHTML = '<i class="fa-solid fa-bolt mr-2"></i> Buy Now';

                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Failed to process request. Please make sure you are logged in.'
                            });
                        });
                });
            }
        });

        // 1. Color Select
        function selectColor(element, colorName) {
            selectedColor = colorName;

            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.classList.remove('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
                btn.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');

            // Find matching slide index
            let slides = document.querySelectorAll('.mainImageSwiper .swiper-wrapper .swiper-slide:not(.swiper-slide-duplicate)');
            let slideIndexToMove = -1;

            slides.forEach((slide, idx) => {
                if (slide.getAttribute('data-color') === colorName) {
                    if (slideIndexToMove === -1) {
                        slideIndexToMove = idx;
                    }
                }
            });

            if (slideIndexToMove !== -1 && mainSwiper) {
                mainSwiper.slideToLoop(slideIndexToMove);
                let targetThumb = document.querySelector(`.thumb[data-index="${slideIndexToMove}"]`);
                if (targetThumb) {
                    updateThumbActiveState(targetThumb);
                }
            }

            renderSizesForColor(colorName);
        }

        // 2. Render Sizes
        function renderSizesForColor(colorName, preselectedVariantId = null) {
            const sizeContainer = document.getElementById('sizeContainer');
            sizeContainer.innerHTML = '';

            const filteredVariants = allVariants.filter(v => v.color_name === colorName);

            filteredVariants.forEach((variant, index) => {
                const sizeButton = document.createElement('button');
                sizeButton.type = "button";
                sizeButton.innerText = variant.size ? variant.size.toUpperCase() : 'FREE SIZE';

                sizeButton.className = "size-btn border cursor-pointer rounded-lg px-4 py-2 text-sm font-medium transition duration-200 hover:border-gray-400 border-gray-200";

                sizeButton.setAttribute('onclick', `selectSize(this, '${variant.id}', ${variant.stock}, ${variant.price}, '${variant.cut_price || ""}')`);

                sizeContainer.appendChild(sizeButton);

                if ((preselectedVariantId && variant.id == preselectedVariantId) || (!preselectedVariantId && index === 0)) {
                    sizeButton.click();
                }
            });
        }

        // 3. Size Select
        function selectSize(element, variantId, stock, price, cutPrice) {
            document.querySelectorAll('.size-btn').forEach(btn => {
                btn.classList.remove('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');
                btn.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-gray-300', 'bg-black/5', 'ring-1', 'ring-black/10');

            document.getElementById('selectedVariantId').value = variantId;
            document.getElementById('selectedVariantStock').value = stock;
            document.getElementById('qtyInput').value = 1;

            document.getElementById('displayPrice').innerText = "Rs " + price.toLocaleString();
            let cutPriceElement = document.getElementById('displayCutPrice');
            if (cutPrice && parseFloat(cutPrice) > price) {
                cutPriceElement.innerText = "Rs " + parseFloat(cutPrice).toLocaleString();
                cutPriceElement.classList.remove('hidden');
            } else {
                cutPriceElement.classList.add('hidden');
            }

            let stockBadge = document.getElementById('stockBadge');
            if (stock > 0) {
                stockBadge.innerHTML = stock + ' Items In Stock';
                stockBadge.className = "px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700";
            } else {
                stockBadge.innerHTML = 'Out of Stock';
                stockBadge.className = "px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700";
            }
        }

        // Thumbnail click handler
        function changeSwiperSlide(index, element) {
            if (mainSwiper) {
                mainSwiper.slideToLoop(index);
            }
            updateThumbActiveState(element);
        }

        // Update border state on active thumbnail
        function updateThumbActiveState(element) {
            document.querySelectorAll('.thumb').forEach(img => {
                img.classList.remove('border-gray-300');
                img.classList.add('border-transparent');
            });
            element.classList.add('border-gray-300');
            element.classList.remove('border-transparent');
        }

        // Carousel Slider Navigation
        function moveCarousel(direction) {
            const carousel = document.getElementById('thumbCarousel');
            carousel.scrollBy({ left: direction * 150, behavior: 'smooth' });
        }

        // Quantity Controller
        function qty(change) {
            let input = document.getElementById('qtyInput');
            let value = parseInt(input.value) || 1;
            let stock = parseInt(document.getElementById('selectedVariantStock').value) || 0;

            value += change;
            if (value < 1) value = 1;

            if (change > 0 && value > stock) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: stock > 0 ? 'Only ' + stock + ' items available' : 'Product is out of stock',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            input.value = value;
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .swiper-pagination-bullet-active { background: #000 !important; }
    </style>

@endsection
