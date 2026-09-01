@php
    // Store data fallback if not passed directly from controller
    $store = $store ?? \App\Models\Store::first();
    // Ensure categories collection exists to avoid null errors
    $categories = $categories ?? collect();
@endphp

<!-- ================= MAIN HEADER (Sticky ONLY on Mobile, Normal scroll on Desktop) ================= -->
<div class="bg-black sticky top-0 lg:static z-50 shadow-lg" x-data="{ mobileSearchOpen: false }">

    <header
            class="container mx-auto flex flex-col justify-between h-13 md:h-20 text-white px-3 sm:px-4 relative">

        <div class="flex justify-between items-center h-full w-full">

            <!-- Dynamic Store Logo & Title Section -->
            <a href="{{ route('index') }}"
               class="flex items-center gap-1.5 hover:opacity-90 transition-opacity lg:ms-5 md:ms-5 xl:ms-5 2xl:ms-5  shrink-0">
                @if($store)
                    {{-- Show Logo if enabled --}}
                    @if($store->is_logo && $store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}"
                             alt="{{ $store->title ?? 'ShopNest' }}"
                             class="h-6 sm:h-9  w-auto max-w-[110px] sm:max-w-[150px] object-contain rounded-md">
                    @endif

                    {{-- Show Title if enabled --}}
                    @if($store->is_title && $store->title)
                        <span class="text-base sm:text-xl font-bold tracking-tight">
                            {{ $store->title }}
                        </span>
                    @endif

                    {{-- Fallback if both toggles are off --}}
                    @if(!$store->is_logo && !$store->is_title)
                        <span class="text-base sm:text-xl font-bold">ShopNest</span>
                    @endif
                @else
                    <h1 class="text-base sm:text-xl font-bold">ShopNest</h1>
                @endif
            </a>

            <!-- Center Search Bar (Desktop) - FIXED ALIGNMENT -->
            <div class="search-wrapper hidden md:flex flex-1 max-w-xl mx-auto justify-center relative px-4">
                <form action="{{ route('categories') }}" method="GET"
                      class="w-full flex items-center bg-white rounded-full border border-gray-300 px-3 py-1 shadow-inner relative h-11 z-20"
                      style="margin-left: 15px">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search products, brands and more..."
                           autocomplete="off"
                           class="w-180 search-input pl-3  pr-2 text-sm text-gray-800 focus:outline-none bg-transparent">

                    <button type="button" class="px-2.5 text-gray-500 hover:text-black transition"
                            title="Search by Image">
                        <i class="fa-solid fa-qrcode text-base"></i>
                    </button>

                    <button type="submit"
                            class="bg-black hover:bg-gray-800 text-white w-9 h-9 rounded-full flex items-center justify-center transition shrink-0">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </form>

                <div id="search-suggestions"
                     class="search-suggestions absolute left-0 right-0 top-[52px] bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden z-30 hidden mt-1 max-h-96 overflow-y-auto"></div>
            </div>

            <!-- Right Side Icons & Sign In Dropdown -->
            <div class="flex items-center gap-2 sm:gap-5 shrink-0">

                <!-- Mobile Search Button Icon -->
                <button @click="mobileSearchOpen = !mobileSearchOpen" class="text-sm hover:text-gray-300 md:hidden p-1">
                    <i class="fa-solid fa-magnifying-glass text-base"></i>
                </button>

                <!-- Wishlist: Desktop Only -->
                <a href="{{ route('wishlist') }}" class="relative text-xl hover:text-red-500 hidden sm:block">
                    <i class="fa-regular fa-heart fa-sm mb-3"></i>
                    <span id="header-wishlist-count"
                          class="wishlist-count absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5 {{ ($wishlistCount ?? 0) > 0 ? '' : 'hidden' }}">
        {{ $wishlistCount ?? 0 }}
    </span>
                </a>

                <!-- Cart: Desktop Only -->
                <a href="{{ route('cart') }}" class="relative text-lg hover:text-gray-300 hidden sm:block">
                    <i class="fa-solid fa-cart-shopping fa-sm"></i>

                    <span id="header-cart-count"
                          class="cart-count absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5">
                        {{ $cartCount ?? 0 }}
                    </span>
                </a>

                <!-- Divider -->
                <div class="hidden sm:block h-8 border-l border-gray-700"></div>

                <!-- User Profile Click Dropdown Container -->
                <div class="relative me-1 sm:me-5 py-1" x-data="{ mobileDropdownOpen: false }"
                     @click.outside="mobileDropdownOpen = false">

                    <!-- Trigger Header Element -->
                    <button
                            type="button"
                            @click.stop="mobileDropdownOpen = !mobileDropdownOpen"
                            id="navAuthLink"
                            class="flex items-center gap-1.5 sm:gap-2 text-white hover:text-emerald-400 transition text-sm font-medium cursor-pointer select-none bg-transparent border-none">
                        <i class="fa-regular fa-user text-sm sm:text-lg"></i>
                        <div class="leading-tight text-left hidden sm:block">
                            <span class="block text-[10px] text-gray-400">Welcome</span>
                            <span class="font-semibold">
                                @auth
                                    {{ Str::limit(Auth::user()->name, 10) }}
                                @else
                                    Sign in / Register
                                @endauth
                            </span>
                        </div>
                    </button>

                    <!-- Dropdown Box (Alpine x-show controlled) -->
                    <div
                            x-show="mobileDropdownOpen"
                            x-cloak
                            style="display: none;"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-full w-55 overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-800 shadow-xl z-50 mt-1">

                        <div class="border-b border-gray-200 px-4 py-3 bg-gray-50">
                            <p class="truncate font-semibold text-gray-800 capitalize">
                                @auth
                                    {{ Auth::user()->name }}
                                @else
                                    Hello, Guest
                                @endauth
                            </p>
                            <p class="truncate text-xs text-gray-500">
                                @auth
                                    {{ Auth::user()->email }}
                                @else
                                    Welcome to our store!
                                @endauth
                            </p>
                        </div>

                        @auth
                            <a href="{{ route('frontend.user_info.index') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                                <i class="fa-solid fa-user"></i>
                                My Profile
                            </a>
                        @else
                            <button type="button"
                                    onclick="handleNavAuthClick(event)"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100 text-left">
                                <i class="fa-solid fa-user"></i>
                                My Profile
                            </button>
                        @endauth

                        @auth
                            <a href="{{ route('frontend.orders.index') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                                <i class="fa-solid fa-bag-shopping"></i>
                                My Orders
                            </a>
                        @else
                            <button type="button"
                                    onclick="handleNavAuthClick(event)"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100 text-left">
                                <i class="fa-solid fa-bag-shopping"></i>
                                My Orders
                            </button>
                        @endauth

                        @auth
                            <a href="{{ route('logout') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100 border-t border-gray-100"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Logout
                            </a>

                            <form id="logout-form"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  class="hidden">
                                @csrf
                            </form>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                               onclick="handleNavAuthClick(event)"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-emerald-600 font-semibold hover:bg-gray-100 border-t border-gray-100">
                                <i class="fa-solid fa-right-to-bracket"></i>
                                Sign in / Register
                            </a>
                        @endguest

                    </div>

                </div>

            </div>
        </div>

    </header>

    <!-- ================= MOBILE EXPANDABLE SEARCH BAR ================= -->
    <div x-show="mobileSearchOpen" x-cloak style="display: none;" x-transition
         class="search-wrapper py-2 px-3 md:hidden w-full bg-gray-200 border-t border-gray-300 shadow-inner relative z-30">
        <form action="{{ route('categories') }}" method="GET"
              class="w-full flex items-center bg-white rounded-full border border-gray-300 px-3 py-0.5 shadow-sm relative h-10 z-20">

            <input type="text"
                   name="search"
                   value="{{ request('search')}}"
                   placeholder="Search products, brands and more..."
                   autocomplete="off"
                   autofocus
                   class="w-full search-input pl-3 pr-2 text-sm text-gray-800 focus:outline-none bg-transparent">

            <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white w-8 h-8 rounded-full flex items-center justify-center transition shrink-0">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
        </form>

        <div id="search-suggestions"
             class="search-suggestions absolute left-3 right-3 top-[48px] bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden z-30 hidden mt-1 max-h-96 overflow-y-auto"></div>
    </div>
    <!-- ================= END MOBILE SEARCH BAR ================= -->

