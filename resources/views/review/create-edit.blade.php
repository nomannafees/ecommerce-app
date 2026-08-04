@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ !empty($review) ? 'Edit Review' : 'Create Review' }}
                </h2>

                <!-- Header Action Button -->
                <a href="{{ route('reviews.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Reviews</span>
                </a>

            </div>

            <!-- Form -->
            <form action="{{ !empty($review) ? route('reviews.update', $review->id) : route('reviews.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @if(!empty($review))
                    @method('PUT')
                @endif

                <div class="grid gap-6 md:grid-cols-2">

                    <!-- User Select -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>

                        <select name="user_id"
                                id="review_user"
                                required
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('user_id', $review->user_id ?? '') == '' ? 'selected' : '' }}>
                                Select User
                            </option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $review->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach

                        </select>

                        <label for="review_user"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            User
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>

                        @error('user_id')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Select -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-box text-sm"></i>
                        </span>

                        <select name="product_id"
                                id="review_product"
                                required
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('product_id', $review->product_id ?? '') == '' ? 'selected' : '' }}>
                                Select Product
                            </option>

                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id', $review->product_id ?? '') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach

                        </select>

                        <label for="review_product"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Product
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>

                        @error('product_id')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Select -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-receipt text-sm"></i>
                        </span>

                        <select name="order_id"
                                id="review_order"
                                required
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('order_id', $review->order_id ?? '') == '' ? 'selected' : '' }}>
                                Select Order
                            </option>

                            @foreach($orders as $order)
                                <option value="{{ $order->id }}"
                                    {{ old('order_id', $review->order_id ?? '') == $order->id ? 'selected' : '' }}>
                                    Order #{{ $order->order_number ?? $order->id }}
                                </option>
                            @endforeach

                        </select>

                        <label for="review_order"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Order #
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>

                        @error('order_id')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating Select -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-star text-sm"></i>
                        </span>

                        <select name="rating"
                                id="review_rating"
                                required
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="" disabled {{ old('rating', $review->rating ?? '') == '' ? 'selected' : '' }}>
                                Select Rating
                            </option>
                            <option value="5" {{ old('rating', $review->rating ?? '') == 5 ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ old('rating', $review->rating ?? '') == 4 ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ old('rating', $review->rating ?? '') == 3 ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ old('rating', $review->rating ?? '') == 2 ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ old('rating', $review->rating ?? '') == 1 ? 'selected' : '' }}>1 Star</option>

                        </select>

                        <label for="review_rating"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Rating
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>

                        @error('rating')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Select -->
                    <div class="relative w-full md:col-span-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </span>

                        <select name="is_approved"
                                id="review_status"
                                class="peer w-full pl-11 pr-10 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 appearance-none cursor-pointer">

                            <option value="1" {{ old('is_approved', $review->is_approved ?? 1) == 1 ? 'selected' : '' }}>Approved / Visible</option>
                            <option value="0" {{ old('is_approved', $review->is_approved ?? 1) == 0 ? 'selected' : '' }}>Hidden / Pending</option>

                        </select>

                        <label for="review_status"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Status
                        </label>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none z-10">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>

                        @error('is_approved')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload & Dynamic Previews -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-xs font-medium text-gray-500">Review Images</label>
                        <input type="file"
                               name="images[]"
                               id="review_images_input"
                               multiple
                               accept="image/png, image/jpeg, image/jpg, image/webp"
                               class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl bg-white p-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition duration-200 cursor-pointer">

                        <!-- Selected Files Live Preview Grid -->
                        <div id="new_images_preview_container" class="hidden mt-4">
                            <span class="text-xs font-semibold text-emerald-600 block mb-2">New Images Selected Preview:</span>
                            <div id="new_images_preview_grid" class="flex items-center gap-3 flex-wrap">
                                <!-- Previews appended via jQuery -->
                            </div>
                        </div>

                        <!-- Existing Saved Images -->
                        @if(!empty($review) && $review->images && $review->images->isNotEmpty())
                            <div class="mt-4">
                                <span class="text-xs font-semibold text-gray-500 block mb-2">Current Saved Images:</span>
                                <div class="flex items-center gap-3 flex-wrap">
                                    @foreach($review->images as $img)
                                        <div class="relative group">
                                            <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                                     alt="Review Image"
                                                     class="w-16 h-16 object-cover rounded-xl border border-gray-200 shadow-xs group-hover:scale-105 transition-transform duration-200">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @error('images')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="relative w-full md:col-span-2">
                        <span class="absolute left-4 top-4 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-comment-dots text-sm"></i>
                        </span>

                        <textarea name="comment"
                                  id="review_comment"
                                  rows="4"
                                  placeholder=" "
                                  class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">{{ old('comment', $review->comment ?? '') }}</textarea>

                        <label for="review_comment"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Comment
                        </label>

                        @error('comment')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bottom Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-8 mt-8 border-t border-gray-100">

                    <a href="{{ route('reviews.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200 cursor-pointer text-sm group">
                        <i class="fa-solid fa-xmark text-gray-500 text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer group active:scale-[0.98]">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ !empty($review) ? 'Update Review' : 'Save Review' }}</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- jQuery CDN (Agar app layout me pehle se included nahi hai to add karein) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery Multiple Images Live Preview Script -->
    <script>
        $(document).ready(function() {
            $('#review_images_input').on('change', function(e) {
                const files = e.target.files;
                const $container = $('#new_images_preview_container');
                const $grid = $('#new_images_preview_grid');

                // Clear existing previews
                $grid.empty();

                if (files && files.length > 0) {
                    $container.removeClass('hidden');

                    $.each(files, function(index, file) {
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const imgHtml = `
                                    <div class="relative group">
                                        <img src="${e.target.result}"
                                             alt="Preview"
                                             class="w-16 h-16 object-cover rounded-xl border border-emerald-300 shadow-xs group-hover:scale-105 transition-transform duration-200">
                                    </div>
                                `;
                                $grid.append(imgHtml);
                            };

                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    $container.addClass('hidden');
                }
            });
        });
    </script>

@endsection
