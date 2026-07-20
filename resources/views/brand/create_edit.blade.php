@extends('layouts.app')

@section('content')

    <div class="max-w-6xl mx-auto py-6 px-6">

        <!-- Card -->
        <div class="bg-white  rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">
                        {{ !empty($brand) ? 'Edit Brand' : 'Create Brand' }}
                    </h2>
                </div>

                <a href="{{ route('brands.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                    List Brands
                </a>
            </div>

            <!-- Form -->
            <form action="{{ !empty($brand->id) ? route('brands.update', $brand->id) : route('brands.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                @if(!empty($brand))
                    @method('PUT')
                @endif

                <div class="grid gap-6 mb-8 md:grid-cols-2">

                    <!-- Brand Name -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Brand Name
                        </label>

                        <input type="text"
                               name="name"
                               id="brand_name"
                               required
                               value="{{ old('name', $brand->name ?? '') }}"
                               placeholder="Enter brand name"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3 shadow-sm placeholder:text-gray-400">

                        @error('name')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Brand Slug -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Slug
                        </label>

                        <input type="text"
                               name="slug"
                               id="brand_slug"
                               required
                               value="{{ old('slug', $brand->slug ?? '') }}"
                               placeholder="brand-slug"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3 shadow-sm placeholder:text-gray-400">

                        @error('slug')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Brand Image -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Brand Logo / Image
                        </label>

                        <input type="file"
                               name="image"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-2 shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                        <!-- Edit Mode me Purani Image Dikhane Ke Liye (FIXED PATH) -->
                        @if(!empty($brand->image))
                            <div class="mt-4">
                                <p class="text-xs text-gray-500 mb-1.5 font-medium">Current Image:</p>
                                <div class="inline-flex items-center justify-center h-16 w-16 p-1 bg-white border border-gray-300 rounded-xl shadow-sm">
                                    <!-- 👇 Yahan storage/ ki bajaye public upload ka path diya gaya hai -->
                                    <img src="{{ asset('storage/brand/' . $brand->image) }}" alt="Brand Logo" class="h-full w-full object-contain rounded-lg bg-gray-50"> </div>
                            </div>
                        @endif

                        @error('image')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('brands.index') }}"
                       class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">
                        Cancel
                    </a>

                    <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-xl text-sm px-6 py-3 transition duration-300 shadow-sm">
                        {{ !empty($brand) ? 'Update' : 'Save' }}
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- Auto Slug Generator Script -->
    <script>
        document.getElementById('brand_name').addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('brand_slug').value = slug;
        });
    </script>

@endsection
