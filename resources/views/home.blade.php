@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="min-h-screen bg-slate-50/50 py-6 sm:py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <!-- 1. Top Collapsible Filter Control -->
            <div x-data="{ open: {{ (request('start_date') || request('end_date')) ? 'true' : 'false' }} }" class="mb-6">

                <!-- Toggle Button Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="flex items-center gap-3 flex-wrap">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all duration-200 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                            </svg>
                            <span>Filter Orders</span>
                            <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>

                        @if(request('start_date') || request('end_date'))
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Filter Active
                            </span>
                        @endif
                    </div>

                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 underline transition-colors self-start sm:self-auto">
                            Clear Filters
                        </a>
                    @endif
                </div>

                <!-- Collapsible Form Body -->
                <div x-show="open" x-collapse x-cloak class="mt-3 bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs">
                    <form action="{{ route('home') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">

                        <div class="w-full flex-1">
                            <label for="start_date" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate) }}" class="w-full rounded-xl border border-gray-200 bg-white text-sm text-gray-700 py-2.5 px-3.5 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                        </div>

                        <div class="w-full flex-1">
                            <label for="end_date" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate) }}" class="w-full rounded-xl border border-gray-200 bg-white text-sm text-gray-700 py-2.5 px-3.5 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all cursor-pointer">
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-x-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition-all duration-200 cursor-pointer">
                                <i class="fa-solid fa-filter text-xs"></i>
                                Filter
                            </button>
                        </div>

                    </form>
                </div>

            </div>

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 sm:mb-8">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        Order Overview
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Real-time status tracking for all store orders.
                    </p>
                </div>
                <div class="flex">
                    <a href="{{ route('orders.index', array_filter(['date_from' => request('start_date'), 'date_to' => request('end_date')])) }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center gap-x-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition-all duration-200 cursor-pointer">
                        <svg class="-ml-0.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm0 5.25h.007v.008H3.75V12zm0 5.25h.007v.008H3.75v-.008z"/>
                        </svg>
                        <span>{{ $totalOrders }}</span> All Orders
                    </a>
                </div>
            </div>

            <!-- Clickable Cards Grid (Responsive: 1 col on mobile, 2 on sm, 4 on lg) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-10">

                <!-- 1. Delivered / Completed Orders -->
                <a href="{{ route('orders.index', array_filter(['status' => 'delivered', 'date_from' => request('start_date'), 'date_to' => request('end_date')])) }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 p-5 sm:p-6 text-white shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/25 hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Completed Orders</p>
                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $deliveredOrders }}</h3>
                        </div>
                        <div class="rounded-xl bg-white/15 backdrop-blur-md p-3 text-white border border-white/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-x-2 text-xs font-medium text-emerald-100 relative z-10">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-300"></span>
                        Successfully Delivered &rarr;
                    </div>
                </a>

                <!-- 2. Processing Orders -->
                <a href="{{ route('orders.index', array_filter(['status' => 'processing', 'date_from' => request('start_date'), 'date_to' => request('end_date')])) }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-800 p-5 sm:p-6 text-white shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-100">Processing Orders</p>
                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $processingOrders }}</h3>
                        </div>
                        <div class="rounded-xl bg-white/15 backdrop-blur-md p-3 text-white border border-white/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-x-2 text-xs font-medium text-blue-100 relative z-10">
                        <span class="inline-block h-2 w-2 rounded-full bg-blue-300 animate-ping"></span>
                        In Fulfillment &rarr;
                    </div>
                </a>

                <!-- 3. Pending Orders -->
                <a href="{{ route('orders.index', array_filter(['status' => 'pending', 'date_from' => request('start_date'), 'date_to' => request('end_date')])) }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 sm:p-6 text-white shadow-lg shadow-amber-500/10 hover:shadow-amber-500/25 hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-100">Pending Orders</p>
                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $pendingOrders }}</h3>
                        </div>
                        <div class="rounded-xl bg-white/15 backdrop-blur-md p-3 text-white border border-white/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-x-2 text-xs font-medium text-amber-100 relative z-10">
                        <span class="inline-block h-2 w-2 rounded-full bg-amber-200"></span>
                        Awaiting Action &rarr;
                    </div>
                </a>

                <!-- 4. Cancelled Orders -->
                <a href="{{ route('orders.index', array_filter(['status' => 'cancelled', 'date_from' => request('start_date'), 'date_to' => request('end_date')])) }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-red-700 p-5 sm:p-6 text-white shadow-lg shadow-rose-500/10 hover:shadow-rose-500/25 hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-rose-100">Cancelled Orders</p>
                            <h3 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">{{ $cancelledOrders }}</h3>
                        </div>
                        <div class="rounded-xl bg-white/15 backdrop-blur-md p-3 text-white border border-white/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-x-2 text-xs font-medium text-rose-100 relative z-10">
                        <span class="inline-block h-2 w-2 rounded-full bg-rose-200"></span>
                        Refunded / Returned &rarr;
                    </div>
                </a>

            </div>

            <!-- Financial Summary Heading Section -->
            <div class="mb-5">
                <h3 class="text-xl font-bold text-slate-800">Financial Summary</h3>
                <p class="text-sm text-slate-500 mt-1">Revenue performance broken down by order fulfillment status.</p>
            </div>

            <!-- 2 Main Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 sm:mb-10">
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                                <i class="fa-solid fa-wallet text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Active Revenue Pipeline</h4>
                                <p class="text-xs text-slate-400">Completed sales & orders in transit</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100/80">
                            <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider block mb-1">Delivered Revenue</span>
                            <div class="text-xl sm:text-2xl font-black text-emerald-900">Rs. {{ number_format($deliveredAmount, 2) }}</div>
                            <span class="text-xs text-emerald-600 font-medium mt-1 inline-block">Realized Earnings</span>
                        </div>
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100/80">
                            <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider block mb-1">Processing Value</span>
                            <div class="text-xl sm:text-2xl font-black text-blue-900">Rs. {{ number_format($processingAmount, 2) }}</div>
                            <span class="text-xs text-blue-600 font-medium mt-1 inline-block">In Transit / Packing</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Pending & Cancelled Status</h4>
                                <p class="text-xs text-slate-400">Awaiting confirmation and cancelled revenue tracking</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100/80">
                            <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider block mb-1">Pending Revenue</span>
                            <div class="text-xl sm:text-2xl font-black text-amber-900">Rs. {{ number_format($pendingAmount, 2) }}</div>
                            <span class="text-xs text-amber-600 font-medium mt-1 inline-block">Awaiting Action</span>
                        </div>
                        <div class="p-4 rounded-xl bg-rose-50/50 border border-rose-100/80">
                            <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider block mb-1">Cancelled Value</span>
                            <div class="text-xl sm:text-2xl font-black text-rose-900">Rs. {{ number_format($cancelledAmount, 2) }}</div>
                            <span class="text-xs text-rose-600 font-medium mt-1 inline-block">Lost / Returned Revenue</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Growth & Analytics Chart Section -->
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Order Growth & Analytics</h3>
                        <p class="text-xs text-slate-500 mt-1">Visualize high and low sales periods day-by-day or month-by-month</p>
                    </div>

                    <div class="flex items-center bg-slate-100 p-1 rounded-xl self-start sm:self-auto">
                        <button id="btnDaily" onclick="switchChartView('daily')" class="px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-900 shadow-xs transition-all cursor-pointer">
                            Daily (30 Days)
                        </button>
                        <button id="btnMonthly" onclick="switchChartView('monthly')" class="px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-900 transition-all cursor-pointer">
                            Monthly (12 Months)
                        </button>
                    </div>
                </div>

                <div class="relative h-72 sm:h-80 w-full">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Engine CDN & Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyLabels = {!! json_encode($dailyLabels ?? []) !!};
        const dailyOrders = {!! json_encode($dailyOrders ?? []) !!};

        const monthlyLabels = {!! json_encode($monthlyLabels ?? []) !!};
        const monthlyOrders = {!! json_encode($monthlyOrders ?? []) !!};

        const ctx = document.getElementById('ordersChart').getContext('2d');

        let ordersChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Orders Count',
                    data: dailyOrders,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { size: 11 }, precision: 0 }
                    }
                }
            }
        });

        function switchChartView(view) {
            const btnDaily = document.getElementById('btnDaily');
            const btnMonthly = document.getElementById('btnMonthly');

            if (view === 'daily') {
                ordersChart.data.labels = dailyLabels;
                ordersChart.data.datasets[0].data = dailyOrders;
                ordersChart.update();

                btnDaily.className = "px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-900 shadow-xs transition-all cursor-pointer";
                btnMonthly.className = "px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-900 transition-all cursor-pointer";
            } else {
                ordersChart.data.labels = monthlyLabels;
                ordersChart.data.datasets[0].data = monthlyOrders;
                ordersChart.update();

                btnMonthly.className = "px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg bg-white text-slate-900 shadow-xs transition-all cursor-pointer";
                btnDaily.className = "px-3 sm:px-4 py-1.5 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-900 transition-all cursor-pointer";
            }
        }
    </script>
@endsection
