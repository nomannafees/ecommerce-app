@extends('frontend.layouts.app')

@section('content')

    <!-- FILTER FORM (PRICE, COLOR, SIZE, BRANDS) -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mx-auto px-3 sm:px-6 md:px-7 py-2 sm:pt-4 sm:pb-2">

        <div class="text-center mt-2 lg:mt-4 px-4 lg:px-10">
            <h1 class="text-2xl lg:text-4xl font-bold text-gray-900">
                🛍️ Explore Our Products
            </h1>
            <p class="text-xs lg:text-base text-gray-500 mt-2 lg:mt-3 max-w-xl lg:max-w-2xl mx-auto leading-relaxed">
                Discover the best products from multiple categories.
                Use filters to find exactly what you need — by price, color, size, brand, and category.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 py-6">

        @include('frontend.partials.category-sidebar')

        <!-- RIGHT SIDE (Products Grid - 5 columns) -->
            <div class="w-full lg:w-4/5 container mx-auto ">

                <!-- DESKTOP SORT BAR -->
                <div
                    class="mb-4 hidden lg:flex flex-row gap-4 items-center justify-between bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 shadow-sm">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-700">Products</h2>

                    <div class="flex items-center gap-3">
                        <div class="w-48 relative h-[40px]" x-data="{
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

                // Yahan aap apna AJAX function call karein jo page reload kiye baghair products fetch karta hai
                // Misal ke tor par agar aapke paas fetch products ka function hai:
                if (typeof fetchFilteredProducts === 'function') {
                    // Agar URL update karna ho bina reload ke:
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', value);
                    window.history.pushState({}, '', url);
                    fetchFilteredProducts(); // Products refresh karne ka function
                } else {
                    // Fallback agar direct URL par request bhejni ho
                    window.location.href = window.location.pathname + '?' + new URLSearchParams(new FormData(document.getElementById('searchSortForm') || document.createElement('form'))).toString() + '&sort=' + value;
                }
            }
        }">
                            <!-- Trigger Button -->
                            <button type="button"
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="w-full h-full bg-white border border-gray-300 rounded-full pl-4 pr-10 focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-400 text-sm cursor-pointer font-medium text-gray-700 flex items-center justify-between shadow-sm">
                                <span class="truncate leading-none"
                                      x-text="sortLabels[currentSort] || 'Latest Products'"></span>
                                <span
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"></i>
                </span>
                            </button>

                            <!-- Custom Dropdown Options (Without Page Reload) -->
                            <div x-show="open"
                                 style="display: none;"
                                 class="absolute left-0 right-0 top-full bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden z-50 py-1">

                                <button type="button"
                                        @click="changeSort('latest')"
                                        class="w-full text-left block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition cursor-pointer"
                                        :class="currentSort == 'latest' ? 'bg-gray-50 font-semibold' : ''">
                                    Latest Products
                                </button>

                                <button type="button"
                                        @click="changeSort('price_low_high')"
                                        class="w-full text-left block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition cursor-pointer"
                                        :class="currentSort == 'price_low_high' ? 'bg-gray-50 font-semibold' : ''">
                                    Price: Low to High
                                </button>

                                <button type="button"
                                        @click="changeSort('price_high_low')"
                                        class="w-full text-left block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition cursor-pointer"
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
                            <span>{{ request('brand') ? 'Brand: '.ucfirst(request('brand')) : 'Brands' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobColorDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span>{{ request('color') ? 'Color: '.ucfirst(request('color')) : 'Colors' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <button type="button" onclick="toggleMobDropdown('mobSizeDropdown')"
                                class="flex-shrink-0 h-[30px] bg-white border border-gray-200 rounded-full px-3 text-xs font-medium text-gray-700 flex items-center gap-2">
                            <span>{{ request('size') ? 'Size: '.strtoupper(request('size')) : 'Sizes' }}</span>
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
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ !request('brand') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Brands
                            </button>
                            @foreach($availableBrands as $brand)
                                <button type="button" onclick="selectBrand('{{ $brand->slug }}')"
                                        class="w-full text-left block px-2.5 py-1.5 rounded border {{ request('brand') == $brand->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
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
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ !request('color') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Colors
                            </button>
                            <div class="flex flex-wrap gap-2 py-1">
                                @foreach($availableColors as $colorName)
                                    @php
                                        $cleanColor = strtolower(trim($colorName));
                                        $isSelected = request('color') == $cleanColor;
                                        $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';
                                    @endphp
                                    <button type="button" onclick="selectColorSwatch('{{ $cleanColor }}')"
                                            title="{{ ucfirst($colorName) }}"
                                            class="w-7 h-7 rounded-full cursor-pointer transition-all duration-200 flex items-center justify-center shadow-sm {{ $isSelected ? 'ring-2 ring-offset-1 ring-black scale-110' : 'opacity-90 hover:opacity-100' }}"
                                            style="{{ $inlineBg }}">
                                        @if($isSelected) <i
                                            class="fa-solid fa-check text-[10px] {{ in_array($cleanColor, ['white', 'yellow', 'lightgray']) ? 'text-black' : 'text-white' }}"></i> @endif
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
                                    class="w-full text-left block px-2.5 py-1.5 rounded border {{ !request('size') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 hover:bg-gray-100 transition">
                                All Sizes
                            </button>
                            <div class="flex flex-wrap gap-1.5 py-1">
                                @foreach($availableSizes as $sizeName)
                                    @php
                                        $cleanSize = trim($sizeName);
                                        $isSizeSelected = request('size') == $cleanSize;
                                    @endphp
                                    <button type="button" onclick="selectSizeBox('{{ $cleanSize }}')"
                                            class="px-2.5 py-1 text-xs rounded border transition font-medium {{ $isSizeSelected ? 'bg-black text-white border-black shadow-sm' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                                        {{ strtoupper($cleanSize) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS GRID (5 columns on lg/xl) -->
                <div id="productGrid"
                     class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-5 sm:gap-3">
                    @include('frontend.partials.category-product-cards', ['records' => $records, 'wishlistProductIds' => $wishlistProductIds])
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
        // --- 1. PRICE & FILTER FUNCTIONS ---
        function updatePriceLabel(value) {
            document.getElementById('priceLabel').innerText = Number(value).toLocaleString();
        }

        // AJAX Filter Trigger Function (Ab yeh sorting aur baqi sab filters ko ikatha serialize karega)
        function fetchFilteredProducts(resetPage = true) {
            if (resetPage) {
                page = 1;
                let url = new URL(window.location.href);
                url.searchParams.set('page', 1);
                window.history.pushState({}, '', url);
            }

            $('#productGrid').html('<div class="col-span-full text-center py-16"><i class="fa-solid fa-spinner fa-spin text-3xl text-emerald-600"></i></div>');

            // 1. Filter form ka data lo
            let formData = $('#filterForm').serializeArray();

            // 2. Desktop ya mobile sort ki current value ko bhi lazmi shamil karo
            let currentSort = $('#desktopSortSelect').val() || new URLSearchParams(window.location.search).get('sort') || 'latest';

            // Check karo agar formData mein pehle se sort hai toh theek, warna add kar do
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

            // Serialize data ko string banao
            let serializedData = $.param(formData);

            let cleanParams = serializedData.split('&').filter(function (item) {
                let parts = item.split('=');
                return parts[1] !== "" && parts[1] !== undefined;
            }).join('&');

            let actionUrl = $('#filterForm').attr('action') || window.location.pathname;
            let fullQueryUrl = actionUrl + (cleanParams ? "?" + cleanParams : "");
            let ajaxData = cleanParams + "&page=" + page;

            $.ajax({
                url: actionUrl,
                type: 'GET',
                data: ajaxData,
                dataType: 'json',
                success: function (response) {
                    $('#loading-spinner').addClass('hidden');

                    if ($.trim(response.products) === "") {
                        hasMorePages = false;
                        $('#productGrid').html('<div class="col-span-full text-center py-16 text-gray-500 font-medium">No Products Found</div>');
                        $('#no-more-products').removeClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: fullQueryUrl}, '', fullQueryUrl);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    $('#loading-spinner').addClass('hidden');
                }
            });
        }

        // Price Slider Mouse Up Handler (Jahan chhodenge wahi filter apply ho jayega)
        function applyPriceFilter(val) {
            let urlParams = new URLSearchParams(window.location.search);
            if (val) {
                urlParams.set('max_price', val);
            } else {
                urlParams.delete('max_price');
            }

            // Browser URL update karein bina page reload kiye
            window.history.pushState({}, '', '?' + urlParams.toString());

            // Products fetch karne wala function chalayein
            fetchFilteredProducts(true);
        }

        // --- Single Desktop Sort Change Event ---
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

        // Mobile Sort Option Click Handler
        function selectMobSort(sortValue) {
            let urlParams = new URLSearchParams(window.location.search);
            if (sortValue) {
                urlParams.set('sort', sortValue);
            } else {
                urlParams.delete('sort');
            }

            window.history.pushState({}, '', '?' + urlParams.toString());
            closeAllMobDropdowns();

            // Desktop select dropdown ko bhi sync kar do
            $('#desktopSortSelect').val(sortValue);

            fetchFilteredProducts(true);
        }

        // Color, Size aur Brand ke selection handlers
        function selectColorSwatch(color) {
            const input = document.getElementById('selectedColorInput');
            input.value = (input.value === color) ? '' : color;
            fetchFilteredProducts(true);
        }

        function selectSizeBox(size) {
            const input = document.getElementById('selectedSizeInput');
            input.value = (input.value === size) ? '' : size;
            fetchFilteredProducts(true);
        }

        function selectBrand(brandSlug) {
            const input = document.getElementById('selectedBrandInput');
            input.value = (input.value === brandSlug) ? '' : brandSlug;
            fetchFilteredProducts(true);
        }

        // --- 2. CATEGORY AJAX CLICK HANDLER ---
        function fetchCategoryProducts(event, targetUrl, categoryPath) {
            event.preventDefault();

            page = 1;
            $('#productGrid').html('<div class="col-span-full text-center py-16"><i class="fa-solid fa-spinner fa-spin text-3xl text-emerald-600"></i></div>');

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

            $.ajax({
                url: ajaxUrl,
                type: "GET",
                data: ajaxData,
                dataType: 'json',
                success: function (response) {
                    $('#loading-spinner').addClass('hidden');

                    if ($.trim(response.products) === "") {
                        hasMorePages = false;
                        $('#productGrid').html('<div class="col-span-full text-center py-16 text-gray-500 font-medium">No Products Found</div>');
                        $('#no-more-products').removeClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: targetUrl}, '', targetUrl);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    $('#loading-spinner').addClass('hidden');
                }
            });
        }

        // Price Slider Change Event
        let priceTimer;
        $('#priceSlider').on('input', function () {
            updatePriceLabel(this.value);
        }).on('change', function () {
            clearTimeout(priceTimer);
            priceTimer = setTimeout(function () {
                fetchFilteredProducts(true);
            }, 300);
        });

        // Brand Checkboxes Change Event
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
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
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

                            const isAdded = data.status === 'added' || data.status === true || (data.message && data.message.toLowerCase().includes('added'));
                            const isRemoved = data.status === 'removed' || data.status === false || (data.message && data.message.toLowerCase().includes('remove'));

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

        // --- 5. INFINITE SCROLL PAGINATION (FIXED WITH SHIMMER) ---
        let page = 1;
        let hasMorePages = {{ $records->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;

        function handleInfiniteScroll($container, isWindow) {
            if (!hasMorePages) return;

            let scrollTop = isWindow ? $(window).scrollTop() : $container.scrollTop();
            let innerHeight = isWindow ? $(window).height() : $container.innerHeight();
            let scrollHeight = isWindow ? $(document).height() : $container[0].scrollHeight;

            if (scrollTop + innerHeight >= scrollHeight - 300) {
                if (isLoading) return;

                isLoading = true;
                page++;

                // Shimmer Effect HTML (Spinner ki jagah grid mein append hoga)
                let shimmerHtml = `
            @for($i = 0; $i < 5; $i++)
                <div class="product-shimmer bg-white rounded-md sm:rounded-lg shadow-xs border border-gray-200 overflow-hidden flex flex-col h-full w-full animate-pulse">
                    <div class="bg-gray-200 h-50 xs:h-44 sm:h-60 2xl:h-57 md:h-52 lg:h-55 w-full"></div>
                    <div class="px-2 py-2 flex-grow flex flex-col justify-between gap-2">
                        <div>
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-full"></div>
                        </div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        <div class="flex items-center justify-between mt-2">
                            <div class="h-5 bg-gray-200 rounded w-1/3"></div>
                            <div class="h-5 bg-gray-200 rounded w-1/4"></div>
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
                        // Response aate hi shimmer cards hata dein
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
                        // Error aane par bhi shimmer hata dein
                        $('.product-shimmer').remove();
                        isLoading = false;
                    }
                });
            }
        }

        // Agar aapke layout mein <main> khud scrollable hai (overflow-y-auto + fixed height)
        $('main').on('scroll', function () {
            handleInfiniteScroll($(this), false);
        });

        // Fallback: agar page normal window scroll use karta hai (zyada common case)
        $(window).on('scroll', function () {
            handleInfiniteScroll(null, true);
        });

        function fetchResetFilters(event, baseUrl) {
            event.preventDefault();

            // Filters ko visually bhi reset karo
            $('#selectedColorInput').val('');
            $('#selectedSizeInput').val('');
            $('#selectedBrandInput').val('');
            $('#priceSlider').val(100000);
            $('#priceLabel').text('100,000');
            $('#filterForm input[name="min_price"]').val(0);
            $('#filterForm input[type="checkbox"]').prop('checked', false);

            page = 1;
            // Reset par bhi shimmer ya loader show kar sakte hain
            $('#productGrid').html('<div class="col-span-full text-center py-16"><i class="fa-solid fa-spinner fa-spin text-3xl text-emerald-600"></i></div>');

            let category = $('#filterForm input[name="category"]').val() || '';
            let search = $('#filterForm input[name="search"]').val() || '';
            let sort = $('#filterForm input[name="sort"]').val() || '';

            let params = {page: page};
            if (category) params.category = category;
            if (search) params.search = search;
            if (sort) params.sort = sort;

            let ajaxUrl = category ? "/collection/" + category : "/collection";

            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function (response) {
                    if ($.trim(response.products) === "") {
                        hasMorePages = false;
                        $('#productGrid').html('<div class="col-span-full text-center py-16 text-gray-500 font-medium">No Products Found</div>');
                        $('#no-more-products').removeClass('hidden');
                    } else {
                        $('#productGrid').html(response.products);
                        $('aside').replaceWith(response.sidebar);
                        hasMorePages = true;
                        isLoading = false;
                        $('#no-more-products').addClass('hidden');
                    }

                    window.history.pushState({path: baseUrl}, '', baseUrl);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endpush
