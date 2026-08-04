@extends('layouts.app')

@section('content')
    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Section -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admin Store Settings</h1>
                <p class="text-xs text-gray-500 mt-1">Manage store details, contact info, and frontend visibility toggles.</p>
            </div>
        </div>

        <!-- Success Alert (Dynamic Session & AJAX) -->
        <div id="ajax-alert" class="hidden mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span id="ajax-alert-message"></span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Dynamic Resource Route Setup --}}
        @php
            $isUpdate = isset($store) && $store->id;
            $actionUrl = $isUpdate ? route('admin-store.update', $store->id) : route('admin-store.store');
        @endphp

        <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($isUpdate)
            @method('PUT')
        @endif

        <!-- Main Single Unified Card -->
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0a2 2 0 012-2v-5a2 2 0 012-2h2a2 2 0 012 2v5a2 2 0 012 2m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">General Store Configuration</h2>
                        <p class="text-xs text-gray-500">Update logo, branding title, contact channels, and frontend visibility</p>
                    </div>
                </div>

                <!-- Store Logo Upload Section -->
                <div class="flex items-center gap-6 mb-8 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                    <div class="relative w-20 h-20 shrink-0">

                        <!-- Image Preview -->
                        <img id="logo-preview"
                             src="{{ (isset($store) && $store->logo) ? asset('storage/' . $store->logo) : '#' }}"
                             class="{{ (isset($store) && $store->logo) ? '' : 'hidden' }} w-full h-full rounded-2xl object-cover bg-white border-2 border-emerald-500 shadow-sm">

                        <!-- Icon Fallback -->
                        <div id="logo-icon-placeholder"
                             class="{{ (isset($store) && $store->logo) ? 'hidden' : '' }} w-20 h-20 rounded-2xl bg-emerald-50 border-2 border-dashed border-emerald-300 flex items-center justify-center text-emerald-600 shadow-sm">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Upload Icon -->
                        <label for="store-logo" class="absolute -bottom-2 -right-2 bg-emerald-600 hover:bg-emerald-700 text-white p-2 rounded-xl cursor-pointer shadow-md transition-all z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            </svg>
                        </label>
                        <input type="file" id="store-logo" name="logo" class="hidden" accept="image/*" onchange="previewLogo(event)">

                        <!-- Delete / Reset Button (Cut Icon) -->
                        <button type="button"
                                id="remove-logo-btn"
                                onclick="deleteLogo()"
                                class="{{ (isset($store) && $store->logo) ? '' : 'hidden' }} absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-md transition-all cursor-pointer z-10">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold text-gray-800">Store Brand Logo</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5">PNG, SVG, or JPG. Recommended size 512x512px.</p>
                        @error('logo')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Input Fields Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">

                    <!-- Store Title -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Title / Name</label>
                        <input type="text" name="title" value="{{ old('title', $store->title ?? '') }}" placeholder="My Awesome Store" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        @error('title')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $store->email ?? '') }}" placeholder="support@store.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        @error('email')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Contact Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $store->phone ?? '') }}" placeholder="+92 300 1234567"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        @error('phone')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Physical Address -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Physical Address</label>
                    <textarea name="address" rows="3" placeholder="Enter full shop/office address..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/30 text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all resize-none">{{ old('address', $store->address ?? '') }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- FRONTEND DISPLAY TOGGLES (Integrated inside Main Card) -->
                <div class="pt-6 border-t border-gray-100 mb-8">
                    <h3 class="text-xs font-bold text-gray-900 mb-3 uppercase tracking-wider">Frontend Display Settings</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Toggle 1: Show Logo -->
                        <div class="p-4 rounded-2xl bg-gray-50/60 border border-gray-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-800">Show Store Logo</h4>
                                <p class="text-[11px] text-gray-400 mt-0.5">Display logo on navbar/footer</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_logo" value="1" class="sr-only peer"
                                    {{ old('is_logo', $store->is_logo ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <!-- Toggle 2: Show Title -->
                        <div class="p-4 rounded-2xl bg-gray-50/60 border border-gray-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-semibold text-gray-800">Show Store Title</h4>
                                <p class="text-[11px] text-gray-400 mt-0.5">Display store name text</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_title" value="1" class="sr-only peer"
                                    {{ old('is_title', $store->is_title ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-6 py-3 rounded-xl shadow-md shadow-emerald-600/20 transition-all inline-flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Save Store Settings</span>
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript For Image Preview & Direct Server Deletion (jQuery Version) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 1. Image Preview Function
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo-preview').attr('src', e.target.result).removeClass('hidden');
                    $('#logo-icon-placeholder').addClass('hidden');
                    $('#remove-logo-btn').removeClass('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // 2. Direct Delete Logo Function (using $.ajax)
        function deleteLogo() {
            const fileInput = $('#store-logo')[0];

            // Agar user ne local system se new image select ki ho aur upload na ki ho
            if (fileInput.files && fileInput.files[0]) {
                $('#store-logo').val('');
                resetLogoUI();
                return;
            }

            const storeId = "{{ $store->id ?? 'null' }}";

            if (storeId === 'null') {
                resetLogoUI();
                return;
            }

            const destroyUrl = "{{ route('admin-store.destroy', ':id') }}".replace(':id', storeId);
            const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';

            $.ajax({
                url: destroyUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        resetLogoUI();
                        showAlert(response.message || 'Store logo deleted successfully!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Delete Error:", error);
                }
            });
        }

        // 3. Helper Function to Reset Preview UI
        function resetLogoUI() {
            $('#store-logo').val('');
            $('#logo-preview').addClass('hidden').attr('src', '#');
            $('#logo-icon-placeholder').removeClass('hidden');
            $('#remove-logo-btn').addClass('hidden');
        }

        // 4. Helper Function to Show Success Alert
        function showAlert(message) {
            $('#ajax-alert-message').text(message);
            $('#ajax-alert').removeClass('hidden');
        }
    </script>
@endsection
