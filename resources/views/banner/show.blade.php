@extends('layouts.app')

@section('content')
    <div class="mx-auto p-6">
        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden p-6">

            <!-- Header -->
            <div class="flex items-center justify-between pb-6 border-b border-gray-200 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Banner Display Settings</h2>
                <a href="{{ route('banners.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition duration-200">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Back</span>
                </a>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
        @endif

        <!-- Update Form -->
            <form action="{{ route('banners.update', $banner->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden inputs to retain values -->
                <input type="hidden" name="name" value="{{ $banner->name }}">
                <input type="hidden" name="description" value="{{ $banner->description }}">

                <div class="space-y-6">

                    <!-- TOP SECTION: Banner Information -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-4">Banner Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-stretch">

                            <!-- Col 1: Image -->
                            <div class="p-4 bg-gray-50/50 border border-gray-200 rounded-xl flex items-center gap-4">
                                <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden border border-gray-200 bg-white">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-400 bg-gray-100">No Image</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="block text-xs font-bold uppercase text-gray-400 tracking-wide">Image Preview</span>
                                    <p class="text-xs text-gray-500 mt-1">Frontend preview image for this banner.</p>
                                </div>
                            </div>

                            <!-- Col 2: Name / Title -->
                            <div class="p-4 bg-gray-50/50 border border-gray-200 rounded-xl flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">
                                    <i class="fa-solid fa-heading"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Name</span>
                                    <p class="text-sm font-bold text-gray-800 mt-0.5 truncate">{{ $banner->name ?? 'No Name Provided' }}</p>
                                </div>
                            </div>

                            <!-- Col 3: Description -->
                            <div class="p-4 bg-gray-50/50 border border-gray-200 rounded-xl flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">
                                    <i class="fa-solid fa-align-left"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wide">Description</span>
                                    <p class="text-sm text-gray-700 mt-0.5 line-clamp-2">{{ $banner->description ?? 'No Description Provided' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- BOTTOM SECTION: Display Settings -->
                    <div class="p-5 bg-gray-50/30 border border-gray-200 rounded-2xl">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-4">Frontend Display Settings</h3>

                        <!-- 3 Switches -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <!-- Switch 1: Show Title (is_title) -->
                            <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold shrink-0">
                                        <i class="fa-solid fa-heading"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800 text-sm">Show Title</span>
                                        <span class="text-xs text-gray-500">Banner name text</span>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="hidden" name="is_title" value="0">
                                    <input type="checkbox" name="is_title" value="1" class="sr-only peer" {{ $banner->is_title == 1 ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <!-- Switch 2: Show Image (is_image) -->
                            <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold shrink-0">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800 text-sm">Show Image</span>
                                        <span class="text-xs text-gray-500">Image media</span>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="hidden" name="is_image" value="0">
                                    <input type="checkbox" name="is_image" value="1" class="sr-only peer" {{ $banner->is_image == 1 ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                            <!-- Switch 3: Show Description (is_description) -->
                            <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-2xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-base font-bold shrink-0">
                                        <i class="fa-solid fa-align-left"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800 text-sm">Show Description</span>
                                        <span class="text-xs text-gray-500">Display text on frontend</span>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="hidden" name="is_description" value="0">
                                    <input type="checkbox" name="is_description" value="1" class="sr-only peer" {{ $banner->is_description == 1 ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>

                        </div>

                        <!-- Save Button -->
                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98] cursor-pointer">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Save Visibility Settings</span>
                            </button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection