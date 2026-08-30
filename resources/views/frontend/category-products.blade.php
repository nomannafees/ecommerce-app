@extends('frontend.layouts.app')

@section('content')

    <!-- MAIN CONTAINER -->
    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-6 sm:pb-10 sm:pt-6">

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
                                    class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                                    {{ $currentCategory->children->count() }} Items
                                </span>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-3 sm:gap-4">
                                @foreach($currentCategory->children as $subCat)
                                    @php
                                        $currentRequestCategory = request('category');
                                        $nestedSlug = $currentRequestCategory ? $currentRequestCategory . '/' . $subCat->slug : $subCat->slug;
                                    @endphp
                                    <a href="{{ route('categories', array_merge(request()->except('page'), ['category' => $nestedSlug])) }}"
                                       class="group bg-white border border-gray-200/80 rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col text-center relative">
                                        <div class="w-full h-24 sm:h-30 bg-gray-50 overflow-hidden relative">
                                            <img
                                                src="{{ $subCat->image ? asset('storage/cat_image/' . $subCat->image) : asset('images/no-image.png') }}"
                                                alt="{{ $subCat->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                                        </div>
                                        <div
                                            class="p-2.5 flex items-center justify-center bg-white border-t border-gray-100/60">
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
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-5 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
                        <span>{{ isset($currentCategory) ? 'Products in ' . $currentCategory->name : 'All Products' }}</span>
                    </h3>

                    <!-- GRID WITH ID FOR INFINITE SCROLL -->
                    <div id="product-grid"
                         class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                        @include('frontend.partials.category-products-loop', ['products' => $products, 'wishlistProductIds' => $wishlistProductIds])
                    </div>
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
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Handle form input changes (Checkbox, Range Slider)
            document.addEventListener('change', function (e) {
                if (e.target.closest('#filterForm') && e.target.classList.contains('filter-input')) {
                    e.preventDefault();
                    submitFilterForm();
                }
            });

            // 2. Handle Price Slider release (mouseup / touchend)
            window.applyPriceFilter = function (value) {
                document.getElementById('priceLabel').innerText = Number(value).toLocaleString();
                submitFilterForm();
            };

            // 3. Handle Category & Pagination Link Clicks via AJAX
            window.fetchCategoryProducts = function (event, url, categorySlug) {
                event.preventDefault();

                // Optional: Update browser URL without reloading
                window.history.pushState({}, '', url);

                fetchProducts(url);
            };

            // 4. Reset Filters Handler
            window.fetchResetFilters = function (event, url) {
                event.preventDefault();
                window.history.pushState({}, '', url);
                fetchProducts(url);
            };

            // Core AJAX Fetch Function
            function submitFilterForm() {
                const form = document.getElementById('filterForm');
                const formData = new FormData(form);
                const url = form.getAttribute('action') + '?' + new URLSearchParams(formData).toString();

                window.history.pushState({}, '', url);
                fetchProducts(url);
            }

            function fetchProducts(url) {
                // Optional: Add a loading state / opacity mask to your product grid here

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Update Product Grid container (Make sure your main container has id="product-grid-container")
                        const productContainer = document.getElementById('product-grid-container');
                        if (productContainer && data.products !== undefined) {
                            productContainer.innerHTML = data.products;
                        }

                        // Update Sidebar container (Make sure your sidebar wrapper has id="sidebar-container")
                        const sidebarContainer = document.getElementById('sidebar-container');
                        if (sidebarContainer && data.sidebar !== undefined) {
                            sidebarContainer.innerHTML = data.sidebar;
                        }
                    })
                    .catch(error => console.error('Error fetching filtered products:', error));
            }
        });
    </script>

@endpush
