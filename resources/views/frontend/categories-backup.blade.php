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

            <!-- LEFT SIDEBAR (Desktop Only) -->
            <aside class="w-full lg:w-1/5 bg-white p-5 rounded-xl shadow h-fit hidden lg:block overflow-visible" x-data="{ showAllCategories: false, categoryLimit: 6 }">
            @php
                // 1. Helper function to build category path
                $buildCategoryPath = function ($categoryItem) {
                    $slugs = [];
                    $item = $categoryItem;
                    while ($item) {
                        array_unshift($slugs, $item->slug);
                        $item = $item->parent;
                    }
                    return implode('/', $slugs);
                };

                // 2. Display categories logic
                if (!isset($currentCategory) || !$currentCategory) {
                    $displayCategories = $categories->where('parent_id', 0);
                } elseif ($currentCategory->children && $currentCategory->children->count() > 0) {
                    $displayCategories = $currentCategory->children;
                } elseif ($currentCategory->parent) {
                    $displayCategories = $currentCategory->parent->children ?? collect();
                } else {
                    $displayCategories = collect();
                }
            @endphp

            <!-- Form action -->
                <form method="GET" action="{{ request()->url() }}" id="filterForm" onsubmit="return cleanEmptyInputs(this);" class="space-y-4">

                    @if(request()->route('category'))
                        <input type="hidden" name="category" value="{{ request()->route('category') }}">
                    @endif

                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <input type="hidden" name="color" id="selectedColorInput" value="{{ request('color') }}">
                    <input type="hidden" name="size" id="selectedSizeInput" value="{{ request('size') }}">
                    <input type="hidden" name="brand" id="selectedBrandInput" value="{{ request('brand') }}">

                    {{-- ============================================================
                         CATEGORIES HEADER WITH CLEAR FILTERS
                    ============================================================= --}}
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="font-bold text-sm tracking-tight">
                            <span>Categories</span>
                        </h2>

                        {{-- Jab koi filter active ho ga tabhi yeh Clear link samne show ho ga --}}
                        @if(request()->hasAny(['color', 'size', 'brand', 'max_price', 'min_price']))
                            <a href="{{ request()->url() }}"
                               class="text-[11px] font-semibold text-red-600 hover:text-red-800 transition underline cursor-pointer">
                                Clear Filters
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1.5 pb-2">
                        {{-- BACK TO PARENT OR PREVIOUS CATEGORY --}}
                        @if(isset($currentCategory) && $currentCategory)
                            @if($currentCategory->parent)
                                @php
                                    $parentCategory = $currentCategory->parent;
                                    $backSlug = $buildCategoryPath($parentCategory);
                                @endphp
                                <a href="{{ url('/collection/' . $backSlug) }}"
                                   class="text-xs text-emerald-600 font-semibold mb-1 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back to {{ $parentCategory->name }}
                                </a>
                            @else
                                {{-- Jab user kisi Main Category (jaise Men's Fashion) par ho, toh root categories par wapas jaane ke liye --}}
                                <a href="{{ url('/collection') }}"
                                   class="text-xs text-emerald-600 font-semibold mb-1 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    All Categories
                                </a>
                            @endif
                        @endif

                        {{-- CATEGORY LIST --}}
                        @if(isset($displayCategories) && $displayCategories->count() > 0)
                            @foreach($displayCategories as $index => $cat)
                                @php
                                    $categoryPath = $buildCategoryPath($cat);
                                    $currentUrlCategory = trim(request()->route('category') ?? '', '/');
                                    $isCategorySelected = $currentUrlCategory === trim($categoryPath, '/');

                                    $activeQueryParams = array_filter(request()->except('category'));
                                    $queryString = !empty($activeQueryParams) ? '?' . http_build_query($activeQueryParams) : '';
                                @endphp

                                <a href="{{ url('/collection/' . $categoryPath) }}{{ $queryString }}"
                                   x-show="showAllCategories || {{ $index }} < categoryLimit"
                                   class="text-xs sm:text-sm text-gray-700 hover:text-black py-1 px-2 rounded transition {{ $isCategorySelected ? 'bg-gray-100 font-bold text-black' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- SEE MORE / SEE LESS BUTTON (Categories ke liye) --}}
                    @if(isset($displayCategories) && $displayCategories->count() > 6)
                        <button type="button"
                                @click="showAllCategories = !showAllCategories"
                                class="mt-1 w-full py-1.5 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-md text-xs font-semibold text-gray-700 flex items-center justify-center gap-2 transition-all duration-200 shadow-xs cursor-pointer">
                            <span x-text="showAllCategories ? 'See Less' : 'See More ({{ $displayCategories->count() - 6 }})'"></span>
                            <i class="fa-solid text-[10px] text-gray-500 transition-transform duration-200" :class="showAllCategories ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                    @endif

                <!-- Price Range -->
                    <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                        <span>Price</span>
                    </h2>
                    <div class="space-y-2 my-2 pb-3 border-b border-gray-100">
                        <input type="range"
                               id="priceSlider"
                               name="max_price"
                               min="0"
                               max="100000"
                               step="500"
                               value="{{ request('max_price', 100000) }}"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black"
                               oninput="updatePriceLabel(this.value)"
                               onchange="document.getElementById('filterForm').submit();">

                        <input type="hidden" name="min_price" value="{{ request('min_price', 0) }}">

                        <div class="flex justify-between text-xs text-gray-500 font-medium">
                            <span>Rs 0</span>
                            <span class="text-black font-bold">Max: Rs <span id="priceLabel">{{ number_format(request('max_price', 100000)) }}</span></span>
                        </div>
                    </div>

                    <!-- Color Filter -->
                    @if(isset($availableColors) && count($availableColors) > 0)
                        <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                            <span>Color</span>
                        </h2>
                        <div class="flex flex-wrap gap-2.5 my-2 pb-3 border-b border-gray-100">
                            @foreach($availableColors as $colorName)
                                @php
                                    $cleanColor = strtolower(trim($colorName));
                                    $isSelected = request('color') == $cleanColor;
                                    $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';
                                @endphp
                                <button type="button" onclick="selectColorSwatch('{{ $cleanColor }}')"
                                        title="{{ ucfirst($colorName) }}"
                                        class="w-6 h-6 sm:w-7 sm:h-7 rounded-full cursor-pointer transition-all duration-200 transform hover:scale-110 relative flex items-center justify-center shadow-sm {{ $isSelected ? 'ring-2 ring-offset-2 ring-black scale-110' : 'opacity-80 hover:opacity-100' }}"
                                        style="{{ $inlineBg }}">
                                    @if($isSelected)
                                        <i class="fa-solid fa-check text-[10px] sm:text-xs {{ in_array($cleanColor, ['white', 'yellow', 'lightgray']) ? 'text-black' : 'text-white' }}"></i>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endif

                <!-- Size Filter -->
                    @if(isset($availableSizes) && count($availableSizes) > 0)
                        <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                            <span>Size</span>
                        </h2>
                        <div class="flex flex-wrap gap-2 my-2 pb-3 border-b border-gray-100">
                            @foreach($availableSizes as $sizeName)
                                @php
                                    $cleanSize = trim($sizeName);
                                    $isSizeSelected = request('size') == $cleanSize;
                                @endphp
                                <button type="button" onclick="selectSizeBox('{{ $cleanSize }}')"
                                        class="px-2.5 py-1 cursor-pointer text-xs sm:text-sm rounded border transition-all duration-200 font-medium {{ $isSizeSelected ? 'bg-black text-white border-black shadow' : 'bg-gray-50 text-gray-700 border-gray-300 hover:border-black' }}">
                                    {{ strtoupper($cleanSize) }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                <!-- Brands Filter -->
                    @if(isset($availableBrands) && count($availableBrands) > 0)
                        <div x-data="{ showAllBrands: false, brandLimit: 6 }">
                            <h2 class="font-bold text-sm mt-4 mb-2 tracking-tight flex items-center gap-2">
                                <span>Brands</span>
                            </h2>

                            <div class="flex flex-col gap-2 pb-1">
                                @foreach($availableBrands as $index =>$brand)
                                    @php
                                        $isBrandSelected = request('brand') == $brand->slug;
                                    @endphp
                                    <label x-show="showAllBrands || {{ $index }} < brandLimit"
                                           class="flex items-center gap-2.5 cursor-pointer group text-xs sm:text-sm text-gray-700 hover:text-black">
                                        <input type="checkbox"
                                               onchange="selectBrand('{{ $brand->slug }}')"
                                               {{ $isBrandSelected ? 'checked' : '' }}
                                               class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black accent-black">
                                        <span class="{{ $isBrandSelected ? 'font-semibold text-black' : '' }}">
                                {{ ucfirst($brand->name) }}
                            </span>
                                    </label>
                                @endforeach
                            </div>

                            @if(count($availableBrands) > 6)
                                <button type="button"
                                        @click="showAllBrands = !showAllBrands"
                                        class="mt-2 w-full py-1.5 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-md text-xs font-semibold text-gray-700 flex items-center justify-center gap-2 transition-all duration-200 shadow-xs cursor-pointer">
                                    <span x-text="showAllBrands ? 'See Less' : 'See More'"></span>
                                    <i class="fa-solid text-[10px] text-gray-500 transition-transform duration-200" :class="showAllBrands ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                </button>
                            @endif
                        </div>
                    @endif

                </form>
            </aside>

            <script>
                function updatePriceLabel(val) {
                    document.getElementById('priceLabel').innerText = Number(val).toLocaleString();
                }

                function selectColorSwatch(color) {
                    let input = document.getElementById('selectedColorInput');
                    input.value = (input.value === color) ? '' : color;
                    document.getElementById('filterForm').submit();
                }

                function selectSizeBox(size) {
                    let input = document.getElementById('selectedSizeInput');
                    input.value = (input.value === size) ? '' : size;
                    document.getElementById('filterForm').submit();
                }

                function selectBrand(brandSlug) {
                    let input = document.getElementById('selectedBrandInput');
                    input.value = (input.value === brandSlug) ? '' : brandSlug;
                    document.getElementById('filterForm').submit();
                }

                // Yeh function empty inputs ko disable kar dega taake URL mein ?color=&size= jaisi fazool cheezein na jayein
                function cleanEmptyInputs(form) {
                    let inputs = form.elements;
                    for (let i = 0; i < inputs.length; i++) {
                        if (inputs[i].value === "" || inputs[i].value === null) {
                            inputs[i].disabled = true;
                        }
                    }
                    return true;
                }
            </script>

            <!-- JavaScript Helper Functions (Agar pehle se defined nahi hain to inhein add kar dein) -->
            <script>
                function updatePriceLabel(val) {
                    document.getElementById('priceLabel').innerText = Number(val).toLocaleString();
                }

                function selectColorSwatch(color) {
                    let input = document.getElementById('selectedColorInput');
                    // Agar same color dobara click ho to toggle off kar dein
                    input.value = (input.value === color) ? '' : color;
                    document.getElementById('filterForm').submit();
                }

                function selectSizeBox(size) {
                    let input = document.getElementById('selectedSizeInput');
                    input.value = (input.value === size) ? '' : size;
                    document.getElementById('filterForm').submit();
                }

                function selectBrand(brandSlug) {
                    let input = document.getElementById('selectedBrandInput');
                    input.value = (input.value === brandSlug) ? '' : brandSlug;
                    document.getElementById('filterForm').submit();
                }
            </script>

            <!-- RIGHT SIDE (Products Grid - 5 columns) -->
            <div class="w-full lg:w-4/5 container mx-auto ">

                <!-- DESKTOP SORT BAR -->
                <form method="GET" action="{{ request()->url() }}" id="searchSortForm"
                      class="mb-4 hidden lg:flex flex-row gap-4 items-center justify-between bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 shadow-sm">

                    @if(request()->route('category')) <input type="hidden" name="category" value="{{ request()->route('category') }}"> @endif
                    @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                    @if(request('color')) <input type="hidden" name="color" value="{{ request('color') }}"> @endif
                    @if(request('size')) <input type="hidden" name="size" value="{{ request('size') }}"> @endif
                    @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                    <h2 class="text-base sm:text-lg font-semibold text-gray-700">Products</h2>

                    <div class="flex items-center gap-3">
                        <div class="w-48 relative h-[40px]">
                            <select name="sort"
                                    onchange="document.getElementById('searchSortForm').submit();"
                                    class="w-full h-full bg-white border border-gray-300 rounded-full pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-black text-sm cursor-pointer font-medium text-gray-700 appearance-none block leading-none">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Products</option>
                                <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- MOBILE HORIZONTAL SCROLLING FILTER BUTTONS -->
                <div class="mb-3 block lg:hidden bg-gray-50 border border-gray-200 rounded-xl p-3 shadow-sm space-y-2 relative">
                    <div class="flex justify-between items-center px-1">
                        <h2 class="text-sm font-semibold text-gray-700">Filters & Sort</h2>
                        @if(request()->has('min_price') || request()->has('max_price') || request()->has('color') || request()->has('size') || request()->has('brand') || request()->has('search') || request()->has('sort') || request()->has('category'))
                            <a href="{{ request()->url() }}" class="text-xs text-red-500 underline font-medium hover:text-red-700 transition">
                                Clear Filter
                            </a>
                        @else
                            <span class="text-[11px] text-gray-400">Tap to filter &rarr;</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 overflow-x-auto -mb-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
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
                    <div id="mobSortDropdown" class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Sort Products</h3>
                            <button type="button" onclick="closeAllMobDropdowns()" class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <form method="GET" action="{{ request()->url() }}" class="space-y-1">
                            @if(request()->route('category')) <input type="hidden" name="category" value="{{ request()->route('category') }}"> @endif
                            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                            @if(request('color')) <input type="hidden" name="color" value="{{ request('color') }}"> @endif
                            @if(request('size')) <input type="hidden" name="size" value="{{ request('size') }}"> @endif
                            @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                            <label class="block px-2.5 py-1.5 rounded border {{ request('sort') == 'latest' ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 cursor-pointer">
                                <input type="radio" name="sort" value="latest" {{ request('sort') == 'latest' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden"> Latest Products
                            </label>
                            <label class="block px-2.5 py-1.5 rounded border {{ request('sort') == 'price_low_high' ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 cursor-pointer">
                                <input type="radio" name="sort" value="price_low_high" {{ request('sort') == 'price_low_high' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden"> Price: Low to High
                            </label>
                            <label class="block px-2.5 py-1.5 rounded border {{ request('sort') == 'price_high_low' ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700 cursor-pointer">
                                <input type="radio" name="sort" value="price_high_low" {{ request('sort') == 'price_high_low' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden"> Price: High to Low
                            </label>
                        </form>
                    </div>

                    <div id="mobCategoryDropdown" class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Category</h3>
                            <button type="button" onclick="closeAllMobDropdowns()" class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ request()->url() }}?{{ http_build_query(request()->except('category')) }}" class="block px-2.5 py-1.5 rounded border {{ !request()->route('category') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Categories</a>
                            @foreach($categories as $cat)
                                @php
                                    $activeQueryParams = array_filter(request()->except('category'));
                                    $catQueryString = !empty($activeQueryParams) ? '?' . http_build_query($activeQueryParams) : '';
                                @endphp
                                <a href="{{ url('/product/' . $cat->slug) }}{{ $catQueryString }}" class="block px-2.5 py-1.5 rounded border {{ request()->route('category') == $cat->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div id="mobBrandDropdown" class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Brand</h3>
                            <button type="button" onclick="closeAllMobDropdowns()" class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1">
                            @php
                                $brandParams = request()->except('brand');
                                if(request()->route('category')) { $brandParams['category'] = request()->route('category'); }
                                $brandQueryString = !empty(array_filter($brandParams)) ? '?' . http_build_query(array_filter($brandParams)) : '';
                            @endphp
                            <a href="{{ request()->url() }}{{ $brandQueryString }}" class="block px-2.5 py-1.5 rounded border {{ !request('brand') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Brands</a>
                            @foreach($availableBrands as $brand)
                                @php
                                    $currentParams = array_merge(request()->query(), ['brand' => $brand->slug]);
                                    if(request()->route('category')) { $currentParams['category'] = request()->route('category'); }
                                @endphp
                                <a href="{{ request()->url() }}?{{ http_build_query(array_filter($currentParams)) }}" class="block px-2.5 py-1.5 rounded border {{ request('brand') == $brand->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">
                                    {{ ucfirst($brand->name) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div id="mobColorDropdown" class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Color</h3>
                            <button type="button" onclick="closeAllMobDropdowns()" class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1.5">
                            @php
                                $colorParams = request()->except('color');
                                if(request()->route('category')) { $colorParams['category'] = request()->route('category'); }
                                $colorQueryString = !empty(array_filter($colorParams)) ? '?' . http_build_query(array_filter($colorParams)) : '';
                            @endphp
                            <a href="{{ request()->url() }}{{ $colorQueryString }}" class="block px-2.5 py-1.5 rounded border {{ !request('color') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Colors</a>
                            <div class="flex flex-wrap gap-2 py-1">
                                @foreach($availableColors as $colorName)
                                    @php
                                        $cleanColor = strtolower(trim($colorName));
                                        $isSelected = request('color') == $cleanColor;
                                        $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';

                                        $currentColorParams = array_merge(request()->query(), ['color' => $cleanColor]);
                                        if(request()->route('category')) { $currentColorParams['category'] = request()->route('category'); }
                                    @endphp
                                    <a href="{{ request()->url() }}?{{ http_build_query(array_filter($currentColorParams)) }}"
                                       title="{{ ucfirst($colorName) }}"
                                       class="w-7 h-7 rounded-full cursor-pointer transition-all duration-200 flex items-center justify-center shadow-sm {{ $isSelected ? 'ring-1 ring-offset-1 ring-gray-600 scale-110' : 'opacity-90 hover:opacity-100' }}"
                                       style="{{ $inlineBg }}">
                                        @if($isSelected) <i class="fa-solid fa-check text-[10px] {{ in_array($cleanColor, ['white', 'yellow', 'lightgray']) ? 'text-black' : 'text-white' }}"></i> @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div id="mobSizeDropdown" class="mob-dropdown hidden absolute left-3 right-3 top-full mt-2 bg-white rounded-xl p-3 shadow-xl border border-gray-200 z-40 max-h-60 overflow-y-auto">
                        <div class="flex justify-between items-center mb-2.5 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-gray-800 text-xs">Select Size</h3>
                            <button type="button" onclick="closeAllMobDropdowns()" class="text-gray-400 hover:text-black"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="space-y-1.5">
                            @php
                                $sizeParams = request()->except('size');
                                if(request()->route('category')) { $sizeParams['category'] = request()->route('category'); }
                                $sizeQueryString = !empty(array_filter($sizeParams)) ? '?' . http_build_query(array_filter($sizeParams)) : '';
                            @endphp
                            <a href="{{ request()->url() }}{{ $sizeQueryString }}" class="block px-2.5 py-1.5 rounded border {{ !request('size') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Sizes</a>
                            <div class="flex flex-wrap gap-1.5 py-1">
                                @foreach($availableSizes as $sizeName)
                                    @php
                                        $cleanSize = trim($sizeName);
                                        $isSizeSelected = request('size') == $cleanSize;

                                        $currentSizeParams = array_merge(request()->query(), ['size' => $cleanSize]);
                                        if(request()->route('category')) { $currentSizeParams['category'] = request()->route('category'); }
                                    @endphp
                                    <a href="{{ request()->url() }}?{{ http_build_query(array_filter($currentSizeParams)) }}"
                                       class="px-2.5 py-1 text-xs rounded border transition font-medium {{ $isSizeSelected ? 'bg-gray-100 text-black border-gray-400 shadow-sm' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ strtoupper($cleanSize) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS GRID (5 columns on lg/xl) -->
                <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-5 sm:gap-3">
                    @include('frontend.partials.category-product-cards', ['records' => $records, 'wishlistProductIds' => $wishlistProductIds])
                </div>

                <!-- LOADING SPINNER -->
                <div id="loading-spinner" class="text-center py-6 hidden">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-emerald-600"></i>
                    <p class="text-xs text-gray-500 mt-1">Loading more products...</p>
                </div>

                <!-- NO MORE PRODUCTS BUTTON -->
                <div id="no-more-products" class="text-center my-6 hidden">
    <span class="inline-flex items-center gap-2 bg-gray-700 text-white text-xs sm:text-sm font-medium px-5 py-2.5 rounded-md shadow-md cursor-default">
        <i class="fa-solid fa-circle-check text-emerald-400"></i> No More Products
    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updatePriceLabel(value) {
            document.getElementById('priceLabel').innerText = Number(value).toLocaleString();
        }

        function selectColorSwatch(color) {
            const input = document.getElementById('selectedColorInput');
            input.value = (input.value === color) ? '' : color;
            document.getElementById('filterForm').submit();
        }

        function selectSizeBox(size) {
            const input = document.getElementById('selectedSizeInput');
            input.value = (input.value === size) ? '' : size;
            document.getElementById('filterForm').submit();
        }

        function selectBrand(brandSlug) {
            const input = document.getElementById('selectedBrandInput');
            input.value = (input.value === brandSlug) ? '' : brandSlug;
            document.getElementById('filterForm').submit();
        }

        // Mobile Dropdown Control (Daraz Style)
        function toggleMobDropdown(dropdownId) {
            const target = document.getElementById(dropdownId);
            const isOpen = !target.classList.contains('hidden');

            // Close all first
            closeAllMobDropdowns();

            // Toggle target
            if (!isOpen) {
                target.classList.remove('hidden');
            }
        }

        function closeAllMobDropdowns() {
            document.querySelectorAll('.mob-dropdown').forEach(el => {
                el.classList.add('hidden');
            });
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.block.lg\\:hidden')) {
                closeAllMobDropdowns();
            }
        });

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
                                Toast.fire({icon: 'success', title: data.message || 'Wishlist updated!'});
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
    </script>
@endsection


@push('scripts')
    <script>
        let page = 1;
        let hasMorePages = {{ $records->hasMorePages() ? 'true' : 'false' }};
        let isLoading = false;

        $('main').scroll(function() {
            let $main = $(this);

            if (!hasMorePages) return;

            if ($main.scrollTop() + $main.innerHeight() >= $main[0].scrollHeight - 300) {
                if (isLoading) return;

                isLoading = true;
                page++;
                $('#loading-spinner').removeClass('hidden');

                // URL ke query parameters ko maintain rakhne ke liye current query string sath bhej rahe hain
                let currentUrl = window.location.href;
                let separator = currentUrl.includes('?') ? '&' : '?';

                $.ajax({
                    url: currentUrl + separator + "page=" + page,
                    type: "GET",
                    success: function(response) {
                        $('#loading-spinner').addClass('hidden');

                        if ($.trim(response) === "") {
                            hasMorePages = false;
                            $('#no-more-products').removeClass('hidden');
                        } else {
                            $('#productGrid').append(response);
                            isLoading = false;
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('#loading-spinner').addClass('hidden');
                        isLoading = false;
                    }
                });
            }
        });
    </script>
@endpush


public function allCategories(Request $request, $category = null)
{
// 1. Default main categories
$categories = Categorie::where('parent_id', 0)->with('allChildren')->get();

$availableColors = ProductVariant::whereNotNull('color_name')
->where('color_name', '!=', '')
->distinct()
->pluck('color_name')
->toArray();

$availableSizes = ProductVariant::whereNotNull('size')
->where('size', '!=', '')
->distinct()
->pluck('size')
->toArray();

$availableBrands = Brand::whereNotNull('name')
->where('name', '!=', '')
->get();

$query = Product::with([
'variants',
'prod_brand',
'mainVariantImage',
'mainVariant',
'variant_images',
'reviews'
]);

// 2. Search Filter
if ($request->filled('search')) {
$searchTerm = $request->search;
$query->where(function ($q) use ($searchTerm) {
$q->where('name', 'LIKE', '%' . $searchTerm . '%')
->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
});
}

$currentCategory = null;
$activeCategory = null;

// 3. Category Filter (Clean Route Parameter Handling)
if (!empty($category)) {
$slugs = explode('/', $category);
$targetSlug = end($slugs);

$currentCategory = Categorie::where('slug', $targetSlug)->with('children.children')->first();

if ($currentCategory) {
$activeCategory = $currentCategory;

$getSubIds = function ($cat) use (&$getSubIds) {
$ids = [];
foreach ($cat->children as $child) {
$ids[] = $child->id;
if ($child->children->isNotEmpty()) {
$ids = array_merge($ids, $getSubIds($child));
}
}
return $ids;
};

$categoryIds = array_merge([$currentCategory->id], $getSubIds($currentCategory));
$query->whereIn('category_id', $categoryIds);
}
}

// 4. Filters (Price, Color, Size, Brand)
if ($request->filled('min_price')) {
$query->whereHas('variants', fn($q) => $q->where('price', '>=', $request->min_price));
}
if ($request->filled('max_price')) {
$query->whereHas('variants', fn($q) => $q->where('price', '<=', $request->max_price));
}
if ($request->filled('color')) {
$query->whereHas('variants', fn($q) => $q->where('color_name', $request->color));
}
if ($request->filled('size')) {
$query->whereHas('variants', fn($q) => $q->where('size', $request->size));
}
if ($request->filled('brand')) {
$query->whereHas('prod_brand', fn($q) => $q->where('slug', $request->brand));
}

// 5. Sorting Logic
if ($request->filled('sort')) {
if ($request->sort === 'price_low_high') {
$query->orderBy(\App\Models\ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'asc')->limit(1), 'asc');
} elseif ($request->sort === 'price_high_low') {
$query->orderBy(\App\Models\ProductVariant::select('price')->whereColumn('product_id', 'products.id')->orderBy('price', 'desc')->limit(1), 'desc');
} else {
$query->latest('products.created_at');
}
} else {
$query->latest();
}

// 6. Custom Pagination Logic (1st page: 15 items, Subsequent pages: 10 items)
$page = $request->get('page', 1);
$perPage = ($page == 1) ? 15 : 10;
$totalRecords = $query->count();

if ($page == 1) {
$offset = 0;
$limit = 15;
} else {
$offset = 15 + (($page - 2) * 10);
$limit = 10;
}

$cloneQuery = clone $query;
$recordsCollection = $cloneQuery->offset($offset)->limit($limit)->get();

$records = new \Illuminate\Pagination\LengthAwarePaginator(
$recordsCollection,
$totalRecords,
$perPage,
$page,
['path' => $request->url(), 'query' => $request->query()]
);

$selectedColor = $request->filled('color') ? strtolower(trim($request->color)) : null;

$records->getCollection()->transform(function ($product) use ($selectedColor) {
$variant = null;
if ($selectedColor) {
$variant = $product->variants->first(fn($v) => strtolower(trim($v->color_name)) === $selectedColor);
}

$product->active_variant = $variant ?? ($product->mainVariant ?? $product->variants->first());

$matchedImage = null;
if ($product->active_variant) {
$matchedImage = $product->variant_images->first(fn($img) => $img->id == $product->active_variant->variant_image_id);
}

$product->custom_image_path = $matchedImage
? $matchedImage->image_path
: ($product->mainVariantImage ? $product->mainVariantImage->image_path : '');

$product->avgRating = $product->reviews->isNotEmpty() ? $product->reviews->avg('rating') : 0;

return $product;
});

// 7. Wishlist Check
$wishlistProductIds = Auth::check() ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray() : [];

// 8. AJAX Request Handling for Scroll Pagination
if ($request->ajax()) {
return view('frontend.partials.category-product-cards', compact('records', 'wishlistProductIds'))->render();
}

return view('frontend.categories', compact(
'categories',
'records',
'wishlistProductIds',
'activeCategory',
'currentCategory',
'availableColors',
'availableSizes',
'availableBrands'
));
}
