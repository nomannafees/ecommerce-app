@php
    // Direct view pass na hone ki soorat mein database fallback
    $store = $store ?? \App\Models\Store::first();
@endphp

<div id="sidebarBackdrop"
     onclick="CloseNav()"
     class="fixed inset-0 bg-black/50 z-10 hidden lg:hidden transition-opacity"></div>

<div id="mySidenav"
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

        <!-- Category -->
        <a href="{{ route('categorie.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('categorie.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-tags text-blue-500"></i>
            <span>Category</span>
        </a>

        <!-- Brands -->
        <a href="{{ route('brands.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('brands.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-copyright text-cyan-500"></i>
            <span>Brands</span>
        </a>

        <!-- Products -->
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('products.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-box text-purple-500"></i>
            <span>Products</span>
        </a>

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

        <!-- Reviews -->
        <a href="{{ route('reviews.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('reviews.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-star text-amber-500"></i>
            <span>Reviews</span>
        </a>

        <!-- Slider -->
        <a href="{{ route('sliders.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('sliders.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-images text-indigo-500"></i>
            <span>Slider</span>
        </a>

        <!-- Contacts -->
        <a href="{{ route('contacts.index') }}"
           class="flex items-center gap-3 p-2.5 rounded-xl font-medium transition {{ request()->routeIs('contacts.*') ? 'bg-gray-100 text-gray-900 shadow-xs' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
            <i class="fa-solid fa-envelope text-emerald-500"></i>
            <span>Contacts</span>
        </a>

    </div>

</div>
