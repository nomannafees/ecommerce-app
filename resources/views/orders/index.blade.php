@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card Wrapper -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

            <!-- Card Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    Orders
                </h2>

                <!-- Toggle Filter Button -->
                <button
                    @click="openFilter = !openFilter"
                    type="button"
                    class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition duration-200 font-medium text-sm cursor-pointer border border-gray-200">
                    <i class="fa-solid fa-filter text-sm"></i>
                    <span x-text="openFilter ? 'Hide Filters' : 'Show Filters'">Show Filters</span>
                </button>
            </div>

            <!-- Filter Form Panel -->
            <div class="p-6 border-b border-gray-200 bg-gray-50/30" x-show="openFilter" x-transition x-cloak>
                <form x-ref="filterForm" @submit.prevent="fetchOrders()" action="{{ route('orders.index') }}"
                      method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">

                        <!-- Search Input -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Order No, Name, Phone..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <!-- Order Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Order Status</label>
                            <select name="status"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Processing
                                </option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped
                                </option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                    Delivered
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Payment
                                Status</label>
                            <select name="payment_status"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">All Payments</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                </option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                    Failed
                                </option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">From Date</label>
                            <input
                                type="date"
                                name="date_from"
                                value="{{ request('date_from', request('start_date')) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">To Date</label>
                            <input
                                type="date"
                                name="date_to"
                                value="{{ request('date_to', request('end_date')) }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white outline-none transition focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <a
                            href="{{route('orders.index')}}"
                            class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition cursor-pointer font-medium">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Clear</span>
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm transition cursor-pointer font-medium">
                            <i class="fa-solid fa-filter text-xs"></i>
                            <span>Filters</span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Orders Table Container -->
            <div id="orders-table-container" class="px-6 pb-6 overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <!-- Table Head -->
                    <thead class="border border-gray-200">
                    <tr class="bg-gray-200/30 border-b border-gray-300 text-gray-800 text-xs uppercase font-bold">
                        <th class="px-4 py-3.5 border-r border-gray-300 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Order #</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Customer</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Phone</th>
                        <th class="px-4 py-3.5 border-r border-gray-300 text-center">Status</th>
                        <th class="px-4 py-3.5 border-r border-gray-300 text-center">Payment</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Date</th>
                        <th class="px-4 py-3.5 text-center w-32">Action</th>
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y border border-gray-200 divide-gray-200 text-sm text-gray-700">

                    @forelse($orders as $key => $order)

                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                            <!-- # Column -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                {{ $orders->firstItem() + $key }}
                            </td>

                            <!-- Order Number -->
                            <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-900">
                                #{{ $order->order_number }}
                            </td>

                            <!-- Customer Name -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-800 font-medium">
                                {{ $order->name }}
                            </td>

                            <!-- Phone -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                {{ $order->phone }}
                            </td>

                            <!-- Status Tag (Original Styling Preserved) -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase
                        {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}">
                        {{ $order->status }}
                    </span>
                            </td>

                            <!-- Payment Status -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-center uppercase text-xs font-semibold text-gray-700">
                                {{ $order->payment_status }}
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                            <!-- Action Buttons (Original Styling Preserved) -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">

                                    <!-- View Button -->
                                    <a href="{{ route('orders.show', $order->id) }}"
                                       title="View Order Details"
                                       class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-100 transition-all duration-200 shadow-xs cursor-pointer">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Delete Order">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-box-archive text-3xl mb-2 block text-gray-300"></i>
                                <span>No orders found matching your criteria.</span>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

@endsection
