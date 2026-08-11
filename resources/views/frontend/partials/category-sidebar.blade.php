<!-- LEFT SIDEBAR (Desktop Only) -->
<aside class="w-full lg:w-1/5 bg-white p-5 rounded-xl shadow h-fit hidden lg:block overflow-visible" x-data="{ showAllCategories: false, categoryLimit: 6 }">
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

    <form method="GET" action="{{ request()->url() }}" id="filterForm" class="space-y-4">

        @if(request()->route('category'))
            <input type="hidden" name="category" value="{{ request()->route('category') }}">
        @endif

        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

        <input type="hidden" name="color" id="selectedColorInput" value="{{ request('color') }}">
        <input type="hidden" name="size" id="selectedSizeInput" value="{{ request('size') }}">
        <input type="hidden" name="brand" id="selectedBrandInput" value="{{ request('brand') }}">

        {{-- CATEGORIES HEADER WITH CLEAR FILTERS --}}
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-bold text-sm tracking-tight">
                <span>Categories</span>
            </h2>

            @if(request()->hasAny(['color', 'size', 'brand', 'max_price', 'min_price']))
                <a href="{{ request()->url() }}"
                   onclick="fetchResetFilters(event, '{{ request()->url() }}')"
                   class="text-[11px] font-semibold text-red-600 hover:text-red-800 transition underline cursor-pointer">
                    Clear Filters
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-1.5 pb-2">
            @if(isset($currentCategory) && $currentCategory)
                @if($currentCategory->parent)
                    @php
                        $parentCategory = $currentCategory->parent;
                        $backSlug = $buildCategoryPath($parentCategory);
                        $backUrl = url('/collection/' . $backSlug);
                    @endphp
                    <a href="{{ $backUrl }}"
                       onclick="fetchCategoryProducts(event, '{{ $backUrl }}', '{{ $backSlug }}')"
                       class="text-xs text-emerald-600 font-semibold mb-1 flex items-center gap-1 hover:underline category-link">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back to {{ $parentCategory->name }}
                    </a>
                @else
                    @php $allCatUrl = url('/collection'); @endphp
                    <a href="{{ $allCatUrl }}"
                       onclick="fetchCategoryProducts(event, '{{ $allCatUrl }}', '')"
                       class="text-xs text-emerald-600 font-semibold mb-1 flex items-center gap-1 hover:underline category-link">
                        <i class="fa-solid fa-arrow-left"></i>
                        All Categories
                    </a>
                @endif
            @endif

            @if(isset($displayCategories) && $displayCategories->count() > 0)
                @foreach($displayCategories as $index => $cat)
                    @php
                        $categoryPath = $buildCategoryPath($cat);
                        $currentUrlCategory = trim(request()->route('category') ?? '', '/');
                        $isCategorySelected = $currentUrlCategory === trim($categoryPath, '/');

                        $activeQueryParams = array_filter(request()->except('category'));
                        $queryString = !empty($activeQueryParams) ? '?' . http_build_query($activeQueryParams) : '';
                        $targetUrl = url('/collection/' . $categoryPath) . $queryString;
                    @endphp

                    <a href="{{ $targetUrl }}"
                       onclick="fetchCategoryProducts(event, '{{ $targetUrl }}', '{{ $categoryPath }}')"
                       x-show="showAllCategories || {{ $index }} < categoryLimit"
                       class="text-xs sm:text-sm text-gray-700 hover:text-black py-1 px-2 rounded transition category-link {{ $isCategorySelected ? 'bg-gray-100 font-bold text-black' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            @endif
        </div>

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
                       oninput="document.getElementById('priceLabel').innerText = Number(this.value).toLocaleString();"
                       onmouseup="applyPriceFilter(this.value)"
                       ontouchend="applyPriceFilter(this.value)">

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
                        <span x-text="showAllBrands ? 'See Less' : 'See More'"></span>
                        <i class="fa-solid text-[10px] text-gray-500 transition-transform duration-200" :class="showAllBrands ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                @endif
            </div>
        @endif

    </form>
</aside>
