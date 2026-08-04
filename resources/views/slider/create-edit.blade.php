@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card Container -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ !empty($slider) ? 'Edit Slider' : 'Create Slider' }}
                </h2>

                <!-- List Sliders Button -->
                <a href="{{ route('sliders.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Sliders</span>
                </a>

            </div>

            <!-- Form -->
            <form action="{{ !empty($slider) ? route('sliders.update', $slider->id) : route('sliders.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @if(!empty($slider))
                    @method('PUT')
                @endif

                <div class="grid gap-6">

                    <!-- Heading (Floating Border Style) -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-heading text-sm"></i>
                        </span>

                        <input type="text"
                               name="heading"
                               id="slider_heading"
                               value="{{ old('heading', $slider->heading ?? '') }}"
                               placeholder=" "
                               class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="slider_heading"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Heading
                        </label>

                        @error('heading')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (Floating Border Style) -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-4 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-align-left text-sm"></i>
                        </span>

                        <textarea name="description"
                                  id="slider_description"
                                  rows="5"
                                  placeholder=" "
                                  class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">{{ old('description', $slider->description ?? '') }}</textarea>

                        <label for="slider_description"
                               class="absolute left-3 -top-2.5 bg-white px-1.5 text-xs text-gray-500 peer-focus:text-emerald-600 font-medium pointer-events-none z-20 transition-colors duration-200">
                            Description
                        </label>

                        @error('description')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload & Live Preview -->
                    <div>
                        <label class="block mb-2 text-xs font-medium text-gray-500">Slider Image</label>
                        <input type="file"
                               name="image"
                               id="slider_image_input"
                               accept="image/png, image/jpeg, image/jpg, image/webp"
                               class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl bg-white p-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition duration-200 cursor-pointer">

                        <!-- Image Preview Box -->
                        <div id="image_preview_container" class="{{ (!empty($slider) && !empty($slider->image)) ? '' : 'hidden' }} mt-4">
                            <span id="preview_label" class="text-xs font-semibold text-gray-500 block mb-2">
                                {{ (!empty($slider) && !empty($slider->image)) ? 'Current Saved Image:' : 'Selected Image Preview:' }}
                            </span>
                            <div class="relative group inline-block">
                                <img id="slider_image_preview"
                                     src="{{ (!empty($slider) && !empty($slider->image)) ? asset('storage/' . $slider->image) : '#' }}"
                                     alt="Slider Image Preview"
                                     class="w-44 h-28 object-cover rounded-xl border border-gray-200 shadow-xs bg-gray-50">
                            </div>
                        </div>

                        @error('image')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bottom Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-8 mt-8 border-t border-gray-100">

                    <a href="{{ route('sliders.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200 cursor-pointer text-sm group">
                        <i class="fa-solid fa-xmark text-gray-500 text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer group active:scale-[0.98]">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ !empty($slider) ? 'Update Slider' : 'Save Slider' }}</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- Live Image Preview Script -->
    <script>
        document.getElementById('slider_image_input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image_preview_container');
            const previewImage = document.getElementById('slider_image_preview');
            const previewLabel = document.getElementById('preview_label');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    previewLabel.textContent = 'Selected Image Preview:';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection
