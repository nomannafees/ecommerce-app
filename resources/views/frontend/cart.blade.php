@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-6 sm:py-10">

        <div class="mb-6 sm:mb-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">My Cart</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Review your items before checkout
            </p>
        </div>

        @if($carts->isEmpty())

            <div class="text-center py-12 sm:py-16 px-4 bg-white rounded-2xl shadow-sm border border-gray-100  mx-auto">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4">
                    <i class="fa-solid fa-cart-shopping text-3xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Your cart is empty</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 mb-6">Start shopping now 🛍️</p>
                <a href="{{ url('/') }}"
                   class="inline-block bg-black text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-gray-800 transition">
                    Shop Now
                </a>
            </div>

        @else

        <!-- Layout Grid: Left for Items, Right for Summary -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">

                <!-- Left Column: Cart Items List (Takes 2 Columns on Large Screens) -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($carts as $item)

                        @php
                            $product = $item->variant->product ?? null;
                            $itemPrice = $item->variant->price ?? ($product->base_price ?? 0);
                        @endphp

                        @if($product)

                            <div class="cart-item flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition gap-4">

                                <div class="flex items-start sm:items-center gap-4 w-full sm:w-3/4">

                                    <a href="{{ route('product.detail', $product->slug) }}" class="flex-shrink-0">
                                        @php
                                            $imagePath = ($item->variant->variantImage)
                                                ? $item->variant->variantImage->image_path
                                                : ($product->mainVariantImage->image_path ?? '');
                                        @endphp
                                        <img
                                            src="{{ $imagePath ? asset('storage/'.$imagePath) : asset('upload/no-image.jpg') }}"
                                            class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-xl hover:scale-105 transition cursor-pointer border border-gray-100">
                                    </a>

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            <h3 class="font-semibold text-gray-900 text-sm sm:text-base truncate hover:text-green-600 transition">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <p class="text-xs sm:text-sm text-gray-500 mt-1 flex flex-wrap items-center gap-2">
                                            <span class="flex items-center gap-1">
                                                <span class="text-gray-700 font-medium">Color:</span>
                                                <span class="text-gray-600 bg-gray-100 px-2 py-0.5 rounded text-[11px] uppercase">{{ $item->variant->color_name ?? 'Default' }}</span>
                                            </span>

                                            <span class="hidden sm:inline w-px h-3 bg-gray-300"></span>

                                            <span class="flex items-center gap-1">
                                                <span class="text-gray-700 font-medium">Size:</span>
                                                <span class="text-gray-600 bg-gray-100 px-2 py-0.5 rounded text-[11px] uppercase">{{ $item->variant->size ?? 'Free Size' }}</span>
                                            </span>
                                        </p>

                                        <p class="text-gray-600 mt-1.5 text-xs sm:text-sm">
                                            Price: <span class="font-medium text-gray-800">Rs {{ number_format($itemPrice) }}</span>
                                        </p>

                                        <p class="text-gray-800 font-semibold text-xs sm:text-sm mt-0.5">
                                            Subtotal:
                                            <span class="item-subtotal text-green-600 font-bold">
                                                Rs {{ number_format($itemPrice * $item->quantity) }}
                                            </span>
                                        </p>
                                    </div>

                                </div>

                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">

                                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                        <button
                                            class="qty-minus px-3 py-1.5 bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-600 font-bold text-xs sm:text-sm transition"
                                            data-id="{{ $item->id }}">-
                                        </button>
                                        <span class="px-3 sm:px-4 item-qty font-semibold text-gray-800 text-xs sm:text-sm">{{ $item->quantity }}</span>
                                        <button
                                            class="qty-plus px-3 py-1.5 bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-600 font-bold text-xs sm:text-sm transition"
                                            data-id="{{ $item->id }}">+
                                        </button>
                                    </div>

                                    <button type="button"
                                            data-id="{{ $item->id }}"
                                            class="deleteCart w-8 h-8 flex-shrink-0 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition shadow-xs cursor-pointer"
                                            title="Remove from cart">
                                        <i class="fa-solid fa-times text-xs sm:text-sm"></i>
                                    </button>

                                </div>

                            </div>

                        @endif

                    @endforeach
                </div>

                <!-- Right Column: Cart Summary & Checkout Card (Takes 1 Column on Large Screens, sticky) -->
                <div class="lg:col-span-1 lg:sticky lg:top-24 mb-10 sm:mb-20">

                    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-gray-100">

                        <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fa-solid fa-receipt text-base sm:text-lg"></i>
                            </div>
                            Cart Summary
                        </h2>

                        <div class="space-y-2.5 text-xs sm:text-sm">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Total Items</span>
                                <span class="total-items inline-flex items-center justify-center bg-gray-100 text-gray-900 font-semibold px-2 py-0.5 rounded-md border border-gray-200">
                                    {{ $carts->count() }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-gray-600">
                                <span>Total Quantity</span>
                                <span class="total-qty inline-flex items-center justify-center bg-gray-100 text-gray-900 font-semibold px-2 py-0.5 rounded-md border border-gray-200">
                                    {{ $carts->sum('quantity') }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 my-4"></div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-700 font-medium text-sm sm:text-base">Total Amount</span>
                            <span class="total-amount text-green-600 font-bold text-lg sm:text-xl">
                                Rs {{ number_format(
                                    $carts->sum(function($c) {
                                        $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
                                        return $c->quantity * $price;
                                    })
                                ) }}
                            </span>
                        </div>

                        <!-- Proceed to Checkout Button -->
                        <a href="{{ route('checkout') }}"
                           class="inline-flex items-center justify-center gap-2 w-full bg-green-800 text-white py-3 rounded-xl font-semibold text-xs sm:text-sm transition duration-200 hover:bg-green-900 cursor-pointer shadow-sm">
                            <span>Proceed to Checkout</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>

                    </div>

                </div>

            </div>

        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3-6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // Delete Cart Item AJAX
            $(document).on('click', '.deleteCart', function (e) {
                e.preventDefault();

                let button = $(this);
                let cartId = button.data('id');
                let cartItemRow = button.closest('.cart-item');

                if (confirm('Are you sure you want to remove this item from cart?')) {
                    $.ajax({
                        url: "{{ url('/cart/delete') }}/" + cartId,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function (response) {
                            if (response.status == true || response.status == "true") {

                                let successMsg = response.message ? response.message : 'Product removed from cart successfully!';
                                toastr.success(successMsg);

                                cartItemRow.fadeOut(300, function () {
                                    $(this).remove();

                                    if ($('.cart-item').length === 0) {
                                        location.reload();
                                    }
                                });

                                $('.total-items').text(response.total_items);
                                $('.total-qty').text(response.total_quantity);
                                $('.total-amount').text('Rs ' + response.total_amount);

                                if ($('.cart-count').length) {
                                    $('.cart-count').text(response.total_quantity);
                                }
                            } else {
                                toastr.error(response.message || 'Error executing request.');
                            }
                        },
                        error: function (xhr) {
                            console.error("Error Status: " + xhr.status + " | Response: " + xhr.responseText);
                            toastr.error('Something went wrong on the server.');
                        }
                    });
                }
            });

        });
    </script>

@endsection
