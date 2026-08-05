@extends('frontend.layouts.app')

@section('content')


        <div class="max-w-7xl mx-auto px-3 sm:px-6 md:px-7 py-6 sm:py-10 space-y-6">

        <!-- ORDER HEADER -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Order #{{ $order->order_number ?? $order->id }}
                    </h2>
                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-2">
                        <!-- Calendar Badge Container -->
                        <span class="w-6 h-6 rounded-md bg-green-100 text-green-600 flex items-center justify-center shrink-0 border border-green-200/60 shadow-xs">
                            <i class="fa-regular fa-calendar text-xs"></i>
                        </span>

                        <!-- Text -->
                        <span>Placed on {{ $order->created_at->format('d M Y h:i A') }}</span>
                    </p>
                </div>

                <div>
                    <!-- STATUS BADGE WITH DYNAMIC COLORS -->
                    <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide shadow-sm
                        @if($order->status == 'pending')
                        bg-amber-50 text-amber-700 border border-amber-200
                        @elseif($order->status == 'delivered' || $order->status == 'completed')
                        bg-emerald-50 text-emerald-700 border border-emerald-200
                        @else
                        bg-rose-50 text-rose-700 border border-rose-200
                        @endif">

                        <!-- Status Dot -->
                        <span class="w-2 h-2 rounded-full my-auto mr-2
                        @if($order->status == 'pending') bg-amber-500
                        @elseif($order->status == 'delivered' || $order->status == 'completed') bg-emerald-500
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

                            <!-- QUANTITY & EACH PRICE -->
                            <div
                                class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-sm text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <span>Quantity:</span>
                                    <span class="font-bold text-gray-800 bg-gray-200/80 px-2 py-0.5 rounded-md text-xs">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <span class="text-gray-300 hidden sm:inline">|</span>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-normal">
                                    Rs {{ number_format($item->price) }} each
                                </span>
                            </div>
                        </div>

                        <!-- PRICE & REVIEW BUTTON -->
                        <div
                            class="text-center sm:text-right sm:border-l sm:border-gray-200 sm:pl-6 pt-3 sm:pt-0 min-w-[140px] flex flex-col items-center sm:items-end justify-center gap-2">
                            <p class="text-emerald-600 font-bold text-lg">
                                Rs {{ number_format($item->price * $item->quantity) }}
                            </p>

                            <!-- REVIEW ACTION CONTAINER -->
                            @if(in_array($order->status, ['delivered', 'completed']) && $item->product)
                                @php
                                    $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                        ->where('order_id', $order->id)
                                        ->where('product_id', $item->product_id)
                                        ->exists();
                                @endphp

                                <div id="review-action-{{ $item->product_id }}">
                                @if($hasReviewed)
                                    <!-- Reviewed Status Badge -->
                                        <span
                                            class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200">
                                            <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                                            Reviewed
                                        </span>
                                @else
                                    <!-- Write Review Button -->
                                        <button type="button"
                                                onclick="openReviewModal({{ $order->id }}, {{ $item->product_id }}, '{{ addslashes($item->product->name) }}')"
                                                class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm transition-all duration-200 cursor-pointer active:scale-95">
                                            <i class="fa-solid fa-star text-[10px]"></i>
                                            Write Review
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- TOTAL & ACTIONS SECTION -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 space-y-4">

            <div class="flex justify-between items-center bg-emerald-50/90 p-4 rounded-xl border border-emerald-100/50">
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
                              method="POST" class="w-full flex justify-center my-2">
                            @csrf
                            <button type="button"
                                    class="cancel-btn px-6 py-3 font-semibold text-sm rounded-xl bg-rose-500 text-white hover:bg-rose-600 active:scale-[0.98] transition-all shadow-md shadow-rose-500/10 flex items-center justify-center gap-2"
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

    <!-- REVIEW POPUP MODAL -->
    <div id="reviewModal"
         class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-xs flex items-center justify-center hidden p-4 transition-opacity duration-300 opacity-0">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95"
            id="reviewModalContainer">

            <!-- Modal Header -->
            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-500"></i>
                    Write a Review
                </h3>
                <button type="button" onclick="closeReviewModal()"
                        class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 hover:text-gray-700 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="ajaxReviewForm" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="order_id" id="modal_order_id">
                <input type="hidden" name="product_id" id="modal_product_id">

                <!-- Product Name Display -->
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Product</span>
                    <span id="modal_product_name" class="font-bold text-gray-800 text-sm sm:text-base"></span>
                </div>

                <!-- Rating Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Your Rating</label>
                    <select name="rating" required
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:outline-none transition-all bg-white">
                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars - Excellent)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Stars - Very Good)</option>
                        <option value="3">⭐⭐⭐ (3 Stars - Average)</option>
                        <option value="2">⭐⭐ (2 Stars - Poor)</option>
                        <option value="1">⭐ (1 Star - Terrible)</option>
                    </select>
                </div>

                <!-- Description / Comment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Review Description</label>
                    <textarea name="comment" rows="4"
                              placeholder="Tell us what you liked or disliked about this product..."
                              class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:outline-none transition-all placeholder:text-gray-300"></textarea>
                </div>

                <!-- Images Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Attach Images (Optional)</label>
                    <input type="file" id="reviewImagesInput" multiple accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">

                    <!-- PREVIEW CONTAINER -->
                    <div id="imagePreviewContainer" class="flex flex-wrap gap-3 mt-3"></div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeReviewModal()"
                            class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="reviewSubmitBtn"
                            class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold shadow-sm transition-all flex items-center gap-2">
                        <span>Submit Review</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Array to hold selected files dynamically
        let selectedFiles = [];

        // PREVIEW AND DELETE IMAGE HANDLER
        document.getElementById('reviewImagesInput').addEventListener('change', function (e) {
            let files = Array.from(e.target.files);

            // Append new selected files
            files.forEach(file => {
                // Duplicate selection check
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });

            renderImagePreviews();
            // Clear input so user can pick same file again if needed
            this.value = '';
        });

        function renderImagePreviews() {
            let previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();

                reader.onload = function (e) {
                    let wrapper = document.createElement('div');
                    wrapper.className = "relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 shadow-xs group";

                    wrapper.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeSelectedImage(${index})"
                                class="absolute top-1 right-1 w-5 h-5 bg-rose-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-sm hover:bg-rose-600 transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;

                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        }

        function removeSelectedImage(index) {
            selectedFiles.splice(index, 1);
            renderImagePreviews();
        }

        // REVIEW MODAL FUNCTIONS
        function openReviewModal(orderId, productId, productName) {
            let form = document.getElementById('ajaxReviewForm');
            form.reset();

            selectedFiles = [];
            document.getElementById('imagePreviewContainer').innerHTML = '';

            document.getElementById('modal_order_id').value = orderId;
            document.getElementById('modal_product_id').value = productId;
            document.getElementById('modal_product_name').innerText = productName;

            let modal = document.getElementById('reviewModal');
            let modalContainer = document.getElementById('reviewModalContainer');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContainer.classList.remove('scale-95');
                modalContainer.classList.add('scale-100');
            }, 10);
        }

        function closeReviewModal() {
            let modal = document.getElementById('reviewModal');
            let modalContainer = document.getElementById('reviewModalContainer');

            modal.classList.add('opacity-0');
            modalContainer.classList.remove('scale-100');
            modalContainer.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        document.addEventListener("DOMContentLoaded", function () {

            // AJAX REVIEW SUBMISSION
            document.getElementById('ajaxReviewForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Append managed images into FormData array
                selectedFiles.forEach(file => {
                    formData.append('images[]', file);
                });

                let productId = document.getElementById('modal_product_id').value;
                let btn = document.getElementById('reviewSubmitBtn');

                btn.disabled = true;
                btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Submitting...`;

                fetch("{{ route('reviews.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json().then(data => ({status: response.status, body: data})))
                    .then(res => {
                        if (res.status === 200 || res.status === 201) {
                            closeReviewModal();

                            // Dynamically update the UI without page reload
                            let actionContainer = document.getElementById('review-action-' + productId);
                            if (actionContainer) {
                                actionContainer.innerHTML = `
                                    <span class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i>
                                        Reviewed
                                    </span>
                                `;
                            }

                            Swal.fire({
                                title: "Success!",
                                text: res.body.message || "Thank you! Your review has been submitted successfully.",
                                icon: "success",
                                confirmButtonColor: "#f59e0b"
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: res.body.message || res.body.error || "Failed to submit review.",
                                icon: "error",
                                confirmButtonColor: "#ef4444"
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: "Server Error",
                            text: "Something went wrong. Please try again later.",
                            icon: "error",
                            confirmButtonColor: "#ef4444"
                        });
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = `<span>Submit Review</span>`;
                    });
            });

            // CANCEL ORDER
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

            // RESTORE ORDER
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
