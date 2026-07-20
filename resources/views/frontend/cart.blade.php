@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 md:px-10 py-10 bg-gray-50">

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800">My Cart</h2>
            <p class="text-sm text-gray-500 mt-1">
                Review your items before checkout
            </p>
        </div>

        @if($carts->isEmpty())

            <div class="text-center py-10 bg-white rounded-xl shadow-sm border border-gray-100">
                <i class="fa-solid fa-cart-shopping text-5xl text-gray-300"></i>
                <h2 class="text-xl font-semibold mt-4 text-gray-700">Your cart is empty</h2>
                <p class="text-sm text-gray-500 mt-1 mb-4">Start shopping now 🛍️</p>
                <a href="{{ url('/') }}" class="inline-block bg-black text-white px-6 py-2 rounded-lg text-sm font-medium cursor-pointer">Shop Now</a>
            </div>

        @else

            <div class="space-y-4">

                @foreach($carts as $item)

                    @php
                        $product = $item->variant->product ?? null;
                        // Variant ki actual price use karein, agar na ho to base price fallback
                        $itemPrice = $item->variant->price ?? ($product->base_price ?? 0);
                    @endphp

                    @if($product)

                        <div class="cart-item flex flex-col md:flex-row items-center justify-between bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg transition">

                            <div class="flex items-center gap-5 w-full md:w-2/3">

                                <a href="{{ route('product.detail', $product->slug) }}">
                                    @php
                                        // Variant image check karein, agar na ho to main variant image fallback
                                        $imagePath = ($item->variant->variantImage)
                                            ? $item->variant->variantImage->image_path
                                            : ($product->mainVariantImage->image_path ?? '');
                                    @endphp
                                    <img src="{{ $imagePath ? asset('storage/'.$imagePath) : asset('upload/no-image.jpg') }}"
                                         class="w-22 h-23 object-cover rounded-lg hover:scale-105 transition cursor-pointer border border-gray-100">
                                </a>

                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-3">
                        <span class="flex items-center gap-1">
                            <span class="text-gray-700 font-medium">Color:</span>
                            <span class="text-gray-600 bg-gray-100 px-2 py-0.5 rounded text-xs uppercase">{{ $item->variant->color_name ?? 'Default' }}</span>
                        </span>

                                        <span class="w-px h-4 bg-gray-300"></span>

                                        <span class="flex items-center gap-1">
                            <span class="text-gray-700 font-medium">Size:</span>
                            <span class="text-gray-600 bg-gray-100 px-2 py-0.5 rounded text-xs uppercase">{{ $item->variant->size ?? 'Free Size' }}</span>
                        </span>
                                    </p>

                                    <p class="text-gray-600 mt-2 text-sm">
                                        Price: <span class="font-medium text-gray-800">Rs {{ number_format($itemPrice) }}</span>
                                    </p>

                                    <p class="text-gray-800 font-semibold mt-1">
                                        Subtotal:
                                        <span class="item-subtotal text-green-600">
                            Rs {{ number_format($itemPrice * $item->quantity) }}
                        </span>
                                    </p>
                                </div>

                            </div>

                            <div class="flex items-center gap-3 mt-4 md:mt-0 w-full md:w-auto justify-between md:justify-end">

                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                    <button class="qty-minus px-3 py-1 bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-600 font-bold" data-id="{{ $item->id }}">-</button>
                                    <span class="px-4 item-qty font-medium text-gray-800">{{ $item->quantity }}</span>
                                    <button class="qty-plus px-3 py-1 bg-gray-100 hover:bg-gray-200 cursor-pointer text-gray-600 font-bold" data-id="{{ $item->id }}">+</button>
                                </div>

                                <button type="button"
                                        data-id="{{ $item->id }}"
                                        class="deleteCart w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-300 shadow-xs cursor-pointer"
                                        title="Remove from cart">

                                    <i class="fa-solid fa-times text-sm"></i>
                                </button>

                            </div>

                        </div>

                    @endif

                @endforeach

                <div class="mt-5 bg-white rounded-2xl p-6 shadow-md border border-gray-100">

                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-gray-500"></i>
                        Cart Summary
                    </h2>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Total Items</span>
                            <span class="total-items font-semibold text-gray-900">
                        {{ $carts->count() }}
                    </span>
                        </div>

                        <div class="flex justify-between items-center text-gray-600">
                            <span>Total Quantity</span>
                            <span class="total-qty font-semibold text-gray-900">
                        {{ $carts->sum('quantity') }}
                    </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-4"></div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-medium text-base">Total Amount</span>
                        <span class="total-amount text-green-600 font-bold text-xl">
                    Rs {{ number_format(
                        $carts->sum(function($c) {
                            $price = $c->variant->price ?? ($c->variant->product->base_price ?? 0);
                            return $c->quantity * $price;
                        })
                    ) }}
                </span>
                    </div>

                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('checkout') }}"
                   class="inline-flex items-center justify-center gap-2 w-full sm:w-60 bg-green-800 text-white py-3 rounded-xl font-semibold transition duration-200 hover:bg-green-900 cursor-pointer shadow-sm">
                    <span>Proceed to Checkout</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3-6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            // Toastr configurations setting (Optional: looks clean)
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // Delete Cart Item AJAX
            $(document).on('click', '.deleteCart', function(e) {
                e.preventDefault();

                let button = $(this);
                let cartId = button.data('id');
                let cartItemRow = button.closest('.cart-item');

                if(confirm('Are you sure you want to remove this item from cart?')) {
                    $.ajax({
                        url: "{{ url('/cart/delete') }}/" + cartId,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: "DELETE"
                        },
                        success: function(response) {
                            if(response.status == true || response.status == "true") {

                                // 🔥 FIX: Toaster message show karwayen jo backend se aa raha hai
                                // Agar backend me 'message' key nahi hai, to default text show hoga
                                let successMsg = response.message ? response.message : 'Product removed from cart successfully!';
                                toastr.success(successMsg);

                                // 1. Item ko screen se smoothly remove karein
                                cartItemRow.fadeOut(300, function() {
                                    $(this).remove();

                                    // 2. Agar koi card na bache to page reload karke empty screen layen
                                    if ($('.cart-item').length === 0) {
                                        location.reload();
                                    }
                                });

                                // 3. Totals text update karein html content par
                                $('.total-items').text(response.total_items);
                                $('.total-qty').text(response.total_quantity);
                                $('.total-amount').text('Rs ' + response.total_amount);

                                if($('.cart-count').length) {
                                    $('.cart-count').text(response.total_quantity);
                                }
                            } else {
                                toastr.error(response.message || 'Error executing request.');
                            }
                        },
                        error: function(xhr) {
                            console.error("Error Status: " + xhr.status + " | Response: " + xhr.responseText);
                            toastr.error('Something went wrong on the server.');
                        }
                    });
                }
            });

        });
    </script>

@endsection
