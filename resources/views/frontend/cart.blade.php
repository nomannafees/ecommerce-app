@extends('frontend.layouts.app')

@section('content')

    <div class="px-4 md:px-10 pb-10 pt-6 bg-gray-50 min-h-[49vh]">

        <!-- HEADER -->
        <div class="mb-4 text-center">
            <h1 class="text-2xl font-bold text-gray-800">My Cart</h1>
            <p class="text-[16px] text-gray-500">
                Review your items before checkout
            </p>
        </div>

    @if($carts->isEmpty())

        <!-- EMPTY STATE -->
            <div class="empty-cart-message text-center py-10 bg-white rounded-xl shadow-sm">
                <i class="fa-solid fa-cart-shopping text-5xl text-gray-300"></i>
                <h3 class="text-xl font-semibold mt-4 text-gray-700">Your cart is empty</h3>
                <p class="text-sm text-gray-500 mt-1">Start shopping now 🛍️</p>
                <div class="mt-4">
                    <a href="{{ url('/') }}"
                       class="inline-block bg-black text-white px-6 py-2 rounded-xl text-xs font-semibold hover:bg-gray-800 transition">
                        Shop Now
                    </a>
                </div>
            </div>

    @else

        <!-- Layout Grid: Left for Items, Right for Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 lg:gap-4 items-start container mx-auto">

                <!-- Left Column: Cart Items List -->
                <div class="lg:col-span-2 space-y-3">
                @foreach($carts as $item)

                    @php
                        $product = $item->variant->product ?? null;
                        $itemPrice = $item->variant->price ?? ($product->base_price ?? 0);
                    @endphp

                    @if($product)

                        <!-- CARD -->
                            <div class="cart-item flex flex-col md:flex-row items-start md:items-center justify-between bg-white rounded-2xl p-4 md:p-5 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 gap-3 md:gap-0" data-id="{{ $item->id }}">

                                <!-- LEFT SIDE -->
                                <div class="flex items-start gap-4 w-full md:w-2/3">

                                    <!-- IMAGE -->
                                    <a href="{{ route('product.detail', $product->slug) }}" class="flex-shrink-0">
                                        @php
                                            $imagePath = ($item->variant->variantImage)
                                                ? $item->variant->variantImage->image_path
                                                : ($product->mainVariantImage->image_path ?? '');
                                        @endphp
                                        <img src="{{ $imagePath ? asset('storage/'.$imagePath) : asset('upload/no-image.jpg') }}"
                                             class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-xl hover:scale-105 transition border border-gray-100">
                                    </a>

                                    <!-- INFO -->
                                    <div class="flex-1 min-w-0">

                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            <h3 class="font-semibold text-gray-900 text-sm md:text-base hover:text-indigo-600 transition truncate">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <p class="text-xs text-gray-500 mt-0.5 flex flex-wrap items-center gap-1.5">
                                            <span>Color: <strong class="text-gray-700 uppercase">{{ $item->variant->color_name ?? 'Default' }}</strong></span>
                                            <span>•</span>
                                            <span>Size: <strong class="text-gray-700 uppercase">{{ $item->variant->size ?? 'Free Size' }}</strong></span>
                                        </p>

                                        <!-- PRICE & SUBTOTAL (Gap aur margin ko mazeed kam kiya hai) -->
                                        <div class="flex items-center gap-3 mt-1 text-xs">
                                            <span class="text-gray-500">Price: <span class="font-medium text-gray-800">Rs {{ number_format($itemPrice) }}</span></span>
                                            <span class="text-emerald-600 font-bold">Subtotal: Rs {{ number_format($itemPrice * $item->quantity) }}</span>
                                        </div>

                                    </div>

                                </div>

                                <!-- RIGHT SIDE (Quantity aur Delete button ki extra spacing khatam ki hai) -->
                                <div class="flex items-center justify-between md:justify-end w-full md:w-auto gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">

                                    <!-- Quantity Controller -->
                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                        <button type="button" class="qty-minus px-2.5 py-1 text-gray-600 hover:bg-gray-200 transition text-xs font-bold cursor-pointer" data-id="{{ $item->id }}">-</button>
                                        <span class="px-3 py-1 item-qty text-xs font-semibold text-gray-800 bg-white">{{ $item->quantity }}</span>
                                        <button type="button" class="qty-plus px-2.5 py-1 text-gray-600 hover:bg-gray-200 transition text-xs font-bold cursor-pointer" data-id="{{ $item->id }}">+</button>
                                    </div>

                                    <!-- Delete Button -->
                                    <button type="button"
                                            data-id="{{ $item->id }}"
                                            class="deleteCart w-9 h-9 md:w-10 md:h-10 flex-shrink-0 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition shadow-xs cursor-pointer"
                                            title="Remove from cart">
                                        <i class="fa-solid fa-times text-xs md:text-sm"></i>
                                    </button>

                                </div>

                            </div>

                        @endif

                    @endforeach
                </div>

                <!-- Right Column: Cart Summary -->
                <div class="lg:col-span-1 lg:sticky lg:top-24 mb-8">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                <i class="fa-solid fa-receipt text-base"></i>
                            </div>
                            Cart Summary
                        </h2>

                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Total Items</span>
                                <span class="total-items font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ $carts->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Total Quantity</span>
                                <span class="total-qty font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ $carts->sum('quantity') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        <div class="flex justify-between items-center mb-5">
                            <span class="text-gray-700 font-medium text-sm">Total Amount</span>
                            <span class="total-amount text-green-600 font-bold text-lg">
                                Rs {{ number_format(
                                    $carts->sum(function($c) {
                                        $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
                                        return $c->quantity * $price;
                                    })
                                ) }}
                            </span>
                        </div>

                        <a href="{{ route('checkout') }}"
                           class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-3 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>Proceed to Checkout</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>

        @endif

    </div>

