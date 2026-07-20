@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6 ">

    <!-- Card Wrapper -->
    <div class="bg-white rounded shadow-lg">

        <!-- Card Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
            <h2 class="text-2xl font-bold text-gray-800">
                Orders
            </h2>
        </div>

        <!-- Search Form -->
        <div class="p-6 border-gray-200">
            <form action="{{ route('orders.index') }}" method="GET">

                <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Order..."
                        class="w-70 border border-gray-300 rounded-lg px-4 py-2">

                    <div>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                            Search
                        </button>

                        <a href="{{ route('orders.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-center">
                            Clear
                        </a>
                    </div>

                </div>

            </form>
        </div>

        <div>
            <div class="bg-white px-6 rounded shadow-lg">

                <div class="overflow-x-auto">

                    <table class="w-full border border-gray-100">

                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left">#</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Order No</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Customer</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Phone</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Total</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Payment</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Status</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Created At</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @if(count($orders) > 0)

                            @foreach($orders as $key => $order)

                            <tr>

                                <td class="border border-gray-200 px-4 py-3 font-semibold">
                                    {{ $key + 1 }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $order->order_number }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $order->name }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $order->phone }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3 font-semibold text-green-600">
                                    Rs {{ number_format($order->total) }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">

                                    @if($order->payment_status == 'paid')
                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Paid
                                    </span>
                                    @elseif($order->payment_status == 'failed')
                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Failed
                                    </span>
                                    @else
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Pending
                                    </span>
                                    @endif

                                </td>

                                <td class="border border-gray-200 px-4 py-3">

                                    @if($order->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Pending
                                    </span>

                                    @elseif($order->status == 'processing')
                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Processing
                                    </span>

                                    @elseif($order->status == 'shipped')
                                    <span class="bg-purple-100 text-purple-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Shipped
                                    </span>

                                    @elseif($order->status == 'delivered')
                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Delivered
                                    </span>

                                    @else
                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Cancelled
                                    </span>
                                    @endif

                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('orders.show', $order) }}"
                                           class="w-10 h-10 flex items-center justify-center bg-green-50 text-green-600 border border-green-100 rounded-xl hover:bg-green-100 transition-all duration-300 shadow-xs cursor-pointer"
                                           title="View Order">

                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>

                                        <!-- <a href="{{ route('orders.edit', $order) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded flex items-center gap-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a> -->

                                    </div>
                                </td>

                            </tr>

                            @endforeach

                            @else

                            <tr>
                                <td colspan="9" class="border border-gray-200 px-4 py-3 text-center">
                                    No Orders Found
                                </td>
                            </tr>

                            @endif

                        </tbody>

                    </table>

                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $orders->links() }}
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection
