@extends('layouts.app')

@section('content')

    <div class="mx-auto py-6 px-6">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ !empty($brand) ? 'Edit Brand' : 'Create Brand' }}
                    </h2>
                </div>

                <a href="{{ route('brands.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Brands</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ !empty($brand->id) ? route('brands.update', $brand->id) : route('brands.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                @if(!empty($brand))
                    @method('PUT')
                @endif

                <div class="grid gap-6 mb-8 md:grid-cols-2">

                    <!-- Brand Name (Floating Label) - FULL WIDTH FIXED -->
                    <div class="relative md:col-span-2">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none">
                            <i class="fa-solid fa-tag"></i>
                        </span>

                        <input type="text"
                               name="name"
                               id="brand_name"
                               required
                               value="{{ old('name', $brand->name ?? '') }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="brand_name"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                               peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                            Brand Name
                        </label>

                        @error('name')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Hidden Slug Input -->
                    <input type="hidden" name="slug" id="brand_slug" value="{{ old('slug', $brand->slug ?? '') }}">

                    <!-- Brand Image -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Brand Logo / Image
                        </label>

                        <input type="file"
                               name="image"
                               id="brand_image_input"
                               accept="image/*"
                               class="bg-gray-50/50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:outline-none focus:border-emerald-500 block w-full px-4 py-2.5 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition duration-200 cursor-pointer">

                        <!-- Image Preview Box -->
                        <div id="image_preview_container" class="{{ !empty($brand->image) ? '' : 'hidden' }} mt-4 flex items-center gap-3">
                            <span id="preview_label" class="text-xs text-gray-500 font-medium">
                                {{ !empty($brand->image) ? 'Current Image:' : 'Selected Preview:' }}
                            </span>
                            <div class="inline-flex items-center justify-center h-20 w-20 p-1 bg-white border border-gray-200 rounded-xl shadow-sm">
                                <img id="brand_image_preview"
                                     src="{{ !empty($brand->image) ? asset('storage/' . $brand->image) : '#' }}"
                                     alt="Brand Logo Preview"
                                     class="h-full w-full object-contain rounded-lg bg-gray-50">
                            </div>
                        </div>

                        @error('image')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <!-- Cancel Button -->
                    <a href="{{ route('brands.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition duration-300 shadow-sm border border-gray-200/70 group text-sm">
                        <i class="fa-solid fa-xmark text-gray-500 group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <!-- Save/Update Button -->
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition duration-300 shadow-sm group cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ !empty($brand) ? 'Update' : 'Save' }}</span>
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- Scripts -->
    <script>
        // 1. Auto Slug Generator
        document.getElementById('brand_name').addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            document.getElementById('brand_slug').value = slug;
        });

        // 2. Instant Live Image Preview
        document.getElementById('brand_image_input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image_preview_container');
            const previewImage = document.getElementById('brand_image_preview');
            const previewLabel = document.getElementById('preview_label');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    previewLabel.textContent = 'New Image Preview:';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection
