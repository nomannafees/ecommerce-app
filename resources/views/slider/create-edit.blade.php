@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                {{ !empty($slider) ? 'Edit Slider' : 'Create Slider' }}
            </h2>

            <a href="{{ route('sliders.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-sm">
                List Sliders
            </a>

        </div>

        <form action="{{ !empty($slider) ? route('sliders.update',$slider->id) : route('sliders.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            @if(!empty($slider))
            @method('PUT')
            @endif

            <div class="grid gap-6">

                <!-- Heading -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Heading
                    </label>

                    <input type="text"
                        name="heading"
                        value="{{ old('heading',$slider->heading ?? '') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3">
                </div>

                <!-- Description -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Description
                    </label>

                    <textarea name="description"
                        rows="5"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3">{{ old('description',$slider->description ?? '') }}</textarea>
                </div>

                <!-- Multiple Images -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Images
                    </label>

                    <input type="file" name="image"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3">

                    @if(!empty($slider) && $slider->images)

                    <div class="flex flex-wrap gap-4 mt-4">

                        @if(!empty($slider->image))
                        <img src="{{ asset('storage/'.$slider->image) }}"
                            width="150"
                            class="rounded border">
                        @else
                        <span class="text-gray-400">No Image</span>
                        @endif

                    </div>

                    @endif

                </div>

            </div>

            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('sliders.index') }}"
                    class="px-6 py-3 text-gray-600">
                    Cancel
                </a>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                    {{ !empty($slider) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection