@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-y p-6">

    <!-- Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">

            <div>

                <h2 class="text-3xl font-bold text-gray-800">

                    {{ !empty($categorie) ? 'Edit Category' : 'Create Category' }}

                </h2>

            </div>

            <a href="{{ route('categorie.index')}}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">

                List Category

            </a>

        </div>

        <!-- Form -->
        <form action="{{ !empty($categorie->id) ? route('categorie.update', $categorie->id) : route('categorie.store') }}" method="POST">

            @csrf

            @if(!empty($categorie))
            @method('PUT')
            @endif

            <div class="grid gap-6 mb-8 md:grid-cols-2">

                <!-- Category Name -->
                <div>

                    <label class="block mb-2 text-sm font-medium text-gray-700">

                        Category Name

                    </label>

                    <input type="text"
                        name="name"
                        required
                        value="{{ old('name', $categorie->name ?? '') }}"
                        placeholder="Enter category name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3 shadow-sm placeholder:text-gray-400">

                    @error('name')

                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>

                    @enderror

                </div>

                <!-- Parent Category -->
                <div>

                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Parent Category
                    </label>

                    <div class="relative">

                        <select name="parent_id"
                            class="appearance-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full px-4 py-3 pr-12 shadow-sm">

                            <option value="">
                                Parents
                            </option>

                            @foreach(($edit_data ?? $parent_data ?? []) as $parent)

                            <option value="{{ $parent->id }}"
                                {{ old('parent_id', $categorie->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                {{ ucfirst($parent->name) }}
                            </option>

                            @endforeach

                        </select>

                        <!-- Custom Dropdown Icon -->
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-gray-500 text-sm"></i>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4 mt-8">

                <a href="{{ route('categorie.index') }}"
                    class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3">

                    Cancel

                </a>

                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 font-medium rounded-xl text-sm px-6 py-3 transition duration-300 shadow-sm">

                    {{ !empty($categorie) ? 'Update' : 'Save' }}

                </button>

            </div>

        </form>

    </div>

</div>

@endsection