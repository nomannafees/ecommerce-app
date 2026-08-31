@extends('frontend.layouts.app')

@section('content')
    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-6">

        <!-- DYNAMIC BANNER IMAGE (FULL WIDTH & BALANCED HEIGHT) -->
    @php
        $segment = request()->segment(3);

        if ($segment == 'flash-sale') {
            $bannerImage = asset('assets/images/product_banners/flashsale2.jpeg');
        } elseif ($segment == 'bestselling') {
            $bannerImage = asset('assets/images/product_banners/bestselling2.png');
        } else {
            $bannerImage = asset('assets/images/product_banners/featured2.jpeg');
        }
    @endphp

    <!-- Banner -->
        <div class="relative w-full overflow-hidden rounded-lg mb-4 -mt-2 shadow-sm bg-white">
            <img
                    src="{{ $bannerImage }}"
                    alt="Banner"
                    class="w-full h-auto object-contain sm:h-52 sm:object-cover md:h-60 lg:h-65 object-center"
            >
        </div>

        <!-- 1. PRODUCTS GRID SECTION -->
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 mb-8">
            @include('frontend.partials.product-list-cart')
        </div>

        <!-- 2. SENTINEL -->
        <div id="scroll-sentinel" style="height:1px;"></div>

        <!-- 3. SHIMMER SKELETON GRID -->
        <div id="shimmer-grid" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 mb-8">
            @for($i = 0; $i < 6; $i++)
                <div class="bg-white rounded-sm sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full w-full">
                    <div class="shimmer h-50 xs:h-44 sm:h-40 2xl:h-50 md:h-50 lg:h-50 bg-gray-200"></div>
                    <div class="p-2.5 sm:p-2.5 flex flex-col gap-2">
                        <div class="shimmer h-3.5 w-3/4 bg-gray-200 rounded"></div>
                        <div class="shimmer h-2.5 w-full bg-gray-200 rounded"></div>
                        <div class="shimmer h-2.5 w-1/2 bg-gray-200 rounded"></div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="shimmer h-4 w-16 bg-gray-200 rounded"></div>
                            <div class="shimmer h-4 w-14 bg-gray-200 rounded-full"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

    </div>

    <style>
        .shimmer {
            position: relative;
            overflow: hidden;
            background-color: #e5e7eb;
        }
        .shimmer::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: translateX(-100%);
            background-image: linear-gradient(
                    90deg,
                    rgba(255, 255, 255, 0) 0,
                    rgba(255, 255, 255, 0.6) 20%,
                    rgba(255, 255, 255, 0.9) 60%,
                    rgba(255, 255, 255, 0)
            );
            animation: shimmer 1.4s infinite;
        }
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
    </style>

    <script>
        (function () {
            let nextPageUrl = "{{ $products->nextPageUrl() }}";
            let isLoading = false;
            let currentSegment = "{{ request()->segment(3) }}";
            let observer = null;

            const shimmerGrid = document.getElementById('shimmer-grid');
            const productGrid = document.getElementById('product-grid');

            function showShimmer() {
                shimmerGrid.classList.remove('hidden');
            }

            function hideShimmer() {
                shimmerGrid.classList.add('hidden');
            }

            function loadMore() {
                if (!nextPageUrl || isLoading) return;

                isLoading = true;
                showShimmer();

                let requestUrl = nextPageUrl;
                if (currentSegment && !requestUrl.includes('type=')) {
                    requestUrl += (requestUrl.includes('?') ? '&' : '?') + 'type=' + currentSegment;
                }

                fetch(requestUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        hideShimmer();

                        if (data.html) {
                            productGrid.insertAdjacentHTML('beforeend', data.html);
                        }

                        nextPageUrl = data.next_page_url || null;
                        isLoading = false;

                        if (!nextPageUrl && observer) {
                            observer.disconnect();
                        }
                    })
                    .catch(function (err) {
                        console.error('Load more error:', err);
                        hideShimmer();
                        isLoading = false;
                    });
            }

            var sentinel = document.getElementById('scroll-sentinel');

            if (sentinel && 'IntersectionObserver' in window) {
                observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            loadMore();
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '300px',
                    threshold: 0
                });

                observer.observe(sentinel);
            } else {
                window.addEventListener('scroll', function () {
                    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 300) {
                        loadMore();
                    }
                });
            }
        })();
    </script>
@endsection