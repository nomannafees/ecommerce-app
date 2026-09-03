<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon/latesticon.png') }}">
    <!-- 1. Dynamic Title (Agar page par title na ho toh default show hoga) -->
    <!-- 1. Dynamic Title (Agar page par title na ho toh default show hoga) -->
    @php
        // URL ka aakhri segment nikalna
        $lastSegment = request()->segment(count(request()->segments()));

        // Formatting segment
        $formattedTitle = $lastSegment ? ucwords(str_replace(['-', '_'], ' ', $lastSegment)) : '';

        // Default title logic: Agar segment hai toh "PageName - ShopNest", agar home page hai toh sirf "ShopNest"
        $defaultTitle = $formattedTitle ? $formattedTitle . ' - ShopNest' : 'ShopNest - Best Online Shopping Platform';
    @endphp

    <title>@yield('title', $defaultTitle)</title>

    <!-- 2. Dynamic SEO Meta Description -->
    <meta name="description" content="@yield('meta_description', 'Best online shopping platform for quality products at affordable prices.')">

    <!-- 3. Canonical URL (Duplicate content se bachne ke liye) -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- 4. Open Graph Tags (Jab aap link WhatsApp ya Facebook par share karein, bilkul Daraz ki tarah image/title aye) -->
    <meta property="og:title" content="@yield('title', 'ShopNest  ')">
    <meta property="og:description" content="@yield('meta_description', 'Best online shopping platform.')">
    <meta property="og:image" content="@yield('meta_image', asset('default-share-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!-- Alpine.js Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen overflow-x-hidden flex flex-col justify-between">
<div id="app" class="flex flex-col min-h-screen justify-between w-full">
    <div class="flex bg-gray-100 h-screen">
        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto">
            <div class="flex-grow">
                @include('frontend.layouts.header')
                @yield('content')
            </div>
            @if (!request()->is('thank-you'))
                @include('frontend.layouts.footer')
            @endif
        </main>
    </div>
</div>
<!-- ================= MOBILE BOTTOM NAVIGATION (Daraz Style) ================= -->
<div class="md:hidden fixed bottom-0 left-0 right-0 inset-x-0 bg-white border-t border-gray-200 shadow-lg z-50 px-4 py-2" style="    margin-bottom: -2px !important;">
    <div class="flex items-center justify-between">

        <!-- Home -->
        <a href="{{ route('index') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('index') ? 'text-black font-bold' : '' }}">
            <i class="fa-solid fa-house text-base"></i>
            <span class="text-[10px] mt-0.5">Home</span>
        </a>


        <a href="{{ route('shop.categories') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('shop.categories') ? 'text-black font-bold' : '' }}">
            <i class="fa-solid fa-shop text-base"></i>
            <span class="text-[10px] mt-0.5">Categories</span>
        </a>

        <!-- Products / Shop -->
{{--        <a href="{{ route('shop.categories') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('shop.categories') ? 'text-black font-bold' : '' }}">--}}
{{--            <i class="fa-solid fa-cart-shopping text-base"></i>--}}
{{--            <span class="text-[10px] mt-0.5">Cart</span>--}}
{{--        </a>--}}

        <!-- Cart with Dynamic Badge -->
        <a href="{{ route('cart') }}"
           class="relative flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('cart') ? 'text-black font-bold' : '' }}">

            <div class="relative">
                <i class="fa-solid fa-cart-shopping text-base"></i>

                <span class="cart-count absolute -top-2 -right-2 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">
            {{ $cartCount ?? 0 }}
        </span>
            </div>

            <span class="text-[10px] -mt-1">Cart</span>
        </a>

        <!-- Wishlist -->
        <a href="{{ route('wishlist') }}" class="relative flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('wishlist') ? 'text-black font-bold' : '' }}">
            <div class="relative">
                <i class="fa-solid fa-heart text-base"></i>
                <span class="wishlist-count absolute -top-2 -right-2 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ ($wishlistCount ?? 0) > 0 ? '' : 'hidden' }}">
            {{ $wishlistCount ?? 0 }}
        </span>
            </div>
            <span class="text-[10px] -mt-1">Wishlist</span>
        </a>

        <!-- Profile / Account -->
        @auth
            <a href="{{ route('account.menu') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('account.menu') ? 'text-black font-bold' : '' }}">
                <i class="fa-solid fa-user text-base"></i>
                <span class="text-[10px] mt-0.5">Account</span>
            </a>
        @else
            <a href="javascript:void(0);"
               onclick="handleNavAuthClick(event)"
               class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition">
                <i class="fa-solid fa-user text-base"></i>
                <span class="text-[10px] mt-0.5">Login</span>
            </a>
        @endauth

    </div>