</div>
<!-- ================= END MAIN HEADER ================= -->


<!-- ================= STICKY SUB-HEADER CATEGORIES & CENTER NAV BAR (Sticky on Desktop ONLY) ================= -->
@if (!request()->is('login'))
    <div class="bg-white shadow-md hidden lg:block lg:sticky lg:top-0 z-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-2 flex items-center justify-between">

            <!-- Left Side: All Categories Dropdown Button -->
            <div class="relative w-64 shrink-0"
                 x-data="{ activeMain: null, activeSub: null }"
                 @mouseleave="activeMain = null; activeSub = null;">

                <div class="relative group/dropdown inline-block w-full">

                    <!-- Toggle Button -->
                    <div
                            class="flex items-center justify-between bg-gray-100 transition px-4 py-2 cursor-pointer select-none">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-bars text-gray-800"></i>
                            <span class="font-semibold text-sm text-gray-900">All Categories</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-500 ml-1"></i>
                    </div>

                    <!-- MAIN DROPDOWN WRAPPER -->
                    <div class="hidden group-hover/dropdown:flex absolute left-0 top-full z-50"
                         style="margin-top: 0px !important;">

                        <!-- LEVEL 1: Main Categories Box -->
                        <div class="w-[16rem] bg-white h-[27.7rem] overflow-y-auto custom-scrollbar p-2">
                            <ul class="space-y-0.5 relative">
                                @foreach($categories->where('parent_id',0) as $mainCat)
                                    @php
                                        $isMainActive = request('category') == $mainCat->slug;
                                        $subCategories = $mainCat->children ?? $categories->where('parent_id', $mainCat->id);
                                    @endphp

                                    <li class="relative">
                                        <!-- Main Category Item -->
                                        <div @mouseenter="activeMain = {{ $mainCat->id }}; activeSub = null;"
                                             class="flex items-center justify-between py-2 px-3 rounded-md cursor-pointer transition {{ $isMainActive ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}"
                                             :class="{ 'bg-emerald-50 text-emerald-600': activeMain === {{ $mainCat->id }} }">

                                            <a href="{{ route('categoriesProduct', ['category' => $mainCat->slug]) }}"
                                               class="flex-1 text-sm font-semibold">
                                                {{ $mainCat->name }}
                                            </a>

                                            @if($subCategories->count() > 0)
                                                <i class="fa-solid fa-chevron-right text-xs"
                                                   :class="activeMain === {{ $mainCat->id }} ? 'text-emerald-500' : 'text-gray-400'"></i>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- LEVEL 2 & 3 CONTAINERS -->
                        <div class="relative">
                        @foreach($categories->where('parent_id',0) as $mainCat)
                            @php
                                $subCategories = $mainCat->children ?? $categories->where('parent_id', $mainCat->id);
                            @endphp

                            @if($subCategories->count() > 0)
                                <!-- LEVEL 2: Sub Categories Box -->
                                    <div x-show="activeMain === {{ $mainCat->id }}"
                                         x-cloak
                                         style="display: none;"
                                         @mouseenter="activeMain = {{ $mainCat->id }}"
                                         class="absolute left-0 top-0 w-[16rem] bg-white h-[27.7rem] border-r border-gray-200 p-2 z-50 overflow-y-auto custom-scrollbar">

                                        <ul class="space-y-0.5">
                                            @foreach($subCategories as $subCat)
                                                @php
                                                    $subSlugPath = $mainCat->slug . '/' . $subCat->slug;
                                                    $isSubActive = request('category') == $subCat->slug || request('category') == $subSlugPath;
                                                    $childCategories = $subCat->children ?? $categories->where('parent_id', $subCat->id);
                                                @endphp

                                                <li class="relative">
                                                    <!-- Sub Category Item -->
                                                    <div @mouseenter="activeSub = {{ $subCat->id }}"
                                                         class="flex items-center justify-between py-2 px-3 rounded-md cursor-pointer transition {{ $isSubActive ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}"
                                                         :class="{ 'bg-emerald-50 text-emerald-600': activeSub === {{ $subCat->id }} }">

                                                        <a href="{{ route('categoriesProduct', ['category' => $subSlugPath]) }}"
                                                           class="flex-1 text-sm font-semibold">
                                                            {{ $subCat->name }}
                                                        </a>

                                                        @if($childCategories->count() > 0)
                                                            <i class="fa-solid fa-chevron-right text-xs"
                                                               :class="activeSub === {{ $subCat->id }} ? 'text-emerald-500' : 'text-gray-400'"></i>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- LEVEL 3: Child Categories Box -->
                                    @foreach($subCategories as $subCat)
                                        @php
                                            $childCategories = $subCat->children ?? $categories->where('parent_id', $subCat->id);
                                            $subSlugPath = $mainCat->slug . '/' . $subCat->slug;
                                        @endphp

                                        @if($childCategories->count() > 0)
                                            <div
                                                    x-show="activeMain === {{ $mainCat->id }} && activeSub === {{ $subCat->id }}"
                                                    x-cloak
                                                    style="display: none;"
                                                    @mouseenter="activeMain = {{ $mainCat->id }}; activeSub = {{ $subCat->id }};"
                                                    class="absolute left-64 top-0 bg-white w-[16rem] h-[27.7rem] p-2 z-50 overflow-y-auto custom-scrollbar">

                                                <ul class="space-y-0.5">
                                                    @foreach($childCategories as $childCat)
                                                        @php
                                                            $childSlugPath = $subSlugPath . '/' . $childCat->slug;
                                                            $isChildActive = request('category') == $childSlugPath;
                                                        @endphp
                                                        <li>
                                                            <a href="{{ route('categories', ['category' => $childSlugPath]) }}"
                                                               class="block py-2 px-3 text-sm font-semibold transition {{ $isChildActive ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50 hover:text-black' }}">
                                                                {{ $childCat->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endforeach

                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <!-- Center: Navigation Links -->
            <div class="flex items-center gap-8 text-sm font-semibold text-gray-800">
                <a href="{{ url('/all-product') }}" class="hover:text-emerald-600 transition whitespace-nowrap ms-6">All
                    Products</a>
                <a href="{{ url('/product') }}" class="hover:text-emerald-600 transition">Categories</a>
                @foreach($categories->whereIn('slug', ['men-s-fashion', 'womens-fashion', 'mother-baby', 'home-lifestyle'])->values() as $mainCat)
                    <a href="{{ route('categoriesProduct', ['category' => $mainCat->slug]) }}"
                       class="hover:text-emerald-600 transition whitespace-nowrap">
                        {{ $mainCat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Spacer element to balance out the left category width for absolute center alignment -->
            <div class="w-64 shrink-0 hidden lg:block"></div>

        </div>
    </div>
@endif

<style>
    /* Custom Thin Scrollbar for Categories Dropdown */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    /* Prevent Alpine-controlled elements from flashing visible before Alpine initializes */
    [x-cloak] {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }
</style>
<!-- ================= END SUB-HEADER ================= -->