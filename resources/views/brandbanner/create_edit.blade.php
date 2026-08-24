@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ isset($brandsBanner) ? 'Edit Brands Banner' : 'Create Brands Banner' }}
                </h2>

                <!-- Header Action Button -->
                <a href="{{ route('brandbanners.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-list-check text-xs"></i>
                    <span>List Brands Banners</span>
                </a>

            </div>

            <!-- Form -->
            <form action="{{ isset($brandsBanner) ? route('brandbanners.update', $brandsBanner->id) : route('brandbanners.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @if(isset($brandsBanner))
                    @method('PUT')
                @endif

                <div class="grid gap-6 md:grid-cols-2">

                    <!-- Name Input (Floating Border Label Style) -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-tag text-sm"></i>
                        </span>

                        <input
                            type="text"
                            name="name"
                            id="banner_name"
                            value="{{ old('name', $brandsBanner->name ?? '') }}"
                            placeholder=" "
                            class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200"
                            required>

                        <label for="banner_name"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Name
                        </label>

                        @error('name')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button Name Input (Floating Border Label Style) -->
                    <div class="relative w-full">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-link text-sm"></i>
                        </span>

                        <input
                            type="text"
                            name="button_name"
                            id="banner_button_name"
                            value="{{ old('button_name', $brandsBanner->button_name ?? '') }}"
                            placeholder=" "
                            class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="banner_button_name"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Button Name
                        </label>

                        @error('button_name')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description Textarea (Full Width) -->
                    <div class="relative w-full md:col-span-2">
                        <span class="absolute left-4 top-4 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-align-left text-sm"></i>
                        </span>

                        <textarea
                            name="description"
                            id="banner_description"
                            rows="3"
                            placeholder=" "
                            class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">{{ old('description', $brandsBanner->description ?? '') }}</textarea>

                        <label for="banner_description"
                               class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Description
                        </label>

                        @error('description')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload (Full Width) -->
                    <div class="relative w-full md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Banner Image</label>
                        <div class="flex items-center gap-4">
                            <input
                                type="file"
                                name="image"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition duration-200 border border-gray-200 rounded-xl p-2 bg-white cursor-pointer">
                        </div>

                        @if(isset($brandsBanner) && $brandsBanner->image)
                            <div class="mt-3">
                                <span class="block text-xs text-gray-400 mb-1">Current Image:</span>
                                <img src="{{ asset('storage/' . $brandsBanner->image) }}" class="h-16 w-24 object-cover rounded-xl border border-gray-200 shadow-xs">
                            </div>
                        @endif

                        @error('image')
                        <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bottom Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-8 mt-8 border-t border-gray-100">

                    <a href="{{ route('brandbanners.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 transition duration-200 cursor-pointer text-sm group">
                        <i class="fa-solid fa-xmark text-gray-500 text-xs group-hover:scale-110 transition-transform"></i>
                        <span>Cancel</span>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer group active:scale-[0.98]">
                        <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                        <span>{{ isset($brandsBanner) ? 'Update' : 'Save' }}</span>
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