</div>

<!-- GLOBAL AUTHENTICATION MODAL -->
<div id="authModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-3.5 relative">
        <!-- Close Button -->
        <button onclick="closeAuthModal()" type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- LOGIN VIEW -->
        <div id="loginView" class="max-w-md mx-auto p-2">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                <p class="text-sm text-gray-500">Please enter your credentials to log in to your account.</p>
            </div>

            <form id="modalLoginForm" onsubmit="submitModalLogin(event)">
            @csrf

            <!-- Email Field -->
                <div class="mb-4">
                    <label for="modalLoginEmail" class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                    <input id="modalLoginEmail" type="email" name="email" required autocomplete="email" placeholder="name@company.com"
                           class="bg-gray-50 border rounded-xl w-full px-4 py-3 text-sm text-gray-800 outline-none transition-all @error('email') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror">
                    <!-- Email Error Message -->
                    <span id="modalLoginEmailError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>

                <!-- Password Field with Eye Icon -->
                <div class="mb-4">
                    <label for="modalLoginPassword" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input id="modalLoginPassword" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                               class="bg-gray-50 border rounded-xl w-full px-4 py-3 pr-10 text-sm text-gray-800 outline-none transition-all @error('password') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror">
                        <button type="button" onclick="togglePasswordVisibility('modalLoginPassword', 'modalLoginPasswordIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <i id="modalLoginPasswordIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    <!-- Password Error Message -->
                    <span id="modalLoginPasswordError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>

                <!-- General Error Box (Agar credentials match na hon) -->
                <div id="modalLoginGeneralError" class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs hidden text-center font-medium"></div>

                <!-- Submit Button -->
                <button type="submit" id="modalLoginBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 cursor-pointer flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    <span>Login</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <button type="button" onclick="switchAuthView('register')" class="text-emerald-600 font-semibold hover:underline cursor-pointer ml-1">Create Account</button>
                </p>
            </div>
        </div>

        <!-- REGISTER VIEW -->
        <div id="registerView" class="hidden max-w-md mx-auto p-2">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-sm text-gray-500">Please fill in the details below to create your new account.</p>
            </div>
            <form id="modalRegisterForm" onsubmit="submitModalRegister(event)">
                @csrf
                <input type="hidden" value="customer" name="role">
                <div class="mb-5">
                    <label for="modalRegisterName" class="block mb-2 text-sm font-medium text-gray-700">Full Name</label>
                    <input id="modalRegisterName" type="text" name="name" required autocomplete="name" placeholder="Enter your full name" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                    <span id="modalRegisterNameError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>
                <div class="mb-5">
                    <label for="modalRegisterEmail" class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                    <input id="modalRegisterEmail" type="email" name="email" required autocomplete="email" placeholder="name@company.com" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                    <span id="modalRegisterEmailError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>

                <!-- Register Password Field with Eye Icon -->
                <div class="mb-5">
                    <label for="modalRegisterPassword" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input id="modalRegisterPassword" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 pr-10 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('modalRegisterPassword', 'modalRegisterPasswordIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <i id="modalRegisterPasswordIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    <span id="modalRegisterPasswordError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>

                <!-- Register Confirm Password Field with Eye Icon -->
                <div class="mb-6">
                    <label for="modalRegisterPasswordConfirm" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <input id="modalRegisterPasswordConfirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 pr-10 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        <button type="button" onclick="togglePasswordVisibility('modalRegisterPasswordConfirm', 'modalRegisterPasswordConfirmIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                            <i id="modalRegisterPasswordConfirmIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    <span id="modalRegisterPasswordConfirmError" class="text-red-500 text-xs mt-1 hidden block"></span>
                </div>

                <!-- General Error Box -->
                <div id="modalRegisterGeneralError" class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs hidden text-center font-medium"></div>

                <!-- Submit Button -->
                <button type="submit" id="modalRegisterBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 cursor-pointer flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                    </svg>
                    <span>Create Account</span>
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <button type="button" onclick="switchAuthView('login')" class="text-emerald-600 font-semibold hover:underline cursor-pointer ml-1">Login here</button>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ================= END MOBILE BOTTOM NAVIGATION ================= -->


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    function OpenNav() {
        let mobileNav = document.getElementById("mobileNav");
        let mySidenav = document.getElementById("mySidenav");
        let backdrop = document.getElementById("sidebarBackdrop");

        // Agar aap Tailwind translate-x wala sidenav use kar rahe hain
        if (mySidenav) {
            mySidenav.classList.add("translate-x-0");
            mySidenav.classList.remove("-translate-x-64");
        }

        // Agar aap style left wala mobileNav use kar rahe hain
        if (mobileNav) {
            mobileNav.style.left = "0";
        }

        // Backdrop show karne ke liye
        if (backdrop) {
            backdrop.classList.remove("hidden");
        }
    }

    function CloseNav() {
        let mobileNav = document.getElementById("mobileNav");
        let mySidenav = document.getElementById("mySidenav");
        let backdrop = document.getElementById("sidebarBackdrop");

        // Agar aap Tailwind translate-x wala sidenav use kar rahe hain
        if (mySidenav) {
            mySidenav.classList.add("-translate-x-64");
            mySidenav.classList.remove("translate-x-0");
        }

        // Agar aap style left wala mobileNav use kar rahe hain
        if (mobileNav) {
            mobileNav.style.left = "-100%";
        }

        // Backdrop hide karne ke liye
        if (backdrop) {
            backdrop.classList.add("hidden");
        }
    }

    function toggleDropdown() {
        let dropdown = document.getElementById("myDropdown");
        if (dropdown) {
            dropdown.classList.toggle("hidden");
        }
    }
</script>

@if(session('success'))

    <script>
        {{--Swal.fire({--}}
        {{--    toast: true,--}}
        {{--    position: 'top-end',--}}
        {{--    icon: 'success',--}}
        {{--    title: "{{ session('success') }}",--}}
        {{--    showConfirmButton: false,--}}
        {{--    timer: 2000--}}
        {{--});--}}
    </script>

@endif

<script>
    $(document).on('submit', '.wishlistForm', function (e) {
        e.preventDefault();

        let form = $(this);
        let icon = form.find('.wishlistIcon');

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (res) {

                // Agar user login nahi hai, to toast ki jagah login modal kholo
                if (!res.status && res.message === 'Please login first') {
                    if (typeof openAuthModal === 'function') {
                        openAuthModal();
                    }
                    return;
                }

                if (res.status) {

                    // ADD
                    if (res.action === 'added') {
                        icon.removeClass('fa-regular text-gray-500')
                            .addClass('fa-solid text-red-500');
                    }

                    // REMOVE
                    if (res.action === 'removed') {
                        icon.removeClass('fa-solid text-red-500')
                            .addClass('fa-regular text-gray-500');
                    }

                    // Badge update (header + mobile bottom-nav dono)
                    if (typeof res.wishlistCount !== 'undefined') {
                        $('.wishlist-count').text(res.wishlistCount);
                        if (res.wishlistCount > 0) {
                            $('.wishlist-count').removeClass('hidden');
                        } else {
                            $('.wishlist-count').addClass('hidden');
                        }
                    }
                }

                // Swal.fire({
                //     toast: true,
                //     position: 'top-end',
                //     icon: res.status ? 'success' : 'error',
                //     title: res.message,
                //     showConfirmButton: false,
                //     timer: 2000
                // });
            }
        });
    });
</script>

<script>
    $(document).on('click', '.deleteWishlist', function () {

        let id = $(this).data('id');
        let btn = $(this);

        $.ajax({
            url: '/wishlist/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },

            success: function (res) {

                if (res.status) {

                    // Deleted card remove
                    btn.closest('.wishlist-item').fadeOut(300, function () {

                        $(this).remove();

                        // Agar koi wishlist item baki nahi hai
                        if ($('.wishlist-item').length === 0) {

                            // Wishlist container hide
                            $('#wishlistContainer').addClass('hidden');

                            // Empty wishlist show
                            $('#emptyWishlist')
                                .removeClass('hidden')
                                .hide()
                                .fadeIn(300);
                        }

                    });


                    // Badge update (header + mobile bottom-nav dono)
                    if (typeof res.wishlistCount !== 'undefined') {

                        $('.wishlist-count').text(res.wishlistCount);

                        if (res.wishlistCount > 0) {
                            $('.wishlist-count').removeClass('hidden');
                        } else {
                            $('.wishlist-count').addClass('hidden');
                        }
                    }

                } else {
                    alert(res.message);
                }

            },

            error: function () {
                alert('Something went wrong');
            }

        });

    });
</script>

<script>
    $(document).on('click', '#addToCartBtn', function () {

        let variant_id = $('#selectedVariantId').val();
        let qty = $('#qtyInput').val();

        if (qty < 1) qty = 1;

        if ($('.variant-btn').length > 0 && variant_id == '') {
            // SweetAlert2 Toast Configuration
            // const Toast = Swal.mixin({
            //     toast: true,
            //     position: 'top-end',
            //     showConfirmButton: false,
            //     timer: 3000,
            //     timerProgressBar: true,
            //     didOpen: (toast) => {
            //         toast.addEventListener('mouseenter', Swal.stopTimer)
            //         toast.addEventListener('mouseleave', Swal.resumeTimer)
            //     }
            // });

            Toast.fire({
                icon: 'error',
                title: 'Please select a variant'
            });

            return;
        }

        $.ajax({
            url: "/add-to-cart",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                variant_id: variant_id,
                qty: qty
            },

            success: function (res) {

                console.log(res);

                $('.cart-count').removeClass('hidden');
                $('.cart-count').text(res.cartCount);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 2000
                });

            },


        });

    });
</script>



<script>
    $(document).off('click', '.qty-plus, .qty-minus').on('click', '.qty-plus, .qty-minus', function () {
        let id = $(this).data('id');
        let isPlus = $(this).hasClass('qty-plus');
        let type = isPlus ? 'plus' : 'minus';
        let item = $(this).closest('.cart-item');
        let qtySpan = item.find('.item-qty');
        let currentQty = parseInt(qtySpan.text());

        if (!isPlus && currentQty <= 1) {
            return;
        }

        $.ajax({
            url: "{{ route('cart.update') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                type: type
            },
            success: function (res) {
                if (res.status === 'success' || res.status === true || res.status === "true") {
                    item.find('.item-qty').text(res.quantity || res.new_qty);
                    item.find('.item-subtotal').text('Rs ' + (res.subtotal || res.item_subtotal));
                    $('.total-items').text(res.total_items);
                    $('.total-qty').text(res.total_quantity || res.total_qty);
                    $('.total-amount').text('Rs ' + res.total_amount);


                }
            }
        });
    });
</script>

<!-- Pehle jQuery load ho gi -->


<!-- Phir Select2 JS yahan load ho gi -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Baqi sab global scripts...

    // Yeh wala function yahan rakh dein
    function handleNavAuthClick(e) {
        @guest
        e.preventDefault();
        openAuthModal();
        @endguest
    }
