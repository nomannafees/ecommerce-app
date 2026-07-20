@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <!-- HEADER -->
        <div class="flex justify-between items-center p-6 bg-gray-50 border-b border-gray-300">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Order Details
                </h2>
                <p class="text-gray-500 font-mono mt-1">
                    Order #: {{ $order->order_number }}
                </p>
            </div>

            <!-- STATUS BADGE -->
            <span class="px-4 py-1 rounded-full text-xs font-semibold
                @if($order->status=='completed' || $order->status=='delivered')
                    bg-green-100 text-green-700
                @elseif($order->status=='cancelled')
                    bg-red-100 text-red-700
                @else
                    bg-yellow-100 text-yellow-700
                @endif
            ">
                {{ ucfirst($order->status ?? 'pending') }}
            </span>

        </div>

        <!-- STATUS UPDATE -->
        <div class="p-6 flex justify-between items-center border-b border-gray-300 bg-white">

            <h3 class="text-lg font-semibold text-gray-700">
                Update Order Status
            </h3>

            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <select name="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 bg-white shadow-sm focus:ring focus:ring-blue-200"
                    onchange="this.form.submit()">

                    <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                    <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
                    <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                    <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>

                </select>
            </form>

        </div>

        <!-- CUSTOMER INFO -->
        <div class="p-6 border-b border-gray-300">

            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Customer Information
            </h3>

            <div class="grid md:grid-cols-3 gap-6">

                <div class="p-4 bg-gray-50 rounded-xl">
                    <div class="text-xs text-gray-400">Name</div>
                    <div class="font-semibold">{{ $order->name ?? '-' }}</div>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl">
                    <div class="text-xs text-gray-400">Phone</div>
                    <div class="font-semibold">{{ $order->phone ?? '-' }}</div>
                </div>

                <div class="p-4 bg-gray-50 rounded-xl">
                    <div class="text-xs text-gray-400">Email</div>
                    <div class="font-semibold">{{ $order->email ?? '-' }}</div>
                </div>

            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                <div class="text-xs text-gray-400">Shipping Address</div>
                <div class="font-semibold">{{ $order->shipping_address ?? '-' }}</div>
            </div>

        </div>

        <!-- PRODUCTS -->
        <div class="p-6">

            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Ordered Products
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full  text-left">

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
                        <span class="text-green-600 ">
                            Rs {{ number_format($order->total ?? 0) }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection
