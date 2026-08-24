@extends('layouts.app')

@section('content')

    <div class="rounded-4 mx-auto p-6">

        <div class="bg-white rounded shadow-md">

            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Featured Banners
                </h2>
                <a href="{{ route('featuredbanners.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <i class="fa-solid fa-folder-plus text-sm"></i>
                    <span>Add Featured Banner</span>
                </a>
            </div>

            <div class="p-6 border-gray-200">
                <form action="{{ route('featuredbanners.index') }}" method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                        <div class="relative w-70">
                            <!-- Search Icon -->
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <!-- Input Field -->
                            <input
                                type="text"
                                name="search"
                                id="search_banner"
                                value="{{ request('search') }}"
                                placeholder=" "
                                class="peer w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50/50 text-gray-700 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <!-- Floating Label -->
                            <label
                                for="search_banner"
                                class="absolute left-10 top-2 text-gray-400 text-sm pointer-events-none transition-all duration-200
        peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-4 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1
        peer-[:not(:placeholder-shown)]:-translate-y-4 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1 peer-[:not(:placeholder-shown)]:text-xs">
                                Search Banner...
                            </label>
                        </div>

                        <div>
                            <!-- Search Button -->
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                <span>Search</span>
                            </button>

                            <!-- Clear Button -->
                            <a href="{{ route('featuredbanners.index') }}"
                               class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-center transition duration-200 shadow-sm">
                                <i class="fa-solid fa-times text-sm"></i>
                                <span>Clear</span>
                            </a>
                        </div>

                    </div>

                </form>
            </div>

            <!-- Table Container -->
            <div class="px-6 pb-6 overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <!-- Table Head -->
                    <thead class="border border-gray-200">
                    <tr class="bg-gray-200/30 border-b border-gray-300 text-gray-800 text-xs uppercase font-bold">
                        <th class="px-4 py-3.5 border-r border-gray-300 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-300 w-24">Image</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Name</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Button Name</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Created At</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y border border-gray-200 divide-gray-200 text-sm text-gray-700">

                    @if(count($featuredBanners) > 0)

                        @foreach($featuredBanners as $key => $banner)

                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                                <!-- ID -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                    {{ $featuredBanners->firstItem() + $key }}
                                </td>

                                <!-- Image -->
                                <td class="px-4 py-3.5 border-r border-gray-200">
                                    @if($banner->image)
                                        <div class="inline-flex items-center justify-center h-12 w-16 p-1 bg-white border border-gray-200 rounded-xl shadow-xs hover:scale-105 transition-transform duration-200">
                                            <img src="{{ asset('storage/' . $banner->image) }}"
                                                 alt="{{ $banner->name }}"
                                                 class="h-full w-full object-cover rounded-lg">
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">No Image</span>
                                    @endif
                                </td>

                                <!-- Name -->
                                <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                    {{ $banner->name }}
                                </td>

                                <!-- Button Name -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                    {{ $banner->button_name ?? '-' }}
                                </td>

                                <!-- Created At -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                    {{ $banner->created_at ? $banner->created_at->format('d M Y') : '-' }}
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Edit -->
                                        <a href="{{ route('featuredbanners.edit', $banner) }}"
                                           class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                           title="Edit Banner">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('featuredbanners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this featured banner?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Delete Banner">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    @else

                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-image text-3xl mb-2 block text-gray-300"></i>
                                <span>No Featured Banners Found</span>
                            </td>
                        </tr>

                    @endif

                    </tbody>

                </table>

                <!-- Pagination -->
                @if(method_exists($featuredBanners, 'hasPages') && $featuredBanners->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $featuredBanners->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
