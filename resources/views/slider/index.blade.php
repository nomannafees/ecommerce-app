@extends('layouts.app')

@section('content')



<div class="container max-w-7xl mx-auto p-6">

    <div class="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">

        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
            <h2 class="text-2xl font-bold text-gray-800">
                Slider List
            </h2>

            <a href="{{ route('sliders.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm">
                + Add Slider
            </a>
        </div>

        @if(session('success'))
        <div class="mx-6 mt-6 bg-green-100 text-green-700 px-4 py-3 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
        @endif

        <form method="GET" action="{{ route('sliders.index') }}" class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-[280px_auto_auto] gap-3 items-center">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search slider..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-300">
                        Search
                    </button>

                    <a href="{{ route('sliders.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-center transition duration-300">
                        Clear
                    </a>
                </div>

            </div>

        </form>

        <div class="bg-white px-6 rounded shadow-lg overflow-x-auto">

            <table class="w-full border border-gray-100 mb-4">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-200 px-4 py-3 text-left">#</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Images</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Heading</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Description</th>
                        <th class="border border-gray-200 px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sliders as $key => $slider)

                    <tr class="hover:bg-gray-50/50">

                        <td class="border border-gray-200 px-4 py-3 text-left">
                            {{ $key + 1 }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left">
                            <div class="flex gap-2 flex-wrap">
                                @if($slider->image)
                                <img src="{{ asset('storage/' . $slider->image) }}"
                                    class="w-12 h-12 rounded-lg object-cover shadow-sm border border-gray-200">
                                @else
                                <span class="text-gray-400 text-xs">No Image</span>
                                @endif
                            </div>
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left font-semibold text-gray-800">
                            {{ $slider->heading }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left text-gray-600">
                            {{ Str::limit($slider->description, 50) }}
                        </td>

                        <td class="border border-gray-200 px-4 py-3 text-left">

                            <div class="flex items-center gap-2">

                                <a href="{{ route('sliders.edit', $slider->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition duration-300"
                                    title="Edit Slider">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('sliders.destroy', $slider->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this slider?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-300"
                                        title="Delete Slider">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </td>
                    </tr>

                    @empty

                    

                    @endforelse

                </tbody>

            </table>

            @if($sliders->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50 -mx-6 mt-4">
                {{ $sliders->appends(request()->query())->links() }}
            </div>
            @endif

        </div>

    </div>

</div>

@endsection