@extends('frontend.layouts.app')

@section('content')

    <!-- Added extra bottom padding (pb-32) to prevent the mobile bottom navigation bar from overlapping content -->
    <div class="min-h-screen bg-gray-50 pb-32 lg:pb-20">

        <!-- TOP HEADER -->
        <div class="bg-white/90 backdrop-blur-sm shadow-sm sticky top-0 z-30 border-b border-gray-100">
            <div class="max-w-4xl mx-auto flex items-center gap-3 px-4 py-3.5">
                <a href="{{ route('index') }}"
                   class="w-9 h-9 flex items-center justify-center rounded-full text-gray-600 hover:bg-gray-100 active:scale-95 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <h1 class="text-lg font-bold text-gray-900 tracking-tight">My Account</h1>
            </div>
        </div>

        <!-- MAIN CONTAINER -->
        <div class="max-w-4xl mx-auto px-4 pt-6 space-y-5">

            <!-- USER PROFILE CARD -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-500 p-5 shadow-md shadow-emerald-900/10">
                <!-- Decorative background circles -->
                <div class="absolute -top-8 -right-8 w-28 h-28 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-10 -right-2 w-20 h-20 rounded-full bg-white/10"></div>

                <div class="relative flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-full bg-emerald-50 backdrop-blur-sm ring-2 ring-white/40 text-emerald-600 flex items-center justify-center text-xl font-bold shrink-0">
                        @auth
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        @else
                            <i class="fa-regular fa-user text-lg"></i>
                        @endauth
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-black text-base truncate">
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                Hello, Guest
                            @endauth
                        </p>
                        <p class="text-xs text-emerald-50/90 truncate mt-0.5">
                            @auth
                                {{ Auth::user()->email }}
                            @else
                                Sign in to access your account
                            @endauth
                        </p>
                    </div>
                    @auth
                        <a href="{{ route('frontend.user_info.index') }}"
                           class="ml-auto shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white/15 hover:bg-white/25 text-white transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                    @endauth
                </div>
            </div>

        @guest
            <!-- GUEST: Sign in / Register prompt -->
                <div>
                    <a href="javascript:void(0);"
                       onclick="handleNavAuthClick(event)"
                       class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-semibold py-3 rounded-xl shadow-sm shadow-emerald-900/10 transition">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Sign in / Register</span>
                    </a>
                </div>
        @endguest

        <!-- MENU SECTIONS -->
            <div class="space-y-5">

                @auth
                    <div>
                        <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-400 px-1 mb-2">Account</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ route('frontend.user_info.index') }}"
                               class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-4 shadow-xs hover:border-emerald-500 hover:shadow-md transition group">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-105 transition">
                                        <i class="fa-solid fa-user text-base"></i>
                                    </span>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800 block">My Profile</span>
                                        <span class="text-xs text-gray-400">Manage personal info</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-emerald-600 transition"></i>
                            </a>

                            <a href="{{ route('frontend.orders.index') }}"
                               class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-4 shadow-xs hover:border-blue-500 hover:shadow-md transition group">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl group-hover:scale-105 transition">
                                        <i class="fa-solid fa-bag-shopping text-base"></i>
                                    </span>
                                    <div>
                                        <span class="text-sm font-bold text-gray-800 block">My Orders</span>
                                        <span class="text-xs text-gray-400">View order history</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-blue-600 transition"></i>
                            </a>
                        </div>
                    </div>
                @endauth

                <div>
                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-400 px-1 mb-2">Shopping</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('wishlist') }}"
                           class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-4 shadow-xs hover:border-rose-500 hover:shadow-md transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl group-hover:scale-105 transition">
                                    <i class="fa-solid fa-heart text-base"></i>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-gray-800 block">My Wishlist</span>
                                    <span class="text-xs text-gray-400">Saved items</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(($wishlistCount ?? 0) > 0)
                                    <span class="bg-rose-100 text-rose-600 text-[11px] font-bold px-2.5 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-rose-500 transition"></i>
                            </div>
                        </a>

                        <a href="{{ route('cart') }}"
                           class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-4 shadow-xs hover:border-amber-500 hover:shadow-md transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl group-hover:scale-105 transition">
                                    <i class="fa-solid fa-cart-shopping text-base"></i>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-gray-800 block">My Cart</span>
                                    <span class="text-xs text-gray-400">Items in cart</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(($cartCount ?? 0) > 0)
                                    <span class="bg-amber-100 text-amber-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                                @endif
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-amber-600 transition"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-400 px-1 mb-2">Support</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('contact') }}"
                           class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-4 shadow-xs hover:border-indigo-500 hover:shadow-md transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-105 transition">
                                    <i class="fa-solid fa-headset text-base"></i>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-gray-800 block">Contact Us</span>
                                    <span class="text-xs text-gray-400">Get assistance</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-indigo-500 transition"></i>
                        </a>
                    </div>
                </div>

                @auth
                    <div class="pt-2">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                           class="flex items-center justify-between bg-white rounded-xl border border-red-100 px-4 py-4 shadow-xs hover:bg-red-50 hover:border-red-200 transition group">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl group-hover:scale-105 transition">
                                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-red-600 block">Logout</span>
                                    <span class="text-xs text-red-400">End your session</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs text-red-300 group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>

                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endauth

            </div>

        </div>

    </div>

@endsection