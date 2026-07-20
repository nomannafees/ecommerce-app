@extends('layouts.app')

@section('content')

    <div class="container max-w-6xl mx-auto p-6">

        <div class="bg-white rounded shadow-lg">

            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Brands
                </h2>
                <a href="{{ route('brands.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm">
                    Add Brand
                </a>
            </div>

            <div class="p-6 border-gray-200">
                <form action="{{ route('brands.index') }}" method="GET">

                    <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search Brand..."
                            class="w-70 border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">

                        <div>
                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg cursor-pointer">
                                Search
                            </button>

                            <a href="{{ route('brands.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-center inline-block">
                                Clear
                            </a>
                        </div>

                    </div>

                </form>
            </div>

            <div class="">
                <div class="bg-white px-6 rounded shadow-lg">

                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-100 mb-6">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 px-4 py-3 text-left w-16">#</th>
                                <th class="border border-gray-200 px-4 py-3 text-left w-24">Logo</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Brand Name</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Slug</th>
                                <th class="border border-gray-200 px-4 py-3 text-left">Created At</th>
                                <th class="border border-gray-200 px-4 py-3 text-left w-32">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($brands) > 0)
                                @foreach($brands as $key => $brand)

                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="border border-gray-200 px-4 py-3 font-semibold">
                                            {{ $key + 1 }}
                                        </td>

                                        <td class="border border-gray-200 px-4 py-2 text-center">
                                        @if($brand->image)
                                            <!-- flex items-center justify-center se image bilkul center me aayegi -->
                                                <div class="inline-flex items-center justify-center h-12 w-12 p-1 bg-white border border-gray-300 rounded-lg shadow-sm hover:shadow-md hover:border-blue-400 transition-all duration-200">
                                                    <img src="{{ asset('storage/' . $brand->image) }}"
                                                         alt="{{ $brand->name }}"
                                                         class="h-full w-full object-contain rounded-md bg-gray-50">
                                                </div>
                                            @else
                                                <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-gray-100 border border-dashed border-gray-300 text-gray-400 text-[10px] italic font-medium mx-auto">
                                                    No Image
                                                </div>
                                            @endif
                                        </td>

                                        <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">
                                            {{ $brand->name }}
                                        </td>

                                        <td class="border border-gray-200 px-4 py-3 text-gray-500 font-mono text-sm">
                                            {{ $brand->slug }}
                                        </td>

                                        <td class="border border-gray-200 px-4 py-3 text-gray-600">
                                            {{ $brand->created_at->format('d M Y') }}
                                        </td>

                                        <td class="border border-gray-200 px-4 py-3">
                                            <div class="flex items-center gap-2">

                                                <a href="{{ route('brands.edit', $brand) }}"
                                                   class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-100 transition-all duration-300 shadow-xs cursor-pointer"
                                                   title="Edit Brand">

                                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                                </a>

                                                <form action="{{ route('brands.destroy', $brand) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this brand?')">
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
                                    <td colspan="6" class="border border-gray-200 px-4 py-8 text-center text-gray-500">
                                        No Brands Found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        @if($brands->hasPages())
                            <div class="p-4 border-t border-gray-200 bg-gray-50">
                                {{ $brands->appends(request()->query())->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
