@extends('layouts.app')

@section('content')
    <div class=" mx-auto p-6">

        <!-- MAIN CARD -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- HEADER -->
            <div class="flex justify-between items-center p-6 bg-gray-50 border-b border-gray-300">

                <div class="flex items-center gap-4">
                    <!-- BACK BUTTON TO ORDERS INDEX -->
                    <a href="{{ route('orders.index') }}"
                       class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-100 flex items-center justify-center transition-all shadow-sm group"
                       title="Back to Orders">
                        <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
                    </a>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Order Details
                        </h2>
                        <p class="text-gray-500 font-mono mt-0.5">
                            Order #: {{ $order->order_number }}
                        </p>
                    </div>
                </div>

                <!-- STATUS BADGE -->
                <span class="px-5 py-1.5 rounded-full text-sm font-medium tracking-wide inline-flex items-center justify-center
                    @if($order->status=='completed' || $order->status=='delivered')
                    bg-green-100 text-green-700
@elseif($order->status=='cancelled')
                    bg-red-100 text-red-700
@else
                    bg-yellow-100 text-yellow-700
@endif ">
                        {{ ucfirst($order->status ?? 'pending') }}
                </span>

            </div>

            <!-- STATUS UPDATE -->
            <div class="p-6 flex justify-between items-center border-b border-gray-300 bg-white">

                <h3 class="text-lg font-semibold text-gray-700">
                    Update Order Status
                </h3>

                <form action="{{ route('orders.update', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')

                    <div class="relative w-max">

                        <select name="status"
                                class="w-full appearance-none border border-gray-300 rounded-lg pl-3 pr-7 py-1.5 bg-white shadow-sm focus:outline-none focus:ring focus:ring-blue-200 cursor-pointer text-sm font-medium text-gray-700"
                                onchange="this.form.submit()">

                            <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                            <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
                            <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                            <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>

                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>

                    </div>
                </form>

            </div>

            <!-- CUSTOMER & SHIPPING INFO (Side-by-Side Cards) -->
            <div class="p-6 border-b border-gray-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- 1st Card: Customer Information (Left Side) -->
                    <div class="p-5 rounded-2xl border border-gray-200/60 space-y-4">
                        <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">
                                Customer Information
                            </h3>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Name</div>
                                <div class="font-normal text-gray-700 text-sm">{{ $order->name ?? '-' }}</div>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Phone</div>
                                <div class="font-normal text-gray-700 text-sm">{{ $order->phone ?? '-' }}</div>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Email</div>
                                <div class="font-normal text-gray-700 text-sm">{{ $order->email ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- 2nd Card: Shipping Information (Right Side) -->
                    <div class="p-5 rounded-2xl border border-gray-200/60 space-y-4">
                        <div class="flex items-center gap-3 border-b border-gray-200 pb-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fa-solid fa-truck-fast text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">
                                Shipping Information
                            </h3>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                    <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Country</div>
                                    <div class="font-normal text-gray-700 text-sm">{{ $order->country->name ?? 'Pakistan' }}</div>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                    <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">State / Province</div>
                                    <div class="font-normal text-gray-700 text-sm">{{ $order->state->name ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                    <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">City</div>
                                    <div class="font-normal text-gray-700 text-sm">{{ $order->city->name ?? '-' }}</div>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                    <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Postal / ZIP Code</div>
                                    <div class="font-normal text-gray-700 text-sm">{{ $order->postal_code ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-2xs">
                                <div class="text-xs text-gray-800 font-bold uppercase tracking-wider mb-0.5">Street Address</div>
                                <div class="font-normal text-gray-700 text-sm">{{ $order->shipping_address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="p-6">

                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Ordered Products
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead class="bg-gray-100 text-gray-600 text-sm">
                        <tr>
                            <th class="p-3 border border-gray-300">#</th>
                            <th class="p-3 border border-gray-300">Image</th>
                            <th class="p-3 border border-gray-300">Product</th>
                            <th class="p-3 border border-gray-300">Variant</th>
                            <th class="p-3 border border-gray-300 text-center">Qty</th>
                            <th class="p-3 border border-gray-300">Price</th>
                            <th class="p-3 border border-gray-300">Total</th>
                        </tr>
                        </thead>

                        <tbody class="text-sm">

                        @forelse($order->items as $key => $item)

                            <tr class="hover:bg-gray-50">

                                <td class="p-3 border border-gray-300 font-semibold text-gray-500">
                                    {{ $key + 1 }}
                                </td>

                                <td class="p-3 border border-gray-300">
                                    <img class="w-12 h-12 rounded-lg object-cover border border-gray-100 shadow-xs"
                                         src="{{ $item->item_image }}"
                                         alt="{{ $item->product->name ?? 'Product Image' }}">
                                </td>

                                <td class="p-3 border border-gray-300 font-semibold">
                                    {{ $item->product?->name ?? '-' }}
                                </td>

                                <td class="p-3 border border-gray-300 text-gray-600 text-xs">
                                    <div><b>SKU:</b> {{ $item->variant->sku ?? '-' }}</div>
                                    <div><b>Size:</b> {{ $item->variant->size ?? '-' }}</div>
                                    <div><b>Color:</b> {{ $item->variant->color_name ?? '-' }}</div>
                                </td>

                                <td class="p-3 border border-gray-300 text-center font-semibold">
                                    {{ $item->quantity ?? 0 }}
                                </td>

                                <td class="p-3 border border-gray-300">
                                    Rs {{ number_format($item->price ?? 0) }}
                                </td>

                                <td class="p-3 border border-gray-300 font-bold text-green-600">
                                    Rs {{ number_format(($item->price ?? 0) * ($item->quantity ?? 0)) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="p-5 border border-gray-300 text-center text-gray-500">
                                    No items found in this order
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- TOTALS -->
            <div class="p-6 border-t border-b border-gray-300 bg-gray-50">

                <div class="flex justify-end">

                    <div class="w-80 space-y-3 text-sm">

                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rs {{ number_format($order->subtotal ?? 0) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>Rs {{ number_format($order->shipping_cost ?? 0) }}</span>
                        </div>

                        <div class="flex justify-between font-bold text-lg border-t border-b border-gray-300 pt-2">
                            <span>Total</span>
                            <span class="text-green-600">
                                Rs {{ number_format($order->total ?? 0) }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
