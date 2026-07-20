@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto px-4 py-12">

        <!-- HEADER -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                My Orders
            </h2>
            <p class="text-gray-500 mt-2 text-sm">
                Track your purchases and order status in real-time
            </p>
        </div>

        <!-- EMPTY STATE -->
        @if($orders->count() == 0)
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">

                <div class="text-6xl mb-4">📦</div>

                <h3 class="text-xl font-semibold text-gray-800">
                    No Orders Yet
                </h3>

                <p class="text-gray-500 mt-2">
                    You haven’t placed any order. Start shopping now.
                </p>

                <a href="{{ route('frontendProduct') }}"
                   class="mt-6 inline-block bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition shadow-md">
                    Browse Products
                </a>

            </div>
        @else

        <!-- ORDERS -->
            <div class="space-y-5">

                @foreach($orders as $order)

                    <a href="{{ route('frontend.orders.show', $order->id) }}"
                       class="group block bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden">

                        <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                            <!-- LEFT SIDE -->
                            <div class="flex-1">

                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition">
                                        Order #{{ $order->order_number ?? $order->id }}
                                    </h3>

                                    <!-- STATUS BADGE WITH UPDATED COLORS -->
                                    <span class="text-xs px-3 py-1 rounded-full font-semibold border
                            @if($order->status == 'pending')
                                        bg-amber-50 text-amber-700 border-amber-200
@elseif($order->status == 'delivered')
                                        bg-emerald-50 text-emerald-700 border-emerald-200
@elseif($order->status == 'completed')
                                        bg-emerald-50 text-emerald-700 border-emerald-200
@else
                                        bg-rose-50 text-rose-700 border-rose-200
@endif">
                            {{ ucfirst($order->status) }}
                        </span>
                                </div>

                                <p class="text-sm text-gray-500 mt-2">
                                    Placed on {{ $order->created_at->format('d M Y') }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $order->items->count() ?? 0 }} Items in this order
                                </p>

                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="text-right">

                                <p class="text-2xl font-bold text-green-600">
                                    Rs {{ number_format($order->total) }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    Total Amount
                                </p>

                            </div>

                        </div>

                        <!-- bottom hover line -->
                        <div class="h-1 w-0 bg-green-500 group-hover:w-full transition-all duration-300"></div>

                    </a>

                @endforeach

            </div>

        @endif

    </div>

@endsection