</script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // --- GLOBAL AUTH MODAL & NAVBAR TRIGGER FUNCTIONS ---
    function openAuthModal() {
        const modal = document.getElementById('authModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            switchAuthView('login');
        }
    }

    function closeAuthModal() {
        const modal = document.getElementById('authModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function switchAuthView(viewName) {
        const loginView = document.getElementById('loginView');
        const registerView = document.getElementById('registerView');
        if (!loginView || !registerView) return;

        if (viewName === 'register') {
            loginView.classList.add('hidden');
            registerView.classList.remove('hidden');
        } else {
            registerView.classList.add('hidden');
            loginView.classList.remove('hidden');
        }
    }

    function handleNavAuthClick(e) {
        @guest
        e.preventDefault();
        openAuthModal();
        @endguest
    }

    function submitModalLogin(e) {
        e.preventDefault();
        let email = document.getElementById('modalLoginEmail');
        let password = document.getElementById('modalLoginPassword');
        let btn = document.getElementById('modalLoginBtn');

        let emailError = document.getElementById('modalLoginEmailError');
        let passwordError = document.getElementById('modalLoginPasswordError');
        let generalError = document.getElementById('modalLoginGeneralError');

        // Purane errors reset karein
        if (emailError) emailError.classList.add('hidden');
        if (passwordError) passwordError.classList.add('hidden');
        if (generalError) generalError.classList.add('hidden');
        if (email) email.classList.remove('border-red-500');
        if (password) password.classList.remove('border-red-500');

        btn.disabled = true;
        btn.innerHTML = 'Logging in...';

        fetch("{{ route('login') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({email: email.value, password: password.value})
        })
            .then(async res => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg><span>Login</span>`;

                let data = await res.json();

                if (res.ok) {
                    closeAuthModal();
                    location.reload();
                } else {
                    if (res.status === 422 && data.errors) {
                        if (data.errors.email && emailError) {
                            email.classList.add('border-red-500');
                            emailError.innerText = data.errors.email[0];
                            emailError.classList.remove('hidden');
                        }
                        if (data.errors.password && passwordError) {
                            password.classList.add('border-red-500');
                            passwordError.innerText = data.errors.password[0];
                            passwordError.classList.remove('hidden');
                        }
                    } else {
                        if (email) email.classList.add('border-red-500');
                        if (password) password.classList.add('border-red-500');
                        if (generalError) {
                            generalError.innerText = data.message || 'The email or password is incorrect. Please try again.\n';
                            generalError.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg><span>Login</span>`;
                if (generalError) {
                    generalError.innerText = 'Something went wrong. Please try again.';
                    generalError.classList.remove('hidden');
                }
            });
    }

    function submitModalRegister(e) {
        e.preventDefault();
        let name = document.getElementById('modalRegisterName');
        let email = document.getElementById('modalRegisterEmail');
        let password = document.getElementById('modalRegisterPassword');
        let passwordConfirm = document.getElementById('modalRegisterPasswordConfirm');
        let btn = document.getElementById('modalRegisterBtn');

        let nameError = document.getElementById('modalRegisterNameError');
        let emailError = document.getElementById('modalRegisterEmailError');
        let passwordError = document.getElementById('modalRegisterPasswordError');
        let passwordConfirmError = document.getElementById('modalRegisterPasswordConfirmError');
        let generalError = document.getElementById('modalRegisterGeneralError');

        // Purane errors reset karein
        [nameError, emailError, passwordError, passwordConfirmError, generalError].forEach(el => {
            if (el) el.classList.add('hidden');
        });
        [name, email, password, passwordConfirm].forEach(el => {
            if (el) el.classList.remove('border-red-500');
        });

        btn.disabled = true;
        btn.innerHTML = 'Registering...';

        fetch("{{ route('register') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                name: name.value,
                email: email.value,
                password: password.value,
                password_confirmation: passwordConfirm.value,
                role: 'customer'
            })
        })
            .then(async res => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/></svg><span>Create Account</span>`;

                let data = await res.json();

                if (res.ok || res.redirected) {
                    closeAuthModal();
                    location.reload();
                } else {
                    // Field-specific validation errors (422 response)
                    if (res.status === 422 && data.errors) {
                        if (data.errors.name && nameError) {
                            name.classList.add('border-red-500');
                            nameError.innerText = data.errors.name[0];
                            nameError.classList.remove('hidden');
                        }
                        if (data.errors.email && emailError) {
                            email.classList.add('border-red-500');
                            emailError.innerText = data.errors.email[0];
                            emailError.classList.remove('hidden');
                        }
                        if (data.errors.password && passwordError) {
                            password.classList.add('border-red-500');
                            passwordError.innerText = data.errors.password[0];
                            passwordError.classList.remove('hidden');
                        }
                        if (data.errors.password_confirmation && passwordConfirmError) {
                            passwordConfirm.classList.add('border-red-500');
                            passwordConfirmError.innerText = data.errors.password_confirmation[0];
                            passwordConfirmError.classList.remove('hidden');
                        }
                    } else if (generalError) {
                        // Koi aur error (validation se hat kar), general box mein dikhao
                        generalError.innerText = data.message || 'Registration failed. Please check inputs.';
                        generalError.classList.remove('hidden');
                    }
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/></svg><span>Create Account</span>`;
                if (generalError) {
                    generalError.innerText = 'Something went wrong. Please try again.';
                    generalError.classList.remove('hidden');
                }
            });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAuthModal();
        }
    });
