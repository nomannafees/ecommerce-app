@extends('layouts.app')

@section('content')

    <div class="rounded-2 mx-auto p-6">

        <!-- Card Wrapper -->
        <div class="bg-white rounded-2xl shadow-md">

            <!-- Card Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Categories
                </h2>
                <a href="{{ route('categorie.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition duration-300 shadow-sm">
                    <i class="fa-solid fa-folder-plus text-xs"></i>
                    <span>Add Category</span>
                </a>
            </div>

            <!-- Search Form -->
            <div class="p-6 border-gray-200">
                <form action="{{ route('categorie.index') }}" method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                        <!-- Search Input -->
                        <div class="relative w-72">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder=" "
                                class="peer w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50/50 text-gray-700 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <label
                                for="search"
                                class="absolute left-10 top-2 text-gray-400 text-sm pointer-events-none transition-all duration-200
        peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-4 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1
        peer-[:not(:placeholder-shown)]:-translate-y-4 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1 peer-[:not(:placeholder-shown)]:text-xs">
                                Search Category...
                            </label>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-sm cursor-pointer">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                <span>Search</span>
                            </button>

                            <a href="{{ route('categorie.index') }}"
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

                <table class="w-full text-left border-collapse border border-gray-200">

                    <thead>
                    <tr class="bg-gray-200/30 border-b border-gray-300 text-gray-800 text-xs uppercase font-bold">
                        <th class="px-4 py-3.5 border-r border-gray-300 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Main Category</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Sub Category</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Child Category</th>
                        <th class="px-4 py-3.5 border-r border-gray-300">Created At</th>
                        <th class="px-4 py-3.5 text-center w-28">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @forelse($record as $key => $category)

                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                            <!-- Exception-Safe Row Counter -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                @if(method_exists($record, 'firstItem') && $record->firstItem())
                                    {{ $record->firstItem() + $key }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>

                            {{-- Main Category Column --}}
                            <td class="px-4 py-3.5 border-r border-gray-200">
                            @if($category->parent_id == 0)
                                <!-- Self Main Category -->
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full font-medium border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $category->name }}
                                    </span>
                            @elseif($category->parent && $category->parent->parent_id == 0)
                                <!-- Parent is Main Category -->
                                    <span class="font-semibold text-gray-800">{{ $category->parent->name }}</span>
                            @elseif($category->parent && $category->parent->parent && $category->parent->parent->parent_id == 0)
                                <!-- Grandparent is Main Category -->
                                    <span class="font-semibold text-gray-800">{{ $category->parent->parent->name }}</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>

                            {{-- Sub Category Column --}}
                            <td class="px-4 py-3.5 border-r border-gray-200">
                            @if($category->parent_id != 0 && optional($category->parent)->parent_id == 0)
                                <!-- Self Sub Category -->
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium border border-blue-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ $category->name }}
                                    </span>
                            @elseif($category->parent && $category->parent->parent_id != 0)
                                <!-- Parent is Sub Category -->
                                    <span class="font-medium text-gray-700">{{ $category->parent->name }}</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>

                            {{-- Child Category Column --}}
                            <td class="px-4 py-3.5 border-r border-gray-200">
                            @if($category->parent && $category->parent->parent_id != 0)
                                <!-- Self Child Category -->
                                    <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs px-3 py-1 rounded-full font-medium border border-purple-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        {{ $category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>

                            <!-- Created At -->
                            <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('categorie.edit', $category) }}"
                                       class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                       title="Edit Category">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form action="{{ route('categorie.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Delete Category">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 block text-gray-300"></i>
                                <span>No Categories Found</span>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

                <!-- Simple & Safe Pagination for $record -->
                @if($record->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $record->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
