<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link  rel="icon" type="image/png" href="{{ asset('upload/frontent/favicon2.jpg') }}">
    <title>{{ config('app.name', 'ShopNest') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-40 min-h-screen overflow-hidden">
    <div id="app">
        <div class="flex bg-gray-100 h-screen">
            <main class="flex-1 h-screen overflow-y-scroll">
                @include('frontend.layouts.header')
                @yield('content')
                @include('frontend.layouts.footer')
            </main>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function OpenNav() {
            document.getElementById("mySidenav").classList.add("translate-x-0");
            document.getElementById("mySidenav").classList.remove("-translate-x-64");
        }

        function CloseNav() {
            document.getElementById("mySidenav").classList.add("-translate-x-64");
            document.getElementById("mySidenav").classList.remove("translate-x-0");
        }

        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("hidden");
        }
    </script>
    <script>
        function OpenNav() {
            document.getElementById("mobileNav").style.left = "0";
        }

        function CloseNav() {
            document.getElementById("mobileNav").style.left = "-100%";
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
        $(document).on('submit', '.wishlistForm', function(e) {
            e.preventDefault();

            let form = $(this);
            let icon = form.find('.wishlistIcon');

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),

                success: function(res) {

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
        $(document).on('click', '.deleteWishlist', function() {

            let id = $(this).data('id');
            let btn = $(this);

            $.ajax({
                url: '/wishlist/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },

                success: function(res) {

                    if (res.status) {

                        // 🔥 FORCE REMOVE CORRECT CARD
                        btn.closest('.wishlist-item').fadeOut(300, function() {
                            $(this).remove();
                        });

                    } else {
                        alert(res.message);
                    }

                },

                error: function() {
                    alert('Something went wrong');
                }

            });

        });
    </script>

    <script>
        $(document).on('click', '#addToCartBtn', function() {

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

                success: function(res) {

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

                error: function(xhr) {

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
        $(document).on('click', '.deleteCart', function() {

            let id = $(this).data('id');
            let item = $(this);

            $.ajax({
                url: '/cart/' + id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function(res) {

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
                        item.closest('.cart-item').fadeOut(300, function() {
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
        $(document).on('click', '.qty-plus, .qty-minus', function() {

            let id = $(this).data('id');
            let type = $(this).hasClass('qty-plus') ? 'plus' : 'minus';
            let item = $(this).closest('.cart-item');

            $.ajax({
                url: "{{ route('cart.update') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    type: type
                },

                // 🔥 یہ رہا وہ کامیابی والا حصہ (Success Function) جہاں تبدیلی کرنی ہے
                success: function(res) {

                    let item = $('.qty-plus[data-id="' + id + '"]').closest('.cart-item');

                    // 1. پروڈکٹ کی کوانٹٹی اور سب ٹوٹل اپڈیٹ کریں
                    item.find('.item-qty').text(res.quantity);
                    item.find('.item-subtotal').text('Rs ' + res.subtotal);

                    // 2. کارٹ سمری کی تینوں چیزیں اب بالکل لائیو اپڈیٹ ہوں گی
                    $('.total-items').text(res.total_items); // ٹوٹل پروڈکٹس (ڈیزائنز) کی تعداد
                    $('.total-qty').text(res.total_quantity); // ٹوٹل کوانٹٹی (جتنے پیسز ہیں)
                    $('.total-amount').text('Rs ' + res.total_amount); // فائنل بل کی رقم

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        });
    </script>


    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
