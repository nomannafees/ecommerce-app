@php
    // Direct view pass na hone ki soorat mein database fallback
    $store = $store ?? \App\Models\Store::first();

    // Active states check karne ke liye variables
    $isCatalogActive = request()->routeIs('categorie.*') || request()->routeIs('brands.*') || request()->routeIs('products.*');
    $isSupportActive = request()->routeIs('reviews.*') || request()->routeIs('contacts.*');
    $isBannersActive = request()->routeIs('banners.*') || request()->routeIs('featuredbanners.*') || request()->routeIs('brandbanners.*') || request()->routeIs('sliders.*');
@endphp

<div id="sidebarBackdrop"
     onclick="CloseNav()"
     class="fixed inset-0 bg-black/50 z-10 hidden lg:hidden transition-opacity"></div>

<div id="mySidenav"
     x-data="{ openDropdown: '{{ $isCatalogActive ? 'catalog' : ($isSupportActive ? 'support' : ($isBannersActive ? 'banners' : '')) }}' }"
     class="bg-white w-64 h-screen overflow-y-auto fixed lg:static transition-transform duration-300 -translate-x-64 lg:translate-x-0 z-12">

    <!-- Header / Branding Section -->
    <div class="relative flex justify-center items-center bg-gray-100 p-4 sticky top-0 z-10">

        <!-- Dynamic Store Logo & Title Section (Centered) -->
        <a href="{{ url('/admin/home') }}" class="flex items-center justify-center gap-2.5 hover:opacity-90 transition-opacity overflow-hidden mx-auto text-center">
            @if($store)
                {{-- Logo show karein agar enabled hai --}}
                @if($store->is_logo && $store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}"
                         alt="{{ $store->title ?? 'Store Logo' }}"
                         class="h-8 w-auto max-w-[120px] object-contain rounded-md shrink-0">
                @endif

                {{-- Title show karein agar enabled hai --}}
                @if($store->is_title && $store->title)
                    <span class="text-lg font-bold text-gray-800 tracking-tight truncate">
                        {{ $store->title }}
                    </span>
                @endif

                {{-- Fallback agar dono options 0 / disabled hain --}}
                @if(!$store->is_logo && !$store->is_title)
                    <span class="text-xl font-bold text-gray-800">Store</span>
                @endif
            @else
                <span class="text-2xl font-semibold text-gray-800">Logo</span>
            @endif
        </a>

        <!-- Close Button (Pinned to Right) -->
        <button class="lg:hidden text-gray-600 hover:text-gray-900 absolute right-4" onclick="CloseNav()">
            <i class="fa fa-times text-lg"></i>
        </button>
    </div>

    <!-- Menu Links -->
    <div class="p-4 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('home') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('home') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-gauge text-blue-500"></i>
            <span>Dashboard</span>
        </a>

        <!-- Catalog Dropdown (Teal Color Theme) -->
        <div class="space-y-1">
            <button @click="openDropdown = openDropdown === 'catalog' ? '' : 'catalog'"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl font-medium transition text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ $isCatalogActive ? 'bg-gray-100 text-gray-900 shadow-xs' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-boxes-stacked text-teal-500"></i>
                    <span>Catalog</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'catalog' }"></i>
            </button>

            <!-- Submenu Items with Teal Active State -->
            <div x-show="openDropdown === 'catalog'" x-collapse x-cloak class="pl-3 ml-4 mt-1 border-l-2 border-teal-100 space-y-1">
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('products.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-box text-xs"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('categorie.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('categorie.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-tags text-xs"></i>
                    <span>Category</span>
                </a>

                <a href="{{ route('brands.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('brands.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-copyright text-xs"></i>
                    <span>Brands</span>
                </a>
            </div>
        </div>

        <!-- Coupon -->
        <a href="{{ route('coupons.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('coupons.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-ticket text-pink-500"></i>
            <span>Coupon</span>
        </a>

        <!-- Wishlist -->
        <a href="{{ route('wishlists.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('wishlists.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-heart text-red-500"></i>
            <span>Wishlist</span>
        </a>

        <!-- Orders -->
        <a href="{{ route('orders.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('orders.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-box text-blue-500"></i>
            <span>Orders</span>
        </a>

        <!-- Customer Support Dropdown -->
        <div class="space-y-1">
            <button @click="openDropdown = openDropdown === 'support' ? '' : 'support'"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl font-medium transition text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ $isSupportActive ? 'bg-gray-100 text-gray-900 shadow-xs' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-headset text-amber-500"></i>
                    <span>Customer Support</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'support' }"></i>
            </button>

            <!-- Submenu Items -->
            <div x-show="openDropdown === 'support'" x-collapse x-cloak class="pl-3 ml-4 mt-1 border-l-2 border-amber-100 space-y-1">
                <a href="{{ route('reviews.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reviews.*') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-star text-xs"></i>
                    <span>Reviews</span>
                </a>

                <a href="{{ route('contacts.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('contacts.*') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-envelope text-xs"></i>
                    <span>Contacts</span>
                </a>
            </div>
        </div>

        <!-- Sliders & Banners Dropdown Menu -->
        <div class="space-y-1">
            <button @click="openDropdown = openDropdown === 'banners' ? '' : 'banners'"
                    class="w-full flex items-center justify-between p-2.5 rounded-xl font-medium transition text-gray-600 hover:bg-gray-100 hover:text-gray-900 {{ $isBannersActive ? 'bg-gray-100 text-gray-900 shadow-xs' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-images text-teal-500"></i>
                    <span>Sliders & Banners</span>
                </div>
                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'banners' }"></i>
            </button>

            <!-- Submenu Items -->
            <div x-show="openDropdown === 'banners'" x-collapse x-cloak class="pl-3 ml-4 mt-1 border-l-2 border-teal-100 space-y-1">
                <a href="{{ route('sliders.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('sliders.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-image text-xs"></i>
                    <span>Sliders</span>
                </a>

                <a href="{{ route('banners.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('banners.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-panorama text-xs"></i>
                    <span>Main Banners</span>
                </a>

                <a href="{{ route('featuredbanners.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('featuredbanners.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-photo-film text-xs"></i>
                    <span>Featured Banners</span>
                </a>

                <a href="{{ route('brandbanners.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('brandbanners.*') ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-clone text-xs"></i>
                    <span>Brands Banners</span>
                </a>
            </div>
        </div>

    </div>

</div>
