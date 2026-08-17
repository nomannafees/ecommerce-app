<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('upload/favicon/images.jpg') }}">
    <title>{{ config('app.name', 'ShopNest') }}</title>
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

<body class="bg-gray-50 min-h-screen overflow-x-hidden">
<div id="app">
    <div class="flex bg-gray-100 h-screen">
        <main class="flex-1 h-screen overflow-y-scroll">
            @include('frontend.layouts.header')
            @yield('content')
            @include('frontend.layouts.footer')
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

        <!-- Products / Shop -->
        <a href="{{ url('/product') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->is('product*') ? 'text-black font-bold' : '' }}">
            <i class="fa-solid fa-bag-shopping text-base"></i>
            <span class="text-[10px] mt-0.5">Shop</span>
        </a>

        <!-- Cart with Dynamic Badge -->
        <a href="{{ route('cart') }}" class="relative flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('cart') ? 'text-black font-bold' : '' }}">
            <div class="relative">
                <i class="fa-solid fa-cart-shopping text-base"></i>
                <!-- Cart Count Badge -->
                <span class="cart-count absolute -top-2 -right-2 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.2 rounded-full {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}">
                    {{ $cartCount ?? 0 }}
                </span>
            </div>
            <span class="text-[10px] mt-0.5">Cart</span>
        </a>

        <!-- Wishlist -->
        <a href="{{ route('wishlist') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('wishlist') ? 'text-black font-bold' : '' }}">
            <i class="fa-solid fa-heart text-base"></i>
            <span class="text-[10px] mt-0.5">Wishlist</span>
        </a>

        <!-- Profile / Account -->
        @auth
            <a href="{{ route('frontend.user_info.index') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('frontend.user_info.index') ? 'text-black font-bold' : '' }}">
                <i class="fa-solid fa-user text-base"></i>
                <span class="text-[10px] mt-0.5">Account</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center text-gray-600 hover:text-black transition {{ request()->routeIs('login') ? 'text-black font-bold' : '' }}">
                <i class="fa-solid fa-user text-base"></i>
                <span class="text-[10px] mt-0.5">Login</span>
            </a>
        @endauth

    </div>
</div>

<!-- GLOBAL AUTHENTICATION MODAL -->
<div id="authModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-8 relative">
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

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="modalLoginPassword" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input id="modalLoginPassword" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                           class="bg-gray-50 border rounded-xl w-full px-4 py-3 text-sm text-gray-800 outline-none transition-all @error('password') border-red-500 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 @enderror">
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
                </div>
                <div class="mb-5">
                    <label for="modalRegisterEmail" class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                    <input id="modalRegisterEmail" type="email" name="email" required autocomplete="email" placeholder="name@company.com" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                </div>
                <div class="mb-5">
                    <label for="modalRegisterPassword" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input id="modalRegisterPassword" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                </div>
                <div class="mb-6">
                    <label for="modalRegisterPasswordConfirm" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="modalRegisterPasswordConfirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                </div>

                <!-- Submit Button with Register Icon -->
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
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
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
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: res.status ? 'success' : 'error',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 2000
                });
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

                    // 🔥 FORCE REMOVE CORRECT CARD
                    btn.closest('.wishlist-item').fadeOut(300, function () {
                        $(this).remove();
                    });

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
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'error', // یہاں آپ 'warning' بھی لکھ سکتے ہیں
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
                            generalError.innerText = data.message || 'Email ya password ghalat hai. Dobara koshish karein.';
                            generalError.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg><span>Login</span>`;
                if (generalError) {
                    generalError.innerText = 'Kuch ghalat ho gaya hai. Dobara koshish karein.';
                    generalError.classList.remove('hidden');
                }
            });
    }

    function submitModalRegister(e) {
        e.preventDefault();
        let name = document.getElementById('modalRegisterName').value;
        let email = document.getElementById('modalRegisterEmail').value;
        let password = document.getElementById('modalRegisterPassword').value;
        let password_confirmation = document.getElementById('modalRegisterPasswordConfirm').value;
        let btn = document.getElementById('modalRegisterBtn');

        btn.disabled = true;
        btn.innerHTML = 'Registering...';

        fetch("{{ route('register') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({name, email, password, password_confirmation, role: 'customer'})
        })
            .then(async res => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/></svg><span>Create Account</span>`;

                let data = await res.json();

                if (res.ok || res.redirected) {
                    closeAuthModal();
                    location.reload();
                } else {
                    alert(data.message || 'Registration failed. Please check inputs.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z"/></svg><span>Create Account</span>`;
                alert('Registration failed. Please check inputs.');
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

        function fetchSuggestions(inputField) {
            let query = inputField.val().trim();
            let suggestionsBox = inputField.closest('.search-wrapper').find('.search-suggestions');

            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('live.search') }}",
                    type: "GET",
                    data: { query: query },
                    success: function (data) {
                        let html = '';

                        // Categories Section
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

                        // Products Section
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

                        // Kuch bhi match nahi hua
                        if (data.categories.length === 0 && data.products.length === 0) {
                            html = '<div class="px-4 py-3 text-sm text-gray-500 text-center">No results found</div>';
                        }

                        suggestionsBox.html(html).removeClass('hidden');
                    }
                });
            } else {
                suggestionsBox.html('').addClass('hidden');
            }
        }

        $(document).on('click', '.search-input', function () {
            fetchSuggestions($(this));
        });

        $(document).on('keyup', '.search-input', function () {
            fetchSuggestions($(this));
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-input, .search-suggestions').length) {
                $('.search-suggestions').addClass('hidden');
            }
        });
    });
</script>

@stack('scripts')

</body>

</html>
