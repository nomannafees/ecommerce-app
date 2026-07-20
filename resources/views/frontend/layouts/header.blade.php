<header class="bg-black flex justify-between items-center h-16 text-white px-4 sticky top-0 z-50 shadow-lg">

    <!-- Mobile Menu Button -->
    <button class="lg:hidden" onclick="OpenNav()">
        <i class="fa fa-bars text-xl"></i>
    </button>

    <!-- Logo -->
    <a href="{{ route('index') }}">
        <h1 class="text-xl font-bold ms-5">ShopNest</h1>
    </a>


    <!-- Desktop Menu -->
    <div class="hidden lg:flex gap-5 ms-18">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/product') }}">Product</a>
        <a href="{{ url('/categories') }}" class="hover:text-gray-300">Categories</a>
        <a href="{{ route('contact') }}">Contact</a>
    </div>

    <!-- Right Side -->
    <div class="flex items-center gap-2 sm:gap-4">

        <!-- Search -->
        <button class="text-lg hover:text-gray-300">
            <i class="fa-solid fa-magnifying-glass fa-sm"></i>
        </button>

        <!-- Wishlist -->
        <a href="{{route('wishlist')}}">
            <button class="text-lg hover:text-red-500 hidden sm:block">
                <i class="fa-regular fa-heart fa-sm"></i>
            </button>
        </a>


        <!-- Cart -->
        <a href="{{ route('cart') }}" class="relative text-lg hover:text-gray-300">
            <i class="fa-solid fa-cart-shopping fa-sm"></i>

            <span class="cart-count absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-1.5">
                {{ $cartCount ?? 0 }}
            </span>
        </a>

        <!-- Divider -->
        <div class="hidden sm:block h-8 border-l border-gray-500"></div>

        <!-- Profile -->
        <div class="relative me-5">

            <div onclick="toggleDropdown()"
                class="cursor-pointer w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-[#22c55e] text-white font-semibold">

                @auth
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @else
                G
                @endauth

            </div>

            <!-- Dropdown -->
            <div id="myDropdown"
                class="hidden absolute right-0 top-full mt-2 w-64 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl z-50">

                <div class="border-b border-gray-200 px-4 py-3">

                    <p class="truncate font-semibold text-gray-800 capitalize">
                        @auth
                        {{ Auth::user()->name }}
                        @endauth
                    </p>

                    <p class="truncate text-sm text-gray-500">
                        @auth
                        {{ Auth::user()->email }}
                        @endauth
                    </p>

                </div>

                <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                    <i class="fa-solid fa-user"></i>
                    My Profile
                </a>

                <a href="{{ route('frontend.orders.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                    <i class="fa-solid fa-bag-shopping"></i>
                    My Orders
                </a>

                @auth

                <a href="{{ route('logout') }}"
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100"
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
                    class="flex items-center gap-2 px-4 py-2.5 text-sm text-blue-600 hover:bg-gray-100">

                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </a>

                @endguest

            </div>
        </div>

    </div>
</header>
