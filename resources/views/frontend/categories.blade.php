@extends('frontend.layouts.app')

@section('content')

    <!-- FILTER FORM (PRICE, COLOR, SIZE, BRANDS) -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-2 sm:pt-4 sm:pb-2">

        <div class="text-center mt-2 px-3">
            <h1 class="text-2xl font-bold text-gray-900">
                🛍️ Explore Our Products
            </h1>
            <p class="text-[16px] text-gray-500 mt-0.5 max-w-md mx-auto">
                Shop top products by category, price, color, and size.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 pb-6 pt-3 lg:mt-0">

        @include('frontend.partials.category-sidebar')

        <!-- RIGHT SIDE (Products Grid - 5 columns) -->
            <div class="w-full  lg:w-4/5 container mx-auto ">

                <!-- DESKTOP SORT BAR -->
                <div
                        class="mb-3 hidden bg-white lg:flex flex-row gap-4 items-center justify-between bg-gray-50 border border-gray-200 rounded-xl py-2 px-4">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-700">Products</h2>

                    <div class="flex items-center gap-3">
                        <div class="w-40 relative h-[34px]" x-data="{
    open: false,
    currentSort: '{{ request('sort', 'latest') }}',
    sortLabels: {
        'latest': 'Latest Products',
        'price_low_high': 'Price: Low to High',
        'price_high_low': 'Price: High to Low'
    },
    changeSort(value) {
        this.currentSort = value;
        this.open = false;

        if (typeof fetchFilteredProducts === 'function') {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', value);
            window.history.pushState({}, '', url);
            fetchFilteredProducts();
        } else {
            window.location.href = window.location.pathname + '?' + new URLSearchParams(new FormData(document.getElementById('searchSortForm') || document.createElement('form'))).toString() + '&sort=' + value;
        }
    }
}">
                            <button type="button"
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="w-full h-full bg-white border border-gray-200 rounded-full pl-3 pr-8 focus:outline-none focus:ring-gray-200 focus:border-gray-200 text-xs cursor-pointer font-medium text-gray-700 flex items-center justify-between">
                <span class="truncate leading-none"
                      x-text="sortLabels[currentSort] || 'Latest Products'"></span>
                                <span
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                    <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </span>
                            </button>

                            <div x-show="open"
                                 style="display: none;"
                                 class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50 py-1">

                                <button type="button"
                                        @click="changeSort('latest')"
                                        class="w-full text-left block px-3 py-2 text-xs text-gray-700 hover:bg-gray-100 transition cursor-pointer"
                                        :class="currentSort == 'latest' ? 'bg-gray-50 font-semibold' : ''">
                                    Latest Products
                                </button>

                                <button type="button"
                                        @click="changeSort('price_low_high')"
                                        class="w-full text-left block px-3 py-2 text-xs text-gray-700 hover:bg-gray-100 transition cursor-pointer"
                                        :class="currentSort == 'price_low_high' ? 'bg-gray-50 font-semibold' : ''">
                                    Price: Low to High
                                </button>

                                <button type="button"
                                        @click="changeSort('price_high_low')"
                                        class="w-full text-left block px-3 py-2 text-xs text-gray-700 hover:bg-gray-100 transition cursor-pointer"
                                        :class="currentSort == 'price_high_low' ? 'bg-gray-50 font-semibold' : ''">
                                    Price: High to Low
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- MOBILE HORIZONTAL SCROLLING FILTER BUTTONS -->
                <div
                        class="mb-3 block lg:hidden bg-gray-50 border border-gray-200 rounded-xl p-3 shadow-sm space-y-2 relative">
                    <div class="flex justify-between items-center px-1">
                        <h2 class="text-sm font-semibold text-gray-700">Filters & Sort</h2>
                        @if(request()->hasAny(['min_price', 'max_price', 'color', 'size', 'brand', 'search', 'sort', 'category']))
                            <a href="{{ request()->url() }}"
                               onclick="fetchResetFilters(event, '{{ request()->url() }}')"
                               class="text-xs text-red-500 underline font-medium hover:text-red-700 transition">
                                Clear Filter
                            </a>
                        @else
                            <span class="text-[11px] text-gray-400">Tap to filter &rarr;</span>
                        @endif
                    </div>

                    @php
                        $selectedBrandReq = request('brand');
                        if (is_array($selectedBrandReq)) {
                            $filteredBrands = array_filter($selectedBrandReq);
                            $brandLabel = count($filteredBrands) > 0 ? 'Brand: ' . implode(', ', array_map('ucfirst', $filteredBrands)) : 'Brands';
                        } elseif ($selectedBrandReq) {
                            $brandLabel = 'Brand: ' . ucfirst($selectedBrandReq);
                        } else {
                            $brandLabel = 'Brands';
                        }
                        $currentSelectedBrands = array_filter((array) request('brand', []));

                        $currentSelectedColors = array_filter((array) request('color', []));
                        $colorLabel = count($currentSelectedColors) > 0
                            ? 'Color: ' . implode(', ', array_map('ucfirst', $currentSelectedColors))
                            : 'Colors';

                        $currentSelectedSizes = array_filter((array) request('size', []));
                        $sizeLabel = count($currentSelectedSizes) > 0
                            ? 'Size: ' . implode(', ', array_map('strtoupper', $currentSelectedSizes))
                            : 'Sizes';
                    @endphp

                    <div
                            class="flex items-center gap-2 overflow-x-auto -mb-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                        <button type="button" onclick="toggleMobDropdown('mobSortDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span>Sort</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobCategoryDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span>{{ request('category') ? 'Category Selected' : 'Categories' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobBrandDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span id="mobBrandLabelSpan" class="truncate max-w-[120px]">{{ $brandLabel }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobColorDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span id="mobColorLabelSpan" class="truncate max-w-[120px]">{{ $colorLabel }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobSizeDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span id="mobSizeLabelSpan" class="truncate max-w-[120px]">{{ $sizeLabel }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                    </div>

                    <!-- MOBILE DROPDOWNS -->
                    <div id="mobSortDropdown"
                         class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Sort Products</h3>
                            <button type="button" onclick="closeAllMobDropdowns()"
                                    class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1">
                            <button type="button" onclick="selectMobSort('latest')"
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ request('sort') == 'latest' || !request('sort') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                Latest Products
                            </button>
                            <button type="button" onclick="selectMobSort('price_low_high')"
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ request('sort') == 'price_low_high' ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                Price: Low to High
                            </button>
                            <button type="button" onclick="selectMobSort('price_high_low')"
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ request('sort') == 'price_high_low' ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                Price: High to Low
                            </button>
                        </div>
                    </div>

                    <div id="mobCategoryDropdown"
                         class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Category</h3>
                            <button type="button" onclick="closeAllMobDropdowns()"
                                    class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1">
                            @php
                                $buildCategoryPath = function ($categoryItem) {
                                    $slugs = [];
                                    $item = $categoryItem;
                                    while ($item) {
                                        array_unshift($slugs, $item->slug);
                                        $item = $item->parent;
                                    }
                                    return implode('/', $slugs);
                                };
                            @endphp
                            <a href="{{ url('/collection') }}"
                               onclick="fetchCategoryProducts(event, '{{ url('/collection') }}', '')"
                               class="block px-2.5 py-1.5 rounded border {{ !request()->route('category') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">All
                                Categories</a>
                            @foreach($categories as $cat)
                                @php
                                    $catPath = $buildCategoryPath($cat);
                                    $targetUrl = url('/collection/' . $catPath);
                                @endphp
                                <a href="{{ $targetUrl }}"
                                   onclick="fetchCategoryProducts(event, '{{ $targetUrl }}', '{{ $catPath }}')"
                                   class="block px-2.5 py-1.5 rounded border {{ request()->route('category') == $cat->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div id="mobBrandDropdown"
                         class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Brand</h3>
                            <button type="button" onclick="closeAllMobDropdowns()"
                                    class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1">
                            <button type="button" onclick="selectBrand('')"
                                    data-brand-option=""
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ empty($currentSelectedBrands) ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Brands
                            </button>
                            @foreach($availableBrands as $brand)
                                @php
                                    $isBrandActive = in_array($brand->slug, $currentSelectedBrands);
                                @endphp
                                <button type="button" onclick="selectBrand('{{ $brand->slug }}')"
                                        data-brand-option="{{ $brand->slug }}"
                                        data-brand-name="{{ ucfirst($brand->name) }}"
                                        class="w-full text-left block px-2.5 py-1.5 rounded border {{ $isBrandActive ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                    {{ ucfirst($brand->name) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div id="mobColorDropdown"
                         class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Color</h3>
                            <button type="button" onclick="closeAllMobDropdowns()"
                                    class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1.5">
                            <button type="button" onclick="selectColorSwatch('')"
                                    data-color-option=""
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ empty($currentSelectedColors) ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Colors
                            </button>
                            <div class="flex flex-wrap gap-2 py-1">
                                @foreach($availableColors as $colorName)
                                    @php
                                        $cleanColor = strtolower(trim($colorName));
                                        $isSelected = in_array($cleanColor, $currentSelectedColors);
                                        $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';
                                    @endphp
                                    <button type="button" onclick="selectColorSwatch('{{ $cleanColor }}')"
                                            title="{{ ucfirst($colorName) }}"
                                            data-color-option="{{ $cleanColor }}"
                                            data-color-name="{{ ucfirst($colorName) }}"
                                            class="mob-color-swatch w-7 h-7 rounded-full cursor-pointer transition-all duration-200 flex items-center justify-center shadow-sm {{ $isSelected ? 'ring-2 ring-offset-1 ring-black scale-110' : 'opacity-90 hover:opacity-100' }}"
                                            style="{{ $inlineBg }}">
                                        <i class="mob-color-check fa-solid fa-check text-[10px] {{ $isSelected ? '' : 'hidden' }} {{ in_array($cleanColor, ['white', 'yellow', 'lightgray']) ? 'text-black' : 'text-white' }}"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div id="mobSizeDropdown"
                         class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Size</h3>
                            <button type="button" onclick="closeAllMobDropdowns()"
                                    class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1.5">
                            <button type="button" onclick="selectSizeBox('')"
                                    data-size-option=""
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ empty($currentSelectedSizes) ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Sizes
                            </button>
                            <div class="flex flex-wrap gap-1.5 py-1">
                                @foreach($availableSizes as $sizeName)
                                    @php
                                        $cleanSize = trim($sizeName);
                                        $isSizeSelected = in_array($cleanSize, $currentSelectedSizes);
                                    @endphp
                                    <button type="button" onclick="selectSizeBox('{{ $cleanSize }}')"
                                            data-size-option="{{ $cleanSize }}"
                                            class="mob-size-box px-2.5 py-1 text-xs rounded border transition font-medium {{ $isSizeSelected ? 'bg-black text-white border-black shadow-sm' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                                        {{ strtoupper($cleanSize) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS GRID (5 columns on lg/xl) -->
                <div id="productGrid"
                     class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 mb-5 sm:gap-3">
                    @include('frontend.partials.category-product-cards', ['records' => $records, 'wishlistProductIds' => $wishlistProductIds])
                </div>

                <!-- PRODUCT NOT FOUND MESSAGE (Agar koi product na mile) -->
                @if($records->isEmpty())
                    <div class="w-full bg-white border border-gray-200 -mt-6 rounded-xl p-12 text-center my-6 no_products_found">
                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">No Products Found</h3>
                        <p class="text-sm text-gray-500 mb-5">We couldn't find any products matching your search or filters.</p>
                        <a href="{{ route('categories') }}"
                           class="inline-block bg-black hover:bg-gray-800 text-white text-xs font-semibold px-5 py-2.5 rounded-full transition">
                            Reset Filters & Search
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>


@endsection

@push('scripts')
    {{-- 1. Shimmer Animation CSS --}}
    <style>
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-shimmer {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
    </style>

    {{-- 2. Skeleton Generator Function --}}
    <script>
        function renderProductSkeletons(count = 10) {
            let skeletons = '';
            for (let i = 0; i < count; i++) {
                skeletons += `
            <div class="bg-white rounded-md sm:rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">
                <div class="bg-gray-200 h-40 xs:h-44 sm:h-50 2xl:h-47 md:h-45 lg:h-55 animate-shimmer"></div>
                <div class="p-2.5 sm:p-2.5 flex-grow flex flex-col justify-between gap-2.5">
                <div class="space-y-2">
                <div class="h-4 bg-gray-200 rounded animate-shimmer w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded animate-shimmer w-full"></div>
                </div>
                <div class="h-3 bg-gray-200 rounded animate-shimmer w-1/3"></div>
                <div class="flex items-center justify-between gap-2 mt-auto pt-2">
                <div class="h-4 bg-gray-200 rounded animate-shimmer w-1/3"></div>
                <div class="h-4 bg-gray-200 rounded animate-shimmer w-1/4"></div>
                </div>
                </div>
                </div>
                `;
            }
            return skeletons;
        }
    </script>

    <script>
        // --- GLOBAL VARIABLES ---
        let currentAjaxReq = null;
        let page = 1;
        let hasMorePages = {{ $records->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;

        // --- REUSABLE "NO PRODUCTS FOUND" HTML FUNCTION ---
        function getNoProductsHtml() {
            return `
            <div class="col-span-full w-full bg-white -mt-1 border border-gray-200 rounded-2xl p-12 text-center shadow-sm my-6">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">No Products Found</h3>
                <p class="text-sm text-gray-500 mb-5">We couldn't find any products matching your search or filters.</p>
                <a href="{{ route('categories') }}"
                   class="inline-block bg-black hover:bg-gray-800 text-white text-xs font-semibold px-5 py-2.5 rounded-full transition">
                    Reset Filters & Search
                </a>
            </div>
        `;
        }

        // --- 1. PRICE & FILTER FUNCTIONS ---
        function updatePriceLabel(value) {
            document.getElementById('priceLabel').innerText = Number(value).toLocaleString();
        }

        // ====================================================================
        // NEW: Sync mobile pill labels + active highlight states after AJAX
        // (Blade only renders these once on page load; since we no longer
        // reload the page, we must update them manually in JS every time
        // the filters change.)
        // ====================================================================
        function updateMobileFilterUI() {
            // ---- BRANDS ----
            let selectedBrandSlugs = $('#filterForm input[name="brand[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            $('[data-brand-option]').each(function () {
                let val = $(this).attr('data-brand-option');
                let isActive = val === '' ? selectedBrandSlugs.length === 0 : selectedBrandSlugs.includes(val);
                $(this).toggleClass('border-gray-400 font-semibold bg-gray-50', isActive);
                $(this).toggleClass('border-gray-100', !isActive);
            });

            if (selectedBrandSlugs.length > 0) {
                let names = selectedBrandSlugs.map(function (slug) {
                    let btn = $('[data-brand-option="' + slug + '"]');
                    return btn.attr('data-brand-name') || slug;
                });
                $('#mobBrandLabelSpan').text('Brand: ' + names.join(', '));
            } else {
                $('#mobBrandLabelSpan').text('Brands');
            }

            // ---- COLORS ----
            let selectedColors = $('#filterForm input[name="color[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            $('[data-color-option]').each(function () {
                let val = $(this).attr('data-color-option');
                if (val === '') {
                    let isActive = selectedColors.length === 0;
                    $(this).toggleClass('border-gray-400 font-semibold bg-gray-50', isActive);
                    $(this).toggleClass('border-gray-100', !isActive);
                }
            });

            $('.mob-color-swatch').each(function () {
                let val = $(this).attr('data-color-option');
                let isActive = selectedColors.includes(val);
                $(this).toggleClass('ring-2 ring-offset-1 ring-black scale-110', isActive);
                $(this).toggleClass('opacity-90 hover:opacity-100', !isActive);
                $(this).find('.mob-color-check').toggleClass('hidden', !isActive);
            });

            if (selectedColors.length > 0) {
                let names = selectedColors.map(function (c) {
                    let btn = $('.mob-color-swatch[data-color-option="' + c + '"]');
                    return btn.attr('data-color-name') || c;
                });
                $('#mobColorLabelSpan').text('Color: ' + names.join(', '));
            } else {
                $('#mobColorLabelSpan').text('Colors');
            }

            // ---- SIZES ----
            let selectedSizes = $('#filterForm input[name="size[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            $('[data-size-option]').each(function () {
                let val = $(this).attr('data-size-option');
                if (val === '') {
                    let isActive = selectedSizes.length === 0;
                    $(this).toggleClass('border-gray-400 font-semibold bg-gray-50', isActive);
                    $(this).toggleClass('border-gray-100', !isActive);
                }
            });

            $('.mob-size-box').each(function () {
                let val = $(this).attr('data-size-option');
                let isActive = selectedSizes.includes(val);
                $(this).toggleClass('bg-black text-white border-black shadow-sm', isActive);
                $(this).toggleClass('bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100', !isActive);
            });

            if (selectedSizes.length > 0) {
                $('#mobSizeLabelSpan').text('Size: ' + selectedSizes.map(s => s.toUpperCase()).join(', '));
            } else {
                $('#mobSizeLabelSpan').text('Sizes');
            }
        }

        function fetchFilteredProducts(resetPage = true) {
            if (currentAjaxReq) {
                currentAjaxReq.abort();
            }

            if (resetPage) {
                page = 1;
                let url = new URL(window.location.href);
                url.searchParams.set('page', 1);
                window.history.pushState({}, '', url);
            }

            $('#productGrid').html(renderProductSkeletons(10));

            let formData = $('#filterForm').serializeArray();
            let currentSort = $('#desktopSortSelect').val() || new URLSearchParams(window.location.search).get('sort') || 'latest';

            let hasSort = false;
            formData.forEach(function (item) {
                if (item.name === 'sort') {
                    item.value = currentSort;
                    hasSort = true;
                }
            });
            if (!hasSort) {
                formData.push({name: 'sort', value: currentSort});
            }

            let serializedData = $.param(formData);

            let cleanParams = serializedData.split('&').filter(function (item) {
                let parts = item.split('=');
                return parts[1] !== "" && parts[1] !== undefined;
            }).join('&');

            let actionUrl = $('#filterForm').attr('action') || window.location.pathname;
            let fullQueryUrl = actionUrl + (cleanParams ? "?" + cleanParams : "");
            let ajaxData = cleanParams + "&page=" + page;

            currentAjaxReq = $.ajax({
                url: actionUrl,
                type: 'GET',
                data: ajaxData,
                dataType: 'json',
                success: function (response) {
                    currentAjaxReq = null;
                    $('#loading-spinner').addClass('hidden');

                    if ($.trim(response.products) === "") {
                        hasMorePages = false;
                        $('.no_products_found').addClass('hidden')
                        $('#productGrid').html(getNoProductsHtml());
                        $('#no-more-products').addClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: fullQueryUrl}, '', fullQueryUrl);
                    updateMobileFilterUI();
                },
                error: function (xhr, textStatus) {
                    if (textStatus !== 'abort') {
                        console.log(xhr.responseText);
                        $('#productGrid').html(
                            '<div class="col-span-full text-center py-10 text-red-500 text-sm font-medium">' +
                            'Something went wrong while loading products. Please check console for details.' +
                            '</div>'
                        );
                    }
                    $('#loading-spinner').addClass('hidden');
                    isLoading = false;
                }
            });
        }

        function applyPriceFilter(val) {
            let urlParams = new URLSearchParams(window.location.search);
            if (val) {
                urlParams.set('max_price', val);
            } else {
                urlParams.delete('max_price');
            }

            window.history.pushState({}, '', '?' + urlParams.toString());
            fetchFilteredProducts(true);
        }

        // --- Desktop Sort Change ---
        $('#desktopSortSelect').on('change', function (e) {
            e.preventDefault();

            let sortVal = $(this).val();
            let urlParams = new URLSearchParams(window.location.search);

            if (sortVal) {
                urlParams.set('sort', sortVal);
            } else {
                urlParams.delete('sort');
            }

            let newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.pushState({path: newUrl}, '', newUrl);

            fetchFilteredProducts(true);
        });

        // Mobile Sort Handler
        function selectMobSort(sortValue) {
            let urlParams = new URLSearchParams(window.location.search);
            if (sortValue) {
                urlParams.set('sort', sortValue);
            } else {
                urlParams.delete('sort');
            }

            window.history.pushState({}, '', '?' + urlParams.toString());
            closeAllMobDropdowns();

            $('#desktopSortSelect').val(sortValue);
            fetchFilteredProducts(true);
        }

        // --- Color / Size / Brand Selection Handlers (Multi-select, checkboxes ko toggle karte hain) ---
        function selectColorSwatch(color) {
            if (!color) {
                $('#filterForm input[name="color[]"]').prop('checked', false);
            } else {
                const checkbox = $('#filterForm input[name="color[]"][value="' + color + '"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
            fetchFilteredProducts(true);
        }

        function selectSizeBox(size) {
            if (!size) {
                $('#filterForm input[name="size[]"]').prop('checked', false);
            } else {
                const checkbox = $('#filterForm input[name="size[]"][value="' + size + '"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
            fetchFilteredProducts(true);
        }

        // FIX: yeh function pehle missing tha, isi wajah se mobile "Brands" filter kaam nahi kar raha tha
        function selectBrand(brandSlug) {
            if (!brandSlug) {
                $('#filterForm input[name="brand[]"]').prop('checked', false);
            } else {
                const checkbox = $('#filterForm input[name="brand[]"][value="' + brandSlug + '"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
            fetchFilteredProducts(true);
        }

        // --- 2. CATEGORY AJAX CLICK HANDLER ---
        function fetchCategoryProducts(event, targetUrl, categoryPath) {
            event.preventDefault();

            if (currentAjaxReq) {
                currentAjaxReq.abort();
            }

            page = 1;

            $('#productGrid').html(renderProductSkeletons(10));

            let categoryInput = $('#filterForm input[name="category"]');
            if (categoryInput.length) {
                categoryInput.val(categoryPath);
            } else {
                $('#filterForm').prepend('<input type="hidden" name="category" value="' + categoryPath + '">');
            }

            let form = $('#filterForm');
            let serializedData = form.serialize();

            let cleanParams = serializedData.split('&').filter(function (item) {
                let parts = item.split('=');
                return parts[1] !== "" && parts[1] !== undefined;
            }).join('&');

            let ajaxUrl = categoryPath ? "/collection/" + categoryPath : "/collection";
            let fullQueryUrl = ajaxUrl + (cleanParams ? "?" + cleanParams : "");
            let ajaxData = cleanParams + "&page=" + page;

            currentAjaxReq = $.ajax({
                url: ajaxUrl,
                type: "GET",
                data: ajaxData,
                dataType: 'json',
                success: function (response) {
                    currentAjaxReq = null;
                    $('#loading-spinner').addClass('hidden');

                    if ($.trim(response.products) === "") {
                        $('.no_products_found').addClass('hidden')
                        hasMorePages = false;
                        $('#productGrid').html(getNoProductsHtml());
                        $('#no-more-products').addClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        if (response.sidebar) $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: targetUrl}, '', targetUrl);
                    updateMobileFilterUI();
                },
                error: function (xhr, textStatus) {
                    if (textStatus !== 'abort') {
                        console.log(xhr.responseText);
                    }
                    $('#loading-spinner').addClass('hidden');
                }
            });
        }

        // Price Slider Event Listener
        let priceTimer;
        $(document).on('input', '#priceSlider', function () {
            updatePriceLabel(this.value);
        }).on('change', '#priceSlider', function () {
            clearTimeout(priceTimer);
            priceTimer = setTimeout(function () {
                fetchFilteredProducts(true);
            }, 300);
        });

        // Brand Checkboxes Change Event (desktop sidebar checkboxes)
        $(document).on('change', '#filterForm input[type="checkbox"]', function () {
            fetchFilteredProducts(true);
        });

        // --- 3. MOBILE DROPDOWN CONTROL ---
        function toggleMobDropdown(dropdownId) {
            const target = document.getElementById(dropdownId);
            const isOpen = !target.classList.contains('hidden');
            closeAllMobDropdowns();
            if (!isOpen) {
                target.classList.remove('hidden');
            }
        }

        function closeAllMobDropdowns() {
            document.querySelectorAll('.mob-dropdown').forEach(el => {
                el.classList.add('hidden');
            });
        }

        window.addEventListener('click', function (e) {
            if (!e.target.closest('.block.lg\\:hidden')) {
                closeAllMobDropdowns();
            }
        });

        // --- 4. WISHLIST AJAX HANDLER ---
        document.addEventListener("DOMContentLoaded", function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.stopTimer)
                }
            });

            const gridContainer = document.getElementById('productGrid');
            if (gridContainer) {
                gridContainer.addEventListener('submit', function (e) {
                    const form = e.target.closest('.wishlistForm');
                    if (!form) return;

                    e.preventDefault();
                    e.stopPropagation();

                    const actionUrl = form.action;
                    const formData = new FormData(form);
                    const icon = form.querySelector('.wishlistIcon');
                    const button = form.querySelector('.wishlistBtn');

                    if (button.classList.contains('processing')) return;

                    button.classList.add('processing');
                    button.disabled = true;

                    fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Network error');
                            return response.json();
                        })
                        .then(data => {
                            button.classList.remove('processing');
                            button.disabled = false;

                            // Agar user login nahi hai, to toast ki jagah login modal kholo
                            if (!data.status && data.message === 'Please login first') {
                                if (typeof openAuthModal === 'function') {
                                    openAuthModal();
                                }
                                return;
                            }

                            const isAdded = data.status === 'const isAdded = data.action === 'added' || (data.message && data.message.toLowerCase().includes('added'));
                            const isRemoved = data.action === 'removed' || (data.message && data.message.toLowerCase().includes('remove'));
                            if (isAdded) {
                                icon.className = 'wishlistIcon fa-heart text-lg transition duration-200 fa-solid text-red-500';
                                Toast.fire({icon: 'success', title: data.message || 'Added to wishlist!'});
                            } else if (isRemoved) {
                                icon.className = 'wishlistIcon fa-heart text-lg transition duration-200 fa-regular text-gray-500';
                                Toast.fire({icon: 'info', title: data.message || 'Removed from wishlist!'});
                            } else {
                                icon.classList.toggle('fa-solid');
                                icon.classList.toggle('text-red-500');
                                icon.classList.toggle('fa-regular');
                                icon.classList.toggle('text-gray-500');
                                Toast.fire({icon: 'success', title: 'Wishlist updated!'});
                            }
                        })
                        .catch(error => {
                            button.classList.remove('processing');
                            button.disabled = false;
                            console.error('Wishlist Error:', error);
                            Toast.fire({icon: 'error', title: 'Could not update wishlist. Try again.'});
                        });
                });
            }
        });

        // --- 5. INFINITE SCROLL PAGINATION ---
        function handleInfiniteScroll($container, isWindow) {
            if (!hasMorePages) return;

            let scrollTop = isWindow ? $(window).scrollTop() : $container.scrollTop();
            let innerHeight = isWindow ? $(window).height() : $container.innerHeight();
            let scrollHeight = isWindow ? $(document).height() : $container[0].scrollHeight;

            if (scrollTop + innerHeight >= scrollHeight - 300) {
                if (isLoading) return;

                isLoading = true;
                page++;

                let shimmerHtml = `
            @for($i = 0; $i < 5; $i++)
                <div class="product-shimmer bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">
                    <div class="bg-gray-200 h-50 xs:h-44 sm:h-60 2xl:h-57 md:h-52 lg:h-55 w-full animate-shimmer"></div>
                    <div class="px-2 py-2 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2 animate-shimmer"></div>
                            <div class="h-3 bg-gray-200 rounded w-full animate-shimmer"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/2 animate-shimmer"></div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="h-5 bg-gray-200 rounded w-1/3 animate-shimmer"></div>
                            <div class="h-5 bg-gray-200 rounded w-1/4 animate-shimmer"></div>
                        </div>
                    </div>
                </div>
@endfor
                `;
                $('#productGrid').append(shimmerHtml);

                let form = $('#filterForm');
                let formData = form.serialize() + "&page=" + page;
                let actionUrl = form.attr('action') || window.location.pathname;

                $.ajax({
                    url: actionUrl,
                    type: "GET",
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        $('.product-shimmer').remove();

                        if (!response.products || $.trim(response.products) === "") {
                            hasMorePages = false;
                            $('#no-more-products').removeClass('hidden');
                        } else {
                            $('#productGrid').append(response.products);
                            isLoading = false;
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        $('.product-shimmer').remove();
                        isLoading = false;
                    }
                });
            }
        }

        $('main').on('scroll', function () {
            handleInfiniteScroll($(this), false);
        });

        $(window).on('scroll', function () {
            handleInfiniteScroll(null, true);
        });

        // --- 6. RESET FILTERS ---
        function fetchResetFilters(event, baseUrl) {
            event.preventDefault();

            if (currentAjaxReq) {
                currentAjaxReq.abort();
            }

            $('#priceSlider').val(100000);
            $('#priceLabel').text('100,000');
            $('#filterForm input[name="min_price"]').val(0);
            $('#filterForm input[type="checkbox"]').prop('checked', false);
            $('#filterForm input[name="search"]').val('');

            page = 1;
            $('#productGrid').html(renderProductSkeletons(10));

            let ajaxUrl = baseUrl;

            currentAjaxReq = $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: { page: page },
                dataType: 'json',
                success: function (response) {
                    currentAjaxReq = null;
                    if ($.trim(response.products) === "") {
                        hasMorePages = false;
                        $('#productGrid').html(getNoProductsHtml());
                        $('#no-more-products').addClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        if (response.sidebar) $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: baseUrl}, '', baseUrl);
                    updateMobileFilterUI();
                },
                error: function (xhr, textStatus) {
                    if (textStatus !== 'abort') {
                        console.log(xhr.responseText);
                    }
                }
            });
        }
    </script>
@endpush