@endsection



@push('scripts')
    <liink rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "3000"
            };

            // =====================================================
            // DELETE CART ITEM - AJAX
            // =====================================================
            $(document).on('click', '.deleteCart', function (e) {
                e.preventDefault();

                let button = $(this);
                let cartId = button.data('id');
                let cartItemRow = button.closest('.cart-item');

                // Prevent double click
                button.prop('disabled', true);

                $.ajax({
                    url: "{{ url('/cart') }}/" + cartId,
                    type: "POST",

                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    },

                    success: function (response) {

                        console.log('Delete Response:', response);

                        if (response.status === true || response.status === "true") {

                            // =================================================
                            // 1. REMOVE CART ITEM WITHOUT PAGE RELOAD
                            // =================================================
                            cartItemRow.fadeOut(250, function () {
                                $(this).remove();

                                // Check if cart is now empty
                                if ($('.cart-item').length === 0) {

                                    // Hide cart layout
                                    $('.grid.grid-cols-1.lg\\:grid-cols-3').hide();

                                    // Show empty cart message
                                    $('.empty-cart-message').removeClass('hidden');
                                }
                            });


                            // =================================================
                            // 2. UPDATE CART SUMMARY
                            // =================================================
                            $('.total-items').text(response.total_items);
                            $('.total-qty').text(response.total_quantity);

                            $('.total-amount').text(
                                'Rs ' + response.total_amount
                            );


                            // =================================================
                            // 3. UPDATE DESKTOP HEADER CART COUNT
                            // =================================================
                            let cartCount = parseInt(response.total_items) || 0;

                            console.log(cartCount)

                            $('#header-cart-count').text(cartCount);


                            // =================================================
                            // 4. UPDATE MOBILE HEADER CART COUNT
                            // =================================================
                            $('#mobile-header-cart-count').text(cartCount);


                            // =================================================
                            // 5. SHOW / HIDE HEADER BADGE
                            // =================================================
                            if (cartCount > 0) {

                                $('#header-cart-count').removeClass('hidden');

                                $('#mobile-header-cart-count').removeClass('hidden');

                            } else {

                                $('#header-cart-count').addClass('hidden');

                                $('#mobile-header-cart-count').addClass('hidden');
                            }


                            // =================================================
                            // 6. SUCCESS MESSAGE
                            // =================================================
                            toastr.success(
                                response.message || 'Removed from cart'
                            );

                        } else {

                            button.prop('disabled', false);

                            toastr.error(
                                response.message || 'Unable to remove item'
                            );
                        }
                    },

                    // IMPORTANT: comma yahan hona chahiye
                    error: function (xhr) {

                        console.log('Delete Error:', xhr.responseText);

                        button.prop('disabled', false);

                        toastr.error(
                            'Something went wrong while removing item.'
                        );
                    }
                });
            });



            // =====================================================
            // QUANTITY + / - AJAX UPDATE
            // =====================================================
            $(document).on('click', '.qty-plus, .qty-minus', function (e) {

                e.preventDefault();

                let button = $(this);
                let cartId = button.data('id');
                let isPlus = button.hasClass('qty-plus');

                let cartRow = button.closest('.cart-item');
                let qtySpan = cartRow.find('.item-qty');

                let currentQty = parseInt(qtySpan.text()) || 1;

                let newQty = isPlus
                    ? currentQty + 1
                    : currentQty - 1;


                // Quantity 1 se kam nahi honi chahiye
                if (newQty < 1) {

                    toastr.warning(
                        'Quantity cannot be less than 1.'
                    );

                    return;
                }


                // Prevent multiple clicks
                button.prop('disabled', true);


                $.ajax({

                    url: "{{ route('cart.update') }}",

                    type: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        quantity: newQty,

                        cart_id: cartId
                    },


                    success: function (response) {

                        console.log('Quantity Response:', response);


                        if (
                            response.status === 'success' ||
                            response.status === true
                        ) {

                            // =================================================
                            // 1. UPDATE ITEM QUANTITY
                            // =================================================
                            qtySpan.text(response.quantity);


                            // =================================================
                            // 2. UPDATE ITEM SUBTOTAL
                            // =================================================
                            cartRow.find('.item-subtotal').text(
                                'Rs ' + response.item_subtotal
                            );


                            // =================================================
                            // 3. UPDATE CART SUMMARY
                            // =================================================
                            $('.total-items').text(
                                response.total_items
                            );

                            $('.total-qty').text(
                                response.total_quantity
                            );

                            $('.total-amount').text(
                                'Rs ' + response.total_amount
                            );


                            // =================================================
                            // 4. UPDATE HEADER COUNT
                            // =================================================
                            let cartCount =
                                parseInt(response.total_items) || 0;

                            $('#header-cart-count').text(cartCount);

                            $('#mobile-header-cart-count').text(cartCount);


                            if (cartCount > 0) {

                                $('#header-cart-count')
                                    .removeClass('hidden');

                                $('#mobile-header-cart-count')
                                    .removeClass('hidden');

                            } else {

                                $('#header-cart-count')
                                    .addClass('hidden');

                                $('#mobile-header-cart-count')
                                    .addClass('hidden');
                            }

                        } else {

                            toastr.error(
                                response.message ||
                                'Unable to update quantity.'
                            );
                        }
                    },


                    error: function (xhr) {

                        console.log(
                            'Quantity Error:',
                            xhr.responseText
                        );


                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            toastr.error(
                                xhr.responseJSON.message
                            );

                        }
                    },


                    complete: function () {

                        button.prop('disabled', false);
                    }
                });

            });

        });

    </script>

@endpush
