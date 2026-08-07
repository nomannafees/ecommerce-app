@extends('frontend.layouts.app')

@section('content')

    <!-- FILTER FORM (PRICE, COLOR, SIZE, BRANDS) -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-2 sm:pt-4 sm:pb-2">

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
            <!-- LEFT SIDEBAR (Desktop Only) -->
            <aside class="w-full lg:w-1/5 bg-white p-5 rounded-xl shadow h-fit hidden lg:block overflow-visible">
                <form method="GET" action="{{ route('categories') }}" id="filterForm" class="space-y-4">

                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    <input type="hidden" name="color" id="selectedColorInput" value="{{ request('color') }}">
                    <input type="hidden" name="size" id="selectedSizeInput" value="{{ request('size') }}">
                    <input type="hidden" name="brand" id="selectedBrandInput" value="{{ request('brand') }}">

                    <!-- Categories Filter Section (With See More / See Less Toggle) -->
                    <div x-data="{ showAllCategories: false, categoryLimit: 6 }">
                        <!-- Header updated with green indicator bar -->
                        <h2 class="font-bold text-sm mb-2 tracking-tight flex items-center gap-2">
                            <span class="w-2 h-4 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
                            <span>Categories</span>
                        </h2>

                        <div class="flex flex-col gap-1.5 pb-2">
                        @php
                            if ($currentCategory) {
                                $displayCategories = $currentCategory->children->count() > 0
                                    ? $currentCategory->children
                                    : ($currentCategory->parent_id != 0 ? optional($currentCategory->parent)->children : $categories);
                            } else {
                                $displayCategories = $categories;
                            }
                        @endphp

                        @if($currentCategory && $currentCategory->parent_id != 0)
                            <!-- Back to parent category option -->
                                <a href="{{ route('categories', ['category' => optional($currentCategory->parent)->slug]) }}"
                                   class="text-xs text-emerald-600 font-semibold mb-1 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-arrow-left"></i> Back to {{ optional($currentCategory->parent)->name }}
                                </a>
                            @endif

                            @if($displayCategories)
                                @foreach($displayCategories as $index => $cat)
                                    @php
                                        // Agar current category ka parent hai, toh URL mein parent/child dono aane chahiye
                                        if ($currentCategory && $currentCategory->parent_id == 0) {
                                            $categorySlug = $currentCategory->slug . '/' . $cat->slug;
                                        } elseif ($currentCategory && $currentCategory->parent_id != 0) {
                                            $categorySlug = optional($currentCategory->parent)->slug . '/' . $cat->slug;
                                        } else {
                                            $categorySlug = $cat->slug;
                                        }

                                        $isCategorySelected = request('category') == $categorySlug || request('category') == $cat->slug;
                                    @endphp

                                    <a href="{{ route('categories', array_merge(request()->all(), ['category' => $categorySlug])) }}"
                                       x-show="showAllCategories || {{ $index }} < categoryLimit"
                                       class="text-xs sm:text-sm text-gray-700 hover:text-black py-1 px-2 rounded transition {{ $isCategorySelected ? 'bg-gray-100 font-bold text-black' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <!-- Categories See More / See Less Button -->
                        @if(isset($displayCategories) && count($displayCategories) > 6)
                            <button type="button"
                                    @click="showAllCategories = !showAllCategories"
                                    class="mt-1 w-full py-1.5 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-md text-xs font-semibold text-gray-700 flex items-center justify-center gap-2 transition-all duration-200 shadow-xs cursor-pointer">
                                <span x-text="showAllCategories ? 'See Less' : 'See More ({{ count($displayCategories) - 6 }})'"></span>
                                <i class="fa-solid text-[10px] text-gray-500 transition-transform duration-200" :class="showAllCategories ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        @endif
                    </div>
                    <!-- End Categories Section -->

                    <!-- Price Range -->
                    <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-4 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
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
                    <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-4 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
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

                    <!-- Size Filter -->
                    <h2 class="font-bold text-sm mt-4 tracking-tight flex items-center gap-2">
                        <span class="w-2 h-4 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
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

                    <!-- Brands Filter -->
                    <div x-data="{ showAllBrands: false, brandLimit: 6 }">
                        <h2 class="font-bold text-sm mt-4 mb-2 tracking-tight flex items-center gap-2">
                            <span class="w-2 h-4 bg-emerald-600 rounded-full inline-block flex-shrink-0"></span>
                            <span>Brands</span>
                        </h2>

                        <div class="flex flex-col gap-2 pb-1">
                            @foreach($availableBrands as $index => $brand)
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
                                <span x-text="showAllBrands ? 'See Less' : 'See More ({{ count($availableBrands) - 6 }})'"></span>
                                <i class="fa-solid text-[10px] text-gray-500 transition-transform duration-200" :class="showAllBrands ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        @endif
                    </div>

                </form>
            </aside>

            <!-- RIGHT SIDE (Products Grid - 4 columns) -->
            <div class="w-full lg:w-4/5">

                <!-- DESKTOP SORT BAR -->
                <form method="GET" action="{{ route('categories') }}" id="searchSortForm"
                      class="mb-6 hidden lg:flex flex-row gap-4 items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                    @if(request('color')) <input type="hidden" name="color" value="{{ request('color') }}"> @endif
                    @if(request('size')) <input type="hidden" name="size" value="{{ request('size') }}"> @endif
                    @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif

                    <h2 class="text-base sm:text-lg font-semibold text-gray-700">Products</h2>

                    <div class="flex items-center gap-3">
                        <div class="w-48 relative h-[42px]">
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
                            <a href="{{ route('categories') }}" class="text-xs text-red-500 underline font-medium hover:text-red-700 transition">
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
                        <form method="GET" action="{{ route('categories') }}" class="space-y-1">
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
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
                            <a href="{{ route('categories', request()->except('category')) }}" class="block px-2.5 py-1.5 rounded border {{ !request('category') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Categories</a>
                            @foreach($categories as $cat)
                                <a href="{{ route('categories', ['category' => $cat->slug] + request()->except('category')) }}" class="block px-2.5 py-1.5 rounded border {{ request('category') == $cat->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">
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
                            <a href="{{ route('categories', request()->except('brand')) }}" class="block px-2.5 py-1.5 rounded border {{ !request('brand') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Brands</a>
                            @foreach($availableBrands as $brand)
                                <a href="{{ route('categories', ['brand' => $brand->slug] + request()->except('brand')) }}" class="block px-2.5 py-1.5 rounded border {{ request('brand') == $brand->slug ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">
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
                            <a href="{{ route('categories', request()->except('color')) }}" class="block px-2.5 py-1.5 rounded border {{ !request('color') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Colors</a>
                            <div class="flex flex-wrap gap-2 py-1">
                                @foreach($availableColors as $colorName)
                                    @php
                                        $cleanColor = strtolower(trim($colorName));
                                        $isSelected = request('color') == $cleanColor;
                                        $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';
                                    @endphp
                                    <a href="{{ route('categories', ['color' => $cleanColor] + request()->except('color')) }}"
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
                            <a href="{{ route('categories', request()->except('size')) }}" class="block px-2.5 py-1.5 rounded border {{ !request('size') ? 'border-gray-400 font-semibold bg-gray-50' : 'border-gray-100' }} text-xs text-gray-700">All Sizes</a>
                            <div class="flex flex-wrap gap-1.5 py-1">
                                @foreach($availableSizes as $sizeName)
                                    @php
                                        $cleanSize = trim($sizeName);
                                        $isSizeSelected = request('size') == $cleanSize;
                                    @endphp
                                    <a href="{{ route('categories', ['size' => $cleanSize] + request()->except('size')) }}"
                                       class="px-2.5 py-1 text-xs rounded border transition font-medium {{ $isSizeSelected ? 'bg-gray-100 text-black border-gray-400 shadow-sm' : 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ strtoupper($cleanSize) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRODUCTS GRID (4 columns on lg) -->
                <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-10 md:mb-5 sm:gap-3">
                    @forelse($records as $product)
                        @php
                            $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                            $totalStock = $product->variants->sum('stock');

                            $variant = $product->active_variant;
                            $imagePath = $product->custom_image_path;
                        @endphp

                        <div class="relative bg-white rounded-xl sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 group flex flex-col h-full w-full">

                            <form action="{{ route('wishlists.store') }}" method="POST"
                                  class="wishlistForm absolute top-2 right-2 sm:top-3 sm:right-3 z-30">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                        class="wishlistBtn bg-white rounded-full shadow hover:scale-110 transition flex items-center justify-center"
                                        style="padding: 4px 9px 4px 9px !important;">
                                    <i style="margin: 6px -1px 3px 0px !important;" class="wishlistIcon fa-heart text-xs sm:text-lg transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                                </button>
                            </form>

                            <a href="{{ route('product.detail', $product->slug) }}{{ request('color') ? '?color='.request('color') : '' }}"
                               class="block z-10 flex flex-col h-full">
                                <div class="bg-gray-100 overflow-hidden relative h-42 xs:h-44 sm:h-55 md:h-50 lg:h-50">
                                    @if(!empty($imagePath))
                                        <img src="{{ asset('storage/'.$imagePath) }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs sm:text-base">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                                <div class="p-2.5 sm:p-2 flex-grow flex flex-col justify-between gap-2">
                                    <div>
                                        <!-- NAME -->
                                        <h4 class="font-medium xs:text[14px] md:text[16px] text-gray-800 truncate group-hover:text-black capitalize">
                                            {{ $product->name }}
                                        </h4>

                                        <!-- DESCRIPTION -->
                                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5 mb-0 line-clamp-1 sm:line-clamp-1 [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:2] overflow-hidden leading-relaxed">
                                            {!! Str::limit(strip_tags($product->description), 150) !!}
                                        </p>
                                    </div>
                                    @php
                                        $avgRating = $product->avgRating ?? 0;
                                    @endphp

                                    {{-- Rating Section Blade Code --}}
                                    <div class="flex items-center gap-1">
                                        <div class="flex text-yellow-400 text-[10px] sm:text-xs gap-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($avgRating))
                                                    <i class="fa-solid fa-star"></i>
                                                @elseif($i - $avgRating < 1 && $i - $avgRating > 0)
                                                    <i class="fa-solid fa-star-half-stroke"></i>
                                                @else
                                                    <i class="fa-regular fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-[10px] sm:text-xs text-gray-500 font-medium">({{ number_format($avgRating, 1) }})</span>
                                    </div>

                                    <div class="flex items-center justify-between mt-auto gap-2">
                                        <div class="flex flex-col">
                                            @if($variant)
                                                <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
                                                Rs {{ number_format($variant->price) }}
                                            </span>
                                                @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                                    <span class="text-[10px] sm:text-xs text-gray-400 line-through whitespace-nowrap">
                                                    Rs {{ number_format($variant->cut_price) }}
                                                </span>
                                                @endif
                                            @else
                                                <span class="text-xs sm:text-base font-bold text-green-600 whitespace-nowrap">
                                                Rs {{ number_format($product->base_price ?? 0) }}
                                            </span>
                                            @endif
                                        </div>

                                        <div class="flex-shrink-0">
                                            @if($totalStock <= 0)
                                                <span class="inline-block bg-red-100 text-red-600 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                                Out of Stock
                                            </span>
                                            @else
                                                <span class="inline-block bg-emerald-100 text-emerald-700 text-[9px] sm:text-[11px] font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap">
                                                <span class="text-emerald-800 font-bold text-[10px] sm:text-xs">{{ $totalStock }}</span> In Stock
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center text-center py-16 px-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">No Products Found</h3>
                            <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">
                                We couldn't find anything matching your current filters. Try adjusting your price, color, size, or brand criteria.
                            </p>
                            @if(request()->has('min_price') || request()->has('max_price') || request()->has('color') || request()->has('size') || request()->has('brand') || request()->has('search') || request()->has('sort'))
                                <a href="{{ route('categories', ['category' => request('category')]) }}"
                                   class="mt-5 inline-flex items-center gap-2 bg-black text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-gray-800 transition shadow-sm">
                                    <i class="fa-solid fa-rotate-right text-xs"></i>
                                    Reset All Filters
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class=" -mt-7 mb-5 lg:mt-8 lg:mb-2 mb-7 sm:mb-14">{{ $records->links() }}</div>
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
