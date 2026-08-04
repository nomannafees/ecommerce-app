<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('upload/frontent/favicon2.jpg') }}">
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

            error: function (xhr) {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                console.log(xhr.responseText);
            },
        });

    });
</script>

<script>
    $(document).on('click', '.deleteCart', function () {

        let id = $(this).data('id');
        let item = $(this);

        $.ajax({
            url: '/cart/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function (res) {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: res.status ? 'success' : 'error',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 2000
                });

                if (res.status) {
                    // آئٹم کو اسکرین سے غائب کریں
                    item.closest('.cart-item').fadeOut(300, function () {
                        $(this).remove();

                        // اگر سارے آئٹمز ختم ہو جائیں تو پیج ریفریش کر دیں تاکہ خالی کارٹ کا میسج آ جائے
                        if (res.total_items == 0) {
                            location.reload();
                        }
                    });

                    // کارٹ سمری کو لائیو اپڈیٹ کریں
                    $('.total-items').text(res.total_items);
                    $('.total-qty').text(res.total_quantity);
                    $('.total-amount').text('Rs ' + res.total_amount);
                }

            }
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

                    if ($('.cart-count').length) {
                        $('.cart-count').text(res.total_quantity || res.total_qty);
                    }
                }
            }
        });
    });
</script>

<!-- Pehle jQuery load ho gi -->


<!-- Phir Select2 JS yahan load ho gi -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@stack('scripts')
</body>

</html>
