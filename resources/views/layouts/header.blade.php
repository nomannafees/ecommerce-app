@php
    // Agar controller se $store paas na ho raha ho to DB se load karne ke liye safety fallback
    $store = $store ?? \App\Models\Store::first();
@endphp

<header class="bg-black flex justify-between h-15 items-center text-white p-4 sticky top-0 z-50">

    <button class="lg:hidden" onclick="OpenNav()">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Dynamic Store Logo & Title Section -->
    <a href="{{ url('/admin/home') }}" class="flex items-center gap-2.5 hover:opacity-90 transition-opacity">
        @if($store)
            {{-- Show Logo if enabled --}}
            @if($store->is_logo && $store->logo)
                <div class="h-9 w-auto max-w-[150px] overflow-hidden rounded-md flex items-center justify-center">
                    <img src="{{ asset('storage/' . $store->logo) }}"
                         alt="{{ $store->title ?? 'Store Logo' }}"
                         class="h-full w-full object-cover border-0 outline-none ring-0">
                </div>
            @endif

            {{-- Show Title if enabled --}}
            @if($store->is_title && $store->title)
                <span class="text-lg font-bold text-white tracking-tight">
                {{ $store->title }}
            </span>
            @endif

            {{-- Fallback if both toggles are off --}}
            @if(!$store->is_logo && !$store->is_title)
                <span class="text-lg font-bold text-white">Store</span>
            @endif
        @else
            <span class="text-xl font-bold">Logo</span>
        @endif
    </a>

    <div class="relative">
        <!-- Profile Button -->
        <div onclick="toggleDropdown()"
             class="cursor-pointer w-10 h-10 rounded-full flex items-center justify-center bg-[#22c55e] text-white font-semibold overflow-hidden shadow-sm">

            @if(Auth::user()->adminInfo && Auth::user()->adminInfo->image)
                <img src="{{ asset('storage/' . Auth::user()->adminInfo->image) }}"
                     alt="{{ Auth::user()->name }}"
                     class="w-10 h-10 object-cover">
            @else
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            @endif

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
            <a href="{{ route('admin-info.index') }}"
               class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                <i class="fa-solid fa-user"></i>
                My Profile
            </a>

            <!-- Orders -->
            <a href="{{ route('admin-store.index') }}"
               class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-gray-800 hover:bg-gray-100">
                <i class="fa-solid fa-store"></i>
                Admin Store
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
