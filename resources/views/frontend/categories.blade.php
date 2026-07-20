@extends('frontend.layouts.app')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="text-center mt-7 px-6 lg:px-10">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">
            🛍️ Explore Our Products
        </h1>
        <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
            Discover the best products from multiple categories.
            Use filters to find exactly what you need — by price, color, size, brand, and category.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 px-6 lg:px-10 py-8">

        <aside class="w-full lg:w-1/4 bg-white p-5 rounded-xl shadow h-fit">

            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">Categories</h2>

                @if(request()->has('min_price') || request()->has('max_price') || request()->has('color') || request()->has('size') || request()->has('brand') || request()->has('search') || request()->has('sort'))
                    <a href="{{ route('categories', ['category' => request('category')]) }}"
                       class="text-xs text-red-500 underline font-medium hover:text-red-700 transition">
                        Clear Filters
                    </a>
                @endif
            </div>

            <ul class="space-y-2 mb-6">
                <li>
                    <a href="{{ route('categories') }}"
                       class="text-gray-600 hover:text-black {{ !request('category') ? 'font-bold text-black' : '' }}">
                        All Products
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('categories', ['category' => $cat->slug] + request()->except('category')) }}"
                           class="text-gray-600 hover:text-black {{ request('category') == $cat->slug ? 'font-bold text-black' : '' }}">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <form method="GET" action="{{ route('categories') }}" id="filterForm" class="space-y-4">

                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                <input type="hidden" name="color" id="selectedColorInput" value="{{ request('color') }}">
                <input type="hidden" name="size" id="selectedSizeInput" value="{{ request('size') }}">
                <input type="hidden" name="brand" id="selectedBrandInput" value="{{ request('brand') }}">

                <h2 class="font-bold">Price</h2>
                <div class="space-y-2 my-2 pb-3">
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
                        <span class="text-black font-bold">Max: Rs <span
                                id="priceLabel">{{ number_format(request('max_price', 100000)) }}</span></span>
                    </div>
                </div>

                <h2 class="font-bold mt-4">Color</h2>
                <div class="flex flex-wrap gap-3 my-2 pb-3">
                    @foreach($availableColors as $colorName)
                        @php
                            $cleanColor = strtolower(trim($colorName));
                            $isSelected = request('color') == $cleanColor;
                            $inlineBg = in_array($cleanColor, ['white', '#ffffff', '#fff']) ? 'background-color: #ffffff; border: 1px solid #d1d5db;' : 'background-color: '.$cleanColor.';';
                        @endphp
                        <button type="button" onclick="selectColorSwatch('{{ $cleanColor }}')"
                                title="{{ ucfirst($colorName) }}"
                                class="w-7 h-7 rounded-full cursor-pointer transition-all duration-200 transform hover:scale-110 relative flex items-center justify-center shadow-sm {{ $isSelected ? 'ring-2 ring-offset-2 ring-black scale-110' : 'opacity-80 hover:opacity-100' }}"
                                style="{{ $inlineBg }}">
                            @if($isSelected) <i
                                class="fa-solid fa-check text-xs {{ in_array($cleanColor, ['white', 'yellow', 'lightgray']) ? 'text-black' : 'text-white' }}"></i> @endif
                        </button>
                    @endforeach
                </div>

                <h2 class="font-bold mt-4">Size</h2>
                <div class="flex flex-wrap gap-2 my-2 pb-3">
                    @foreach($availableSizes as $sizeName)
                        @php
                            $cleanSize = trim($sizeName);
                            $isSizeSelected = request('size') == $cleanSize;
                        @endphp
                        <button type="button" onclick="selectSizeBox('{{ $cleanSize }}')"
                                class="px-3 py-1 cursor-pointer text-sm rounded border transition-all duration-200 font-medium {{ $isSizeSelected ? 'bg-black text-white border-black shadow' : 'bg-gray-50 text-gray-700 border-gray-300 hover:border-black' }}">
                            {{ strtoupper($cleanSize) }}
                        </button>
                    @endforeach
                </div>

                <h2 class="font-bold mt-4">Brands</h2>
                <div class="flex flex-col gap-2 my-2 pb-3 max-h-40 overflow-y-auto custom-scrollbar">
                    @foreach($availableBrands as $brand)
                        @php
                            $isBrandSelected = request('brand') == $brand->slug;
                        @endphp
                        <label
                            class="flex items-center gap-3 cursor-pointer group text-sm text-gray-700 hover:text-black">
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

            </form>
        </aside>

        <div class="w-full lg:w-3/4">

            <form method="GET" action="{{ route('categories') }}" id="searchSortForm"
                  class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                @if(request('color')) <input type="hidden" name="color" value="{{ request('color') }}"> @endif
                @if(request('size')) <input type="hidden" name="size" value="{{ request('size') }}"> @endif
                @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif

                <h2 class="text-lg font-semibold text-gray-700">Products</h2>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto flex-1 justify-end">

                    <div class="w-full sm:w-48 relative h-[42px]">
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

            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($records as $product)
                    @php
                        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                        $totalStock = $product->variants->sum('stock');

                        // Controller se attach kiye gaye attributes ko direct call karein
                        $variant = $product->active_variant;
                        $imagePath = $product->custom_image_path;
                    @endphp

                    <div class="relative bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300 group">

                        <form action="{{ route('wishlists.store') }}" method="POST" class="wishlistForm absolute top-3 right-3 z-30">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="wishlistBtn bg-white px-2 py-2 rounded-full shadow hover:scale-110 transition flex items-center justify-center">
                                <i class="wishlistIcon fa-heart text-lg transition duration-200 {{ $isWishlisted ? 'fa-solid text-red-500' : 'fa-regular text-gray-500' }}"></i>
                            </button>
                        </form>

                        <a href="{{ route('product.detail', $product->slug) }}{{ request('color') ? '?color='.request('color') : '' }}" class="block z-10">
                            <div class="bg-gray-100 overflow-hidden relative">
                                <!-- 🖼️ Dynamic Image Display -->
                                @if(!empty($imagePath))
                                    <img src="{{ asset('storage/'.$imagePath) }}"
                                         class="w-full h-64 object-cover group-hover:scale-105 transition duration-300"
                                         alt="{{ $product->name }}">
                                @else
                                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-medium text-gray-800 truncate group-hover:text-black">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                    {{ strip_tags($product->description) }}
                                </p>

                                <div class="flex items-center justify-between mt-3 gap-2">
                                    <div class="flex items-baseline gap-2">
                                        @if($variant)
                                            <span class="text-base font-bold text-lg text-green-600 whitespace-nowrap">
                                                Rs {{ number_format($variant->price) }}
                                            </span>
                                            @if(!empty($variant->cut_price) && $variant->cut_price > $variant->price)
                                                <span class="text-xs text-gray-400 line-through whitespace-nowrap">
                                                    Rs {{ number_format($variant->cut_price) }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-base font-bold text-green-600 whitespace-nowrap">
                                                Rs {{ number_format($product->base_price ?? 0) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0">
                                        @if($totalStock <= 0)
                                            <span class="inline-block bg-red-100 text-red-600 text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                                                    Out of Stock
                                            </span>
                                        @else
                                            <span class="inline-block bg-emerald-100 text-emerald-700 text-[11px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                                                <span class="text-emerald-800 font-bold text-xs">{{ $totalStock }}</span> In Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 flex flex-col items-center justify-center text-center py-16 px-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 shadow-inner">
                            <i class="fa-solid fa-magnifying-glass text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">No Products Found</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">
                            We couldn't find anything matching your current filters. Try adjusting your price, color,
                            size, or brand criteria.
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

            <div class="mt-6">{{ $records->links() }}</div>
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
