@extends('layouts.app')

@section('content')

    <div class=" rounded-4 mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-md">

            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Brands
                </h2>
                <a href="{{ route('brands.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <i class="fa-solid fa-folder-plus text-xs"></i>
                    <span>Add Brand</span>
                </a>
            </div>

            <div class="p-6 border-gray-200">
                <form action="{{ route('brands.index') }}" method="GET">

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
                                id="search_brand"
                                value="{{ request('search') }}"
                                placeholder=" "
                                class="peer w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50/50 text-gray-700 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <!-- Floating Label -->
                            <label
                                for="search_brand"
                                class="absolute left-10 top-2 text-gray-400 text-sm pointer-events-none transition-all duration-200
        peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-4 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1
        peer-[:not(:placeholder-shown)]:-translate-y-4 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1 peer-[:not(:placeholder-shown)]:text-xs">
                                Search Brand...
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
                            <a href="{{ route('brands.index') }}"
                               class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-center transition duration-200 shadow-sm">
                                <i class="fa-solid fa-times text-sm"></i>
                                <span>Clear</span>
                            </a>
                        </div>

                    </div>

                </form>
            </div>

            <!-- Table Container -->
            <!-- Table Container -->
            <div class="px-6 pb-6 overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <!-- Table Head -->
                    <thead class="border border-gray-200">
                    <tr class="bg-gray-200/30 border-b border-gray-300 text-gray-800 text-xs uppercase font-bold">
                        <th class="px-4 py-3.5 border-r border-gray-300 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-300 w-24">Logo</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Brand Name</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Slug</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Created At</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y border border-gray-200 divide-gray-200 text-sm text-gray-700">

                    @if(count($brands) > 0)

                        @foreach($brands as $key => $brand)

                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                                <!-- ID -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                    {{ $brands->firstItem() + $key }}
                                </td>

                                <!-- Logo -->
                                <td class="px-4 py-3.5 border-r border-gray-200">
                                    @if($brand->image)
                                        <div class="inline-flex items-center justify-center h-12 w-12 p-1 bg-white border border-gray-200 rounded-xl shadow-xs hover:scale-105 transition-transform duration-200">
                                            <img src="{{ asset('storage/' . $brand->image) }}"
                                                 alt="{{ $brand->name }}"
                                                 class="h-full w-full object-contain rounded-lg">
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">No Image</span>
                                    @endif
                                </td>

                                <!-- Brand Name -->
                                <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                    {{ $brand->name }}
                                </td>

                                <!-- Slug -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-mono text-xs">
                                    {{ $brand->slug }}
                                </td>

                                <!-- Created At -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                    {{ $brand->created_at ? $brand->created_at->format('d M Y') : '-' }}
                                </td>

                                <!-- Action Buttons -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Edit -->
                                        <a href="{{ route('brands.edit', $brand) }}"
                                           class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                           title="Edit Brand">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <!-- Delete Form with SweetAlert Integration -->
                                        <form action="{{ route('brands.destroy', $brand) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Delete Brand">
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
                                <i class="fa-solid fa-copyright text-3xl mb-2 block text-gray-300"></i>
                                <span>No Brands Found</span>
                            </td>
                        </tr>

                    @endif

                    </tbody>

                </table>

                <!-- Pagination -->
                @if($brands->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $brands->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
