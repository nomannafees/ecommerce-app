@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER -->
    <div class="container mx-auto px-4 sm:px-8 md:px-8 lg:px-8 2xl:px-8 3xl:px-8 4xl:px-8 py-6 sm:pb-10 sm:pt-6">

        <!-- FLEX LAYOUT -->
        <div class="flex flex-col gap-6">

            <!-- RIGHT SIDE / MAIN CONTENT -->
            <div class="flex-1">

                <!-- Dynamic Category Banner & Heading -->
                @if(isset($currentCategory))
                    <div
                        class="relative w-full bg-cover bg-center rounded-xl p-8 mb-6 text-white shadow-md overflow-hidden"
                        style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ $currentCategory->image ? asset('storage/'.$currentCategory->image) : asset('default-banner.jpg') }}')">
                        <div class="relative z-10">
                            <h1 class="text-2xl sm:text-4xl font-bold mb-2">{{ $currentCategory->name }}</h1>
                            <p class="text-xs sm:text-sm text-gray-200">Explore our exclusive items
                                in {{ $currentCategory->name }}.</p>
                        </div>
                    </div>

                    <!-- SUB-CATEGORIES SECTION -->
                    @if($currentCategory && $currentCategory->children->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight flex items-center gap-2">
                                    <span class="w-1.5 h-4.5 bg-emerald-600 rounded-full inline-block"></span>
                                    Explore Sub-Categories
                                </h3>
                                <span
                                    class="text-md font-semibold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                                    <span class="font-bold text-black">{{ $currentCategory->children->count() }}</span>  Items
                                </span>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-2">
                                @foreach($currentCategory->children as $subCat)
                                    @php
                                        $currentRequestCategory = request('category');
                                        $nestedSlug = $currentRequestCategory ? $currentRequestCategory . '/' . $subCat->slug : $subCat->slug;
                                    @endphp
                                    <a href="{{ route('categories', array_merge(request()->except('page'), ['category' => $nestedSlug])) }}"
                                       class="group bg-white border border-gray-200/80 rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col text-center relative">
                                        <div class="w-full h-24 sm:h-45 overflow-hidden relative px-3 py-3">
                                            <img
                                                src="{{ $subCat->image ? asset('storage/cat_image/' . $subCat->image) : asset('images/no-image.png') }}"
                                                alt="{{ $subCat->name }}"
                                                class="w-full h-full object-cover rounded-xl bg-white group-hover:scale-105 transition duration-500 ease-out">
                                        </div>
                                        <div
                                            class="px-2.5 pb-2.5 flex items-center justify-center bg-white">
                                            <h4 class="font-medium text-xs sm:text-sm text-gray-800 group-hover:text-emerald-600 transition-colors duration-200 line-clamp-1">
                                                {{ $subCat->name }}
                                            </h4>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center mb-6 lg:mb-0">
                        <h2 class="text-2xl sm:text-4xl font-bold text-gray-900">
                            All Products
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-2 max-w-2xl mx-auto">
                            Choose your favorite products and add them to your cart.
                        </p>
                    </div>
            @endif

            <!-- PRODUCTS GRID SECTION -->
                <div class="mb-6">

                    <!-- HEADING -->
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-5 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>

                        <span>
                    {{ isset($currentCategory) ? 'Products in ' . $currentCategory->name : 'All Products' }}
                </span>
                    </h3>

                @if($products->count() > 0)

                    <!-- PRODUCTS GRID -->
                        <div id="product-grid"
                             class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2">

                            @include('frontend.partials.category-products-loop', [
                                'products' => $products,
                                'wishlistProductIds' => $wishlistProductIds
                            ])

                        </div>

                @else

                    <!-- MATCHING THE CLEAN IMAGE INTERFACE -->
                        <div class="w-full">
                            <div class="container mx-auto py-4 bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center flex flex-col items-center justify-center">

                                <!-- Simple Gray Icon Circle -->
                                <div class="w-14 h-14 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center text-xl mb-4">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>

                                <!-- Heading -->
                                <h3 class="text-base font-bold text-slate-900 mb-1 tracking-tight">
                                    No Products Found
                                </h3>

                                <!-- Subtext -->
                                <p class="text-xs sm:text-sm text-slate-500 mb-6">
                                    There are currently no products available in this category.
                                </p>

                                <!-- Action Button -->
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('index') }}"
                                       class="inline-flex items-center justify-center px-6 py-2.5 text-xs sm:text-sm font-semibold text-white bg-slate-900 rounded-full hover:bg-slate-800 transition shadow-sm">
                                        Back to Home
                                    </a>
                                </div>

                            </div>
                        </div>

                    @endif

                </div>

                <!-- NO MORE PRODUCTS BUTTON -->
                <div id="no-more-products" class="text-center my-6 hidden">
                    <span
                        class="inline-flex items-center gap-2 bg-gray-700 text-white text-xs sm:text-sm font-medium px-5 py-2.5 rounded-md shadow-md cursor-default">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i> No More Products
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection

