@extends('layouts.app')

@section('content')

<div class="container max-w-6xl mx-auto p-6">

    <!-- Card Wrapper -->
    <div class="bg-white rounded shadow-lg">

        <!-- Card Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
            <h2 class="text-2xl font-bold text-gray-800">
                Categories
            </h2>
            <a href="{{ route('categorie.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm">
                Add Category
            </a>
        </div>

        <!-- Search Form -->
        <div class="p-6 border-gray-200">
            <form action="{{ route('categorie.index') }}" method="GET">

                <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                    <!-- Search Input -->
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Category..."
                        class="w-70 border border-gray-300 rounded-lg px-4 py-2">

                    <div>
                        <!-- Search Button -->
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                            Search
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('categorie.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-center">
                            Clear
                        </a>
                    </div>


                </div>

            </form>
        </div>

        <div class="">
            <div class="bg-white px-6 rounded shadow-lg">

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-100">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left">#</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Category Name</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Parent Category</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Created At</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($categories) > 0)
                            @foreach($categories as $key => $category)

                            <tr>
                                <td class="border border-gray-200 px-4 py-3 font-semibold">
                                    {{ $key + 1 }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $category->name }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    @if($category->parent)
                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                        {{ ucfirst($category->parent->name) }}
                                    </span>
                                    @else
                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Main Category
                                    </span>
                                    @endif
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    {{ $category->created_at->format('d M Y') }}
                                </td>

                                <td class="border border-gray-200 px-4 py-3">
                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('categorie.edit', $category) }}"
                                           class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-100 transition-all duration-300 shadow-xs cursor-pointer"
                                           title="Edit Category">

                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        <form action="{{ route('categorie.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-300 shadow-xs cursor-pointer"
                                                    title="Delete">

                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="border border-gray-200 px-4 py-3 text-center">
                                    No Categories Found
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $categories->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>


    @endsection
