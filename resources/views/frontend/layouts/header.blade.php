@php
    // Store data fallback if not passed directly from controller
    $store = $store ?? \App\Models\Store::first();
    // Ensure categories collection exists to avoid null errors
    $categories = $categories ?? collect();
@endphp

<!-- ================= MAIN HEADER (Scrolls away normally, NOT sticky) ================= -->
<div class="bg-black">

    <header
        class=" container mx-auto flex justify-between items-center h-14 md:h-20 text-white px-3 sm:px-4 z-50 shadow-lg relative">

        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-sm p-1" onclick="OpenNav()">
            <i class="fa fa-bars text-base"></i>
        </button>

        <!-- Dynamic Store Logo & Title Section -->
        <a href="{{ route('index') }}"
           class="flex items-center gap-1.5 hover:opacity-90 transition-opacity ms-1 sm:ms-3 shrink-0">
            @if($store)
                {{-- Show Logo if enabled --}}
                @if($store->is_logo && $store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}"
                         alt="{{ $store->title ?? 'ShopNest' }}"
                         class="h-6 sm:h-9 w-auto max-w-[110px] sm:max-w-[150px] object-contain rounded-md">
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

        <!-- Center Search Bar (Exact AliExpress Style from Screenshot) -->
        <!-- Center Search Bar -->
        <div class="search-wrapper hidden md:flex ms-20 flex-1 max-w-xl mx-6 justify-center relative">
            <form action="{{ route('categories') }}" method="GET"
                  class="w-full flex items-center bg-white rounded-full border border-gray-300 px-3 py-1 shadow-inner relative h-11 z-20">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search products, brands and more..."
                       autocomplete="off"
                       class="w-full search-input pl-3 pr-2 text-sm text-gray-800 focus:outline-none bg-transparent">

                <button type="button" class="px-2.5 text-gray-500 hover:text-black transition" title="Search by Image">
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

        <!-- Right Side Icons & Sign In Hover Dropdown -->
        <div class="flex items-center gap-2 sm:gap-5 shrink-0">

            <!-- Mobile Search Button Icon -->
            <button class="text-sm hover:text-gray-300 md:hidden p-1">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            <!-- Wishlist: Desktop Only -->
            <a href="{{route('wishlist')}}">
                <button class="text-lg hover:text-red-500 hidden sm:block">
                    <i class="fa-regular fa-heart fa-sm"></i>
                </button>
            </a>

            <!-- Cart: Desktop Only -->
            <a href="{{ route('cart') }}" class="relative text-lg hover:text-gray-300 hidden sm:block">
                <i class="fa-solid fa-cart-shopping fa-sm"></i>

                <span id="header-cart-count"
                      class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5">
        {{ $cartCount ?? 0 }}
    </span>
            </a>

            <!-- Divider -->
            <div class="hidden sm:block h-8 border-l border-gray-700"></div>

            <!-- AliExpress Style Sign in / Register Hover Dropdown Container -->
            <div class="relative group/userdropdown me-1 sm:me-5 py-1">

                <!-- Trigger Header Element (Ab yahan se onclick aur handleNavAuthClick hata diya gaya hai) -->
                <a
                    id="navAuthLink"
                    class="flex items-center gap-1.5 sm:gap-2 text-white hover:text-emerald-400 transition text-sm font-medium cursor-pointer select-none">
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
                </a>

                <!-- Dropdown Box (Appears on Hover) -->
                <div
                    class="hidden group-hover/userdropdown:block absolute right-0 top-full w-55 overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-800 shadow-xl z-50">

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

                    <a href="{{ route('wishlist') }}"
                       class="flex sm:hidden items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                        <i class="fa-regular fa-heart text-red-500"></i>
                        Wishlist
                    </a>

                    <a href="{{ route('cart') }}"
                       class="flex sm:hidden items-center justify-between px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">

    <span class="flex items-center gap-2">
        <i class="fa-solid fa-cart-shopping"></i>
        Cart
    </span>

                        <span id="mobile-header-cart-count"
                              class="bg-green-100 text-green-700 mt-1 font-bold text-xs rounded-full px-2 py-0.5">
        {{ $cartCount ?? 0 }}
    </span>

                    </a>

                    <a href="{{ route('frontend.user_info.index') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                        <i class="fa-solid fa-user"></i>
                        My Profile
                    </a>

                    <a href="{{ route('frontend.orders.index') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                        <i class="fa-solid fa-bag-shopping"></i>
                        My Orders
                    </a>

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
                    <!-- Dropdown ke andar wala Sign in / Register button (Yahan click par modal open hoga) -->
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
    </header>
</div>
<!-- ================= END MAIN HEADER ================= -->


<!-- ================= STICKY SUB-HEADER CATEGORIES & CENTER NAV BAR (In Container) ================= -->
@if (!request()->is('login'))
    <div class="bg-white shadow-md sticky top-0 z-40 hidden lg:block">
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
                {{--                    <a href="{{ url('/') }}" class="hover:text-emerald-600 transition">Home</a>--}}
                <a href="{{ url('/all-product') }}" class="hover:text-emerald-600 transition">All Products</a>
                <a href="{{ url('/product') }}" class="hover:text-emerald-600 transition">Categories</a>
                {{--                    <a href="{{ route('contact') }}" class="hover:text-emerald-600 transition">Contact Us</a>--}}
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
</style>
<!-- ================= END SUB-HEADER ================= -->


<!-- ================= MOBILE SIDEBAR MENU ================= -->
<div id="mobileNav"
     class="fixed inset-y-0 left-[-100%] w-72 bg-black text-white shadow-2xl z-50 transition-all duration-300 ease-in-out lg:hidden flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800 bg-gray-900">
            <span class="text-base font-bold tracking-wide">Navigation Menu</span>
            <button onclick="CloseNav()"
                    class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-300 hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="flex flex-col px-3 py-3 space-y-1 text-sm font-medium">
            <a href="{{ route('index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-900 transition text-gray-200">
                <i class="fa-solid fa-house w-5 text-center text-gray-400"></i> Home
            </a>
            <a href="{{ url('/all-product') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-900 transition text-gray-200">
                <i class="fa-solid fa-bag-shopping w-5 text-center text-gray-400"></i> Products
            </a>
            <a href="{{ url('/product') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-900 transition text-gray-200">
                <i class="fa-solid fa-list w-5 text-center text-gray-400"></i> Categories
            </a>
            <a href="{{ route('contact') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-900 transition text-gray-200">
                <i class="fa-solid fa-headset w-5 text-center text-gray-400"></i> Contact Us
            </a>
        </div>
    </div>

    <div class="p-4 border-t border-gray-800 bg-gray-900">
        @auth
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
               class="flex items-center justify-center gap-2 w-full bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white transition py-2.5 rounded-lg text-xs font-semibold">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        @else
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('login') }}"
                   class="text-center bg-gray-800 hover:bg-gray-700 text-white py-2.5 rounded-lg font-semibold text-xs transition">Login</a>
                <a href="{{ route('register') }}"
                   class="text-center bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold text-xs transition">Register</a>
            </div>
        @endauth
    </div>
</div>

<!-- Backdrop Overlay for Sidebar -->
<div id="sidebarBackdrop" onclick="CloseNav()"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity"></div>
<!-- ================= END MOBILE SIDEBAR ================= -->
