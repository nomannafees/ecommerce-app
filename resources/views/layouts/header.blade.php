<header class="bg-black flex justify-between h-16 items-center text-white p-4 sticky top-0 z-50">

    <button class="lg:hidden" onclick="OpenNav()">
        <i class="fa fa-bars"></i>
    </button>

    <h1 class="text-xl font-bold">Logo</h1>

    <div class="relative">
        <!-- Profile Button -->
        <div onclick="toggleDropdown()"
            class="cursor-pointer w-10 h-10 rounded-full flex items-center justify-center bg-[#ff4d2d] text-white font-semibold">

            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

        </div>

        <!-- Dropdown -->
        <div id="myDropdown"
            class="hidden absolute right-0 top-full z-50 mt-2 w-50 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">

            <!-- User Info -->
            <div class="border-b border-gray-200 px-4 py-3">
                <p class="truncate font-semibold text-gray-800 capitalize">
                    {{ Auth::user()->name }}
                </p>
                <p class="truncate text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </p>


            </div>

            <!-- Profile -->
            <a href="#"
                class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                <i class="fa-solid fa-user"></i>
                My Profile
            </a>

            <!-- Orders -->
            <a href="#"
                class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                <i class="fa-solid fa-bag-shopping"></i>
                My Orders
            </a>

            <!-- Logout -->
            <a href="{{ route('logout') }}"
                class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-gray-100"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">

                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

            <form id="logout-form"
                action="{{ route('logout') }}"
                method="POST"
                class="hidden">
                @csrf
            </form>

        </div>
    </div>

</header>
