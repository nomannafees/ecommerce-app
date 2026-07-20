@extends('layouts.app')

@section('content')

    <div class="container max-w-7xl mx-auto p-6">

        <!-- Card Wrapper -->
        <div class="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Products
                </h2>

                <a href="{{ route('products.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm">
                    Add Product
                </a>
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                    <!-- Search Input -->
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search product..."
                        class="w-70 border border-gray-400 rounded-lg px-4 py-2">

                    <!-- Search Button -->
                    <div>
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                            Search
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('products.index') }}"
                           class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg text-center">
                            Clear
                        </a>
                    </div>


                </div>

            </form>

            <!-- Table -->
            <div class="bg-white px-6 rounded shadow-lg">
                <div class="overflow-x-auto">

                    <table class="w-full border border-gray-100">

                        <!-- Table Head -->
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-4 py-3 text-left">#</th>
                            <th class="border border-gray-200 px-4 py-3 text-left">Image</th>
                            <!-- <th class="border border-gray-200 px-4 py-3 text-left">Gallery</th> -->
                            <th class="border border-gray-200 px-4 py-3 text-left">Name</th>
                            <th class="border border-gray-200 px-4 py-3 text-left">Category</th>
                            <th class="border border-gray-200 px-4 py-3 text-left">Brand</th>

                            <th class="border border-gray-200 px-4 py-3 text-left">Featured</th>
                            <th class="border border-gray-200 px-4 py-3 text-left">Status</th>
                            <th class="border border-gray-200 px-4 py-3 text-left">Action</th>
                        </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody>

                        @if(count($records) > 0)

                            @foreach($records as $key => $record)

                                <tr>

                                    <!-- ID -->
                                    <td class="font-medium text-gray-800 border border-gray-200 px-4 py-3">
                                        {{ $key + 1 }}
                                    </td>


                                    <!-- Product Image -->
                                    <td class="border border-gray-200 px-4 py-3">
                                        @php
                                            // Controller se aaye huay records mein se pehle variant ki image nikal rahe hain (No extra database query)
                                            $firstVariant = $record->variants->first();
                                            $firstVariantImage = $firstVariant ? $firstVariant->variantImage : null;
                                        @endphp

                                        @if($record->mainVariantImage)
                                            <img src="{{ asset('storage/' . $record->mainVariantImage->image_path) }}"
                                                 alt="{{ $record->name }}"
                                                 class="h-16 w-16 object-cover rounded">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </td>

                                    <!-- Product Name -->
                                    <td class="font-semibold border border-gray-200 px-4 py-3">
                                        {{ $record->name }}
                                    </td>

                                    <!-- Category -->
                                    <td class="border border-gray-200 px-4 py-3">
                                        {{ $record->category->name ?? 'N/A' }}
                                    </td>

                                    <!-- Brand -->
                                    <!-- Brand -->
                                    <td class="border border-gray-200 px-4 py-3">

                                        {{ $record->prod_brand->name ?? 'No Brand' }}

                                    </td>



                                    <!-- Featured -->
                                    <td class="border border-gray-200 px-4 py-3">
                                        @if($record->is_featured)
                                            <span
                                                class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">
                                    Yes
                                </span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">
                                    No
                                </span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="border border-gray-200 px-4 py-3">
                                        @if($record->status == 'active')
                                            <span
                                                class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                                    Active
                                </span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">
                                    Inactive
                                </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="border border-gray-200 px-4 py-3">
                                        <div class="flex items-center gap-3 justify-end pr-6">

                                            <!-- Edit -->
                                            <a href="{{ route('products.edit', $record) }}"
                                               class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-100 transition-all duration-300 shadow-xs cursor-pointer"
                                               title="Edit Product">

                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('products.destroy', $record) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-300 shadow-xs cursor-pointer"
                                                        title="Delete">

                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('products.show', $record->id) }}"
                                               class="w-10 h-10 flex items-center justify-center bg-green-50 text-green-600 border border-green-100 rounded-xl hover:bg-green-100 transition-all duration-300 shadow-xs cursor-pointer"
                                               title="View Product">

                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </a>

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        @else

                            <tr>
                                <td colspan="10" class="px-6 py-6">
                                    <div
                                        class="bg-purple-200 border border-black-300 text-black-700 px-4 py-3 rounded-lg text-center font-medium">
                                        No Products Found
                                    </div>
                                </td>
                            </tr>

                        @endif

                        </tbody>

                    </table>

                    <!-- Pagination -->

                    <div class="[&_nav]:bg-white [&_nav]:p-4 [&_nav]:rounded-lg [&_a]:bg-white [&_a]:text-gray-600 [&_a]:border [&_a]:border-gray-200 [&_span]:bg-white [&_span]:text-gray-600 [&_span]:border [&_span]:border-gray-200 [&_a:hover]:!bg-gray-100 [&_a:hover]:!text-gray-800 [&_[aria-current='page']_span]:!bg-gray-100 [&_[aria-current='page']_span]:!text-gray-800 [&_[aria-current='page']_span]:!border-gray-300 [&_[aria-current='page']_span]:font-semibold [&_a:focus]:!ring-0 [&_a:focus]:!outline-none [&_a:focus]:!border-gray-300 [&_span:focus]:!ring-0 [&_span:focus]:!outline-none [&_span:focus]:!border-gray-300 [&_button:focus]:!ring-0 [&_button:focus]:!outline-none [&_button:focus]:!border-gray-300 shadow-sm">
                        {{ $records->links() }}
                    </div>


                </div>
            </div>


        </div>

    </div>

@endsection
