@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto px-4 py-10 space-y-6">

        <!-- ORDER HEADER -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Order #{{ $order->order_number ?? $order->id }}
                    </h2>
                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-xs"></i>
                        Placed on {{ $order->created_at->format('d M Y h:i A') }}
                    </p>
                </div>

                <div>
                    <!-- STATUS BADGE WITH DYNAMIC COLORS -->
                    <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide shadow-sm
                        @if($order->status == 'pending')
                            bg-amber-50 text-amber-700 border border-amber-200
                        @elseif($order->status == 'delivered')
                           bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($order->status == 'completed')
                            bg-emerald-50 text-emerald-700 border border-emerald-200
                        @else
                            bg-rose-50 text-rose-700 border border-rose-200
                        @endif">

                        <!-- Status Dot -->
                        <span class="w-2 h-2 rounded-full my-auto mr-2
                        @if($order->status == 'pending') bg-amber-500
                        @elseif($order->status == 'delivered') bg-emerald-500
                        @elseif($order->status == 'completed') bg-emerald-500
                        @else bg-rose-500 @endif"></span>

                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- PRODUCTS -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-box text-gray-400 text-base"></i>
                Order Items ({{ $order->items->count() }})
            </h3>

            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-5 p-4 rounded-xl bg-gray-50/70 border border-gray-100 hover:shadow-sm transition">

                        <!-- IMAGE -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 mx-auto sm:mx-0">
                            @if($item->product && $item->product->mainVariantImage && $item->product->mainVariantImage->image_path)
                                <img src="{{ asset('storage/'.$item->product->mainVariantImage->image_path) }}"
                                     class="w-full h-full object-cover rounded-xl shadow-inner border border-gray-200">
                            @else
                                <div
                                    class="w-full h-full bg-gray-200 rounded-xl flex items-center justify-center text-gray-400 text-xs text-center p-1 font-medium">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <!-- DETAILS -->
                        <div class="flex-1 space-y-2 text-center sm:text-left">
                            <h4 class="text-gray-900 font-bold text-base sm:text-lg">
                                {{ $item->product->name ?? '' }}
                            </h4>

                            <!-- VARIANTS -->
                            <div class="flex flex-wrap justify-center sm:justify-start gap-2 text-xs">
                                @if($item->variant && $item->variant->color_name)
                                    <span
                                        class="px-3 py-1 bg-blue-50 text-blue-600 font-medium rounded-md border border-blue-100">
                                        Color: {{ $item->variant->color_name }}
                                    </span>
                                @endif

                                @if($item->variant && $item->variant->size)
                                    <span
                                        class="px-3 py-1 bg-purple-50 text-purple-600 font-medium rounded-md border border-purple-100">
                                        Size: {{ $item->variant->size }}
                                    </span>
                                @endif
                            </div>

                            <!-- QUANTITY & EACH PRICE (LEFT SIDE) -->
                            <div
                                class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <span>Quantity:</span>
                                    <span class="font-bold text-gray-800 bg-gray-200/80 px-2 py-0.5 rounded-md text-xs">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <span class="text-gray-300 hidden sm:inline">|</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-medium">
                                    Rs {{ number_format($item->price) }} each
                                </span>
                            </div>
                        </div>

                        <!-- PRICE (RIGHT SIDE) -->
                        <div
                            class="text-center sm:text-right sm:border-l sm:border-gray-200 sm:pl-6 pt-3 sm:pt-0 min-w-[120px]">
                            <p class="text-emerald-600 font-extrabold text-lg">
                                Rs {{ number_format($item->price * $item->quantity) }}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- TOTAL & ACTIONS SECTION (BOTTOM) -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">

            <div class="flex justify-between items-center bg-emerald-50/50 p-4 rounded-xl border border-emerald-100/50">
                <p class="text-gray-700 text-base sm:text-lg font-semibold">
                    Grand Total
                </p>
                <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">
                    Rs {{ number_format($order->total) }}
                </h3>
            </div>

            <!-- ACTION BUTTONS -->
            @if($order->status == 'pending' || $order->status == 'cancelled')
                <div class="flex justify-end pt-2 border-t border-gray-100">

                    <!-- CANCEL BUTTON -->
                    @if($order->status == 'pending')
                        <form id="cancelForm-{{ $order->id }}" action="{{ route('order.cancel', $order->id) }}"
                              method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="button"
                                    class="cancel-btn w-full sm:w-auto px-6 py-3 font-semibold text-sm rounded-xl bg-rose-500 text-white hover:bg-rose-600 active:scale-[0.98] transition-all shadow-md shadow-rose-500/10 flex items-center justify-center gap-2"
                                    data-id="{{ $order->id }}">
                                <i class="fa-solid fa-xmark text-xs"></i>
                                Cancel Order
                            </button>
                        </form>
                    @endif

                <!-- RESTORE BUTTON -->
                    @if($order->status == 'cancelled')
                        <form id="restoreForm-{{ $order->id }}" action="{{ route('order.restore', $order->id) }}"
                              method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="button"
                                    class="restore-btn w-full sm:w-auto px-6 py-3 font-semibold text-sm rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 active:scale-[0.98] transition-all shadow-md shadow-indigo-600/10 flex items-center justify-center gap-2"
                                    data-id="{{ $order->id }}">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                                Restore Order
                            </button>
                        </form>
                    @endif

                </div>
            @endif

        </div>

    </div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // CANCEL
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    let id = this.dataset.id;

                    Swal.fire({
                        title: "Cancel Order?",
                        text: "Are you sure you want to cancel this order? This action can be reversed.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#f43f5e",
                        cancelButtonColor: "#6b7280",
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, keep it"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('cancelForm-' + id).submit();
                        }
                    });
                });
            });

            // RESTORE
            document.querySelectorAll('.restore-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    let id = this.dataset.id;

                    Swal.fire({
                        title: "Restore Order?",
                        text: "Do you want to restore this order back to active state?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#4f46e5",
                        cancelButtonColor: "#6b7280",
                        confirmButtonText: "Yes, restore it!",
                        cancelButtonText: "No"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('restoreForm-' + id).submit();
                        }
                    });
                });
            });

        });
    </script>
@endpush