</script>


<script>
    $(document).ready(function () {

        const RECENT_KEY = 'recentSearches';

        // ===== Recent Search Helpers =====
        function getRecentSearches() {
            return JSON.parse(localStorage.getItem(RECENT_KEY) || '[]');
        }

        function saveRecentSearch(term) {
            term = term.trim();
            if (!term) return;
            let list = getRecentSearches().filter(t => t.toLowerCase() !== term.toLowerCase());
            list.unshift(term);
            list = list.slice(0, 8);
            localStorage.setItem(RECENT_KEY, JSON.stringify(list));
        }

        function renderRecentSearches(suggestionsBox) {
            let list = getRecentSearches();

            if (list.length === 0) {
                suggestionsBox.html('').addClass('hidden');
                return;
            }

            let html = `<div class="py-2">
                <div class="flex justify-between items-center px-4 py-1.5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Search History</p>
                    <button type="button" class="clear-recent text-xs text-orange-500 hover:underline">CLEAR</button>
                </div>`;

            list.forEach(term => {
                html += `<div class="recent-term flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer transition">
                            <i class="fa-regular fa-clock text-xs text-gray-400"></i>
                            <span class="truncate">${term}</span>
                         </div>`;
            });

            html += '</div>';
            suggestionsBox.html(html).removeClass('hidden');
        }

        // ===== Live Suggestions (Categories + Products) =====
        function fetchSuggestions(inputField) {
            let query = inputField.val().trim();
            let suggestionsBox = inputField.closest('.search-wrapper').find('.search-suggestions');

            if (query.length === 0) {
                // Khali input pe click -> recent searches dikhao
                renderRecentSearches(suggestionsBox);
                return;
            }

            $.ajax({
                url: "{{ route('live.search') }}",
                type: "GET",
                data: { query: query },
                success: function (data) {
                    let html = '';

                    if (data.categories.length > 0) {
                        html += '<div class="py-2 border-b border-gray-100">';
                        html += '<p class="px-4 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>';
                        data.categories.forEach(function (cat) {
                            html += `
                                <a href="${cat.url}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition truncate">
                                    <i class="fa-solid fa-layer-group text-xs text-gray-400"></i>
                                    <span class="truncate">${cat.name}</span>
                                </a>`;
                        });
                        html += '</div>';
                    }

                    if (data.products.length > 0) {
                        html += '<div class="py-2">';
                        html += '<p class="px-4 py-1.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Products</p>';
                        data.products.forEach(function (product) {
                            html += `
                                <a href="${product.url}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition truncate">
                                    <i class="fa-solid fa-magnifying-glass text-xs text-gray-400"></i>
                                    <span class="truncate">${product.name}</span>
                                </a>`;
                        });
                        html += '</div>';
                    }

                    if (data.categories.length === 0 && data.products.length === 0) {
                        html = '<div class="px-4 py-3 text-sm text-gray-500 text-center">No results found</div>';
                    }

                    suggestionsBox.html(html).removeClass('hidden');
                }
            });
        }

        $(document).on('click', '.search-input', function () {
            fetchSuggestions($(this));
        });

        $(document).on('keyup', '.search-input', function () {
            fetchSuggestions($(this));
        });

        // Recent search term pe click -> us se search karna
        $(document).on('click', '.recent-term', function () {
            let term = $(this).find('span').text().trim();
            let inputField = $('.search-input');
            inputField.val(term);
            inputField.closest('form').submit();
        });

        // Clear history button
        $(document).on('click', '.clear-recent', function (e) {
            e.stopPropagation();
            localStorage.removeItem(RECENT_KEY);
            $('.search-suggestions').html('').addClass('hidden');
        });

        // Form submit hone par term save karna
        $(document).on('submit', '.search-wrapper form', function () {
            let term = $(this).find('.search-input').val();
            saveRecentSearch(term);
        });

        // Bahir click pe band ho jaye
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-input, .search-suggestions').length) {
                $('.search-suggestions').addClass('hidden');
            }
        });
    });
</script>

<!-- JavaScript Function for Toggle Password Visibility -->
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@stack('scripts')

</body>

</html>