@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ==============================
            // INFINITE SCROLL
            // ==============================

            let currentPage = {{ $products->currentPage() }};
            let isLoadingMore = false;
            let hasMorePages = {{ $products->hasMorePages() ? 'true' : 'false' }};

            const mainScrollContainer = document.querySelector('main');
            const productGrid = document.getElementById('product-grid');
            const noMoreProducts = document.getElementById('no-more-products');


            // --------------------------------
            // CHECK SCROLL
            // --------------------------------
            function checkScrollForMoreProducts() {

                if (!mainScrollContainer) return;
                if (!hasMorePages) return;
                if (isLoadingMore) return;

                const scrollTop = mainScrollContainer.scrollTop;
                const clientHeight = mainScrollContainer.clientHeight;
                const scrollHeight = mainScrollContainer.scrollHeight;

                // Bottom se 500px pehle next products load
                if (scrollTop + clientHeight >= scrollHeight - 500) {
                    loadMoreProducts();
                }
            }


            // --------------------------------
            // LOAD MORE PRODUCTS
            // --------------------------------
            function loadMoreProducts() {

                if (isLoadingMore || !hasMorePages) {
                    return;
                }

                isLoadingMore = true;

                currentPage++;

                // Loading skeleton
                const skeletons = [];

                for (let i = 0; i < 6; i++) {

                    const skeleton = document.createElement('div');

                    skeleton.className =
                        'product-shimmer bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden animate-pulse';

                    skeleton.innerHTML = `
                <div class="bg-gray-200 h-40 sm:h-48 w-full"></div>

                <div class="p-3 space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            `;

                    productGrid.appendChild(skeleton);
                    skeletons.push(skeleton);
                }


                // Current URL preserve rahegi
                // category bhi preserve hogi
                // sirf page change hoga
                const url = new URL(window.location.href);

                url.searchParams.set('page', currentPage);


                fetch(url.toString(), {
                    method: 'GET',

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })

                    .then(response => {

                        if (!response.ok) {
                            throw new Error('HTTP Error: ' + response.status);
                        }

                        return response.text();
                    })

                    .then(html => {

                        // Skeleton remove
                        skeletons.forEach(skeleton => {
                            skeleton.remove();
                        });


                        // Agar next page empty hai
                        if (!html.trim()) {

                            hasMorePages = false;

                            // currentPage ko previous page par wapas
                            currentPage--;

                            if (noMoreProducts) {
                                noMoreProducts.classList.remove('hidden');
                            }

                            isLoadingMore = false;

                            return;
                        }


                        // New products append
                        productGrid.insertAdjacentHTML('beforeend', html);

                        isLoadingMore = false;


                        // Agar naye products ke baad bhi container
                        // scrollable nahi hua to next page automatically load
                        setTimeout(() => {
                            checkScrollForMoreProducts();
                        }, 100);
                    })

                    .catch(error => {

                        console.error('Infinite scroll error:', error);

                        // Skeleton remove
                        skeletons.forEach(skeleton => {
                            skeleton.remove();
                        });

                        // Failed request ki wajah se page number galat na rahe
                        currentPage--;

                        isLoadingMore = false;
                    });
            }


            // --------------------------------
            // MAIN SCROLL LISTENER
            // --------------------------------
            if (mainScrollContainer) {

                mainScrollContainer.addEventListener(
                    'scroll',
                    checkScrollForMoreProducts,
                    { passive: true }
                );

                // Initial check
                // Agar first 12 products screen se chhote hain
                // to automatically next products load hon
                setTimeout(() => {
                    checkScrollForMoreProducts();
                }, 300);
            }


            // --------------------------------
            // OPTIONAL WINDOW SCROLL FALLBACK
            // --------------------------------
            window.addEventListener('scroll', function () {

                if (!hasMorePages || isLoadingMore) {
                    return;
                }

                const windowBottom =
                    window.innerHeight + window.scrollY;

                const documentHeight =
                    document.documentElement.scrollHeight;

                if (windowBottom >= documentHeight - 500) {
                    loadMoreProducts();
                }

            }, { passive: true });

        });
    </script>

@endpush
