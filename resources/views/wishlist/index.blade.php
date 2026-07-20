@extends('layouts.app')

@section('content')

<div class="container max-w-7xl mx-auto p-6">

    <div class="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
            <h2 class="text-2xl font-bold text-gray-800">
                Wishlists
            </h2>

            <a href="{{ route('wishlists.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm">
                Add Wishlist
            </a>
        </div>

        <form method="GET" action="{{ route('wishlists.index') }}"
            class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                <!-- Search Input -->
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search user..."
                    class="w-70 border border-gray-300 rounded-lg px-4 py-2">

                <div>
                    <!-- Search Button -->
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                        Search
                    </button>

                    <!-- Clear Button -->
                    <a href="{{ route('wishlists.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-center">
                        Clear
                    </a>
                </div>


            </div>

        </form>

        <!-- Table -->
        <div class="bg-white px-6 rounded shadow-lg overflow-x-auto">

            <table class="w-full border border-gray-100">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-200 px-4 py-3 text-left">#</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">User</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Product</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Created At</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($records as $key => $record)

                    <tr>

                        <td class="border border-gray-200 px-4 py-3 text-left">
                            {{ $key + 1 }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left font-semibold text-gray-800">
                            {{ $record->user->name ?? '-' }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left">
                            {{ $record->product->name ?? '-' }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left">
                            {{ $record->created_at?->format('Y-m-d') ?? '-' }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left">

                            <div class="flex items-center gap-3">

                                <a href="{{ route('wishlists.edit', $record->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('wishlists.destroy', $record->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty

                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center">
                            No Wishlists Found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $records->links() }}
            </div>

        </div>

    </div>

</div>

@endsection