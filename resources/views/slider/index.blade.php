@extends('layouts.app')

@section('content')

    <div class="mx-auto p-6">

        <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Slider Management
                </h2>

                <a href="{{ route('sliders.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl transition-all duration-200 shadow-xs text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-folder-plus text-sm"></i>
                    <span>Add Slider</span>
                </a>
            </div>

            <!-- Flash Message -->
            @if(session('success'))
                <div class="mx-6 mt-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
        @endif

        <!-- Search Form -->
            <form method="GET" action="{{ route('sliders.index') }}" class="p-6">

                <div class="flex flex-wrap items-center gap-3">

                    <!-- Search Input with Floating Border Style -->
                    <div class="relative w-full sm:w-80">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-600 transition-colors duration-200 pointer-events-none z-10">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            id="slider_search"
                            value="{{ request('search') }}"
                            placeholder=" "
                            class="peer w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-white text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <label for="slider_search"
                               class="absolute left-11 top-3 text-gray-400 text-sm pointer-events-none transition-all duration-200 z-10
                               peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-5.5 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1.5 peer-focus:font-medium
                               peer-[:not(:placeholder-shown)]:-translate-y-5.5 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1.5 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:font-medium">
                            Search slider...
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <!-- Search Button -->
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-medium text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/20 transition duration-200 shadow-xs cursor-pointer active:scale-[0.98]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('sliders.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-medium text-sm text-white bg-red-500 hover:bg-red-600 transition duration-200 cursor-pointer active:scale-[0.98]">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Clear</span>
                        </a>
                    </div>

                </div>

            </form>

            <!-- Table Container -->
            <div class="px-6 pb-6 overflow-x-auto">

                <!-- Table with Outer Border and Sharp Edges -->
                <table class="w-full text-left border-collapse border border-gray-200">

                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase font-semibold">
                        <th class="px-4 py-3.5 border-r border-gray-200 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-200 w-28">Image</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Heading</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Description</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @forelse($sliders as $key => $slider)

                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                {{ $sliders->firstItem() + $key }}
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200">
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}"
                                         alt="Slider Image"
                                         class="w-12 h-12 object-cover rounded-xl border border-gray-200 shadow-xs hover:scale-105 transition-transform duration-200">
                                @else
                                    <span class="text-xs text-gray-400 font-medium">No Image</span>
                                @endif
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                {{ $slider->heading ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5 border-r border-gray-200 text-xs text-gray-600 max-w-md">
                                {{ Str::limit($slider->description ?? '-', 60) }}
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">

                                    <!-- Edit Button -->
                                    <a href="{{ route('sliders.edit', $slider->id) }}"
                                       class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                       title="Edit Slider">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <!-- SweetAlert Delete Form -->
                                    <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Delete Slider">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-images text-3xl mb-2 block text-gray-300"></i>
                                <span>No Slider Records Found</span>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                @if($sliders->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $sliders->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
