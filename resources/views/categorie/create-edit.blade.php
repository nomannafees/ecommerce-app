@extends('layouts.app')

@section('content')

    <div class="mx-auto p-y p-6">

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ !empty($categorie->id) ? 'Edit Category' : 'Create Category' }}
                    </h2>
                </div>

                <a href="{{ route('categorie.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-sm">
                    <i class="fa-solid fa-list-check text-sm"></i>
                    <span>List Category</span>
                </a>
            </div>

            <!-- Form -->
            <form action="{{ !empty($categorie->id) ? route('categorie.update', $categorie->id) : route('categorie.store') }}" method="POST">

                @csrf

                @if(!empty($categorie->id))
                    @method('PUT')
                @endif

                <div class="grid gap-6 mb-8 md:grid-cols-2">

                    <!-- Category Name Input -->
                    <div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200">
                                <i class="fa-solid fa-tag"></i>
                            </span>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   required
                                   value="{{ old('name', $categorie->name ?? '') }}"
                                   placeholder=" "
                                   class="peer w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                            <label for="name"
                                   class="absolute left-11 top-3.5 text-gray-400 text-sm pointer-events-none transition-all duration-200
                                peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-6 peer-focus:left-3 peer-focus:bg-white peer-focus:px-2
                                peer-[:not(:placeholder-shown)]:-translate-y-6 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:text-xs">
                                Category Name
                            </label>
                        </div>

                        @error('name')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Parent Category Select -->
                    <div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200 z-10 pointer-events-none">
                                <i class="fa-solid fa-sitemap"></i>
                            </span>

                            <select name="parent_id"
                                    id="parent_id"
                                    class="peer w-full pl-11 pr-10 pt-5 pb-2 border border-gray-200 rounded-xl bg-gray-50/30 text-gray-800 text-sm appearance-none cursor-pointer focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                                <option value="">None (Main / Root Category)</option>

                                @php
                                    $categoriesList = $parent_data ?? [];
                                    $currentCategoryId = $categorie->id ?? null;
                                    $selectedParentId = old('parent_id', $categorie->parent_id ?? '');
                                @endphp

                                @foreach($categoriesList as $mainCat)
                                    {{-- Level 1: Main Category --}}
                                    @if($mainCat->id != $currentCategoryId)
                                        <option value="{{ $mainCat->id }}" {{ $selectedParentId == $mainCat->id ? 'selected' : '' }} class="font-bold text-gray-900 bg-gray-100/50">
                                             {{ ucfirst($mainCat->name) }} (Main)
                                        </option>

                                        {{-- Level 2: Sub-Category --}}
                                        @if($mainCat->children && count($mainCat->children) > 0)
                                            @foreach($mainCat->children as $subCat)
                                                @if($subCat->id != $currentCategoryId)
                                                    <option value="{{ $subCat->id }}" {{ $selectedParentId == $subCat->id ? 'selected' : '' }} class="text-gray-900">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ ucfirst($subCat->name) }} (Sub)
                                                    </option>

                                                    {{-- Level 3: Child Category --}}
                                                    @if($subCat->children && count($subCat->children) > 0)
                                                        @foreach($subCat->children as $childCat)
                                                            @if($childCat->id != $currentCategoryId)
                                                                <option value="{{ $childCat->id }}"  class="text-gray-700 bg-gray-50 italic">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ ucfirst($childCat->name) }} (Child)
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach

                            </select>

                            <!-- Custom Dropdown Chevron Icon -->
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>

                            <!-- Label -->
                            <label for="parent_id"
                                   class="absolute left-11 top-1.5 text-[11px] font-medium text-emerald-600 pointer-events-none transition-all duration-200">
                                Parent Category
                            </label>
                        </div>

                        @error('parent_id')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-8">
                    <div class="flex items-center gap-3">
                        <!-- Cancel Button -->
                        <a href="{{ route('categorie.index') }}"
                           class="inline-flex items-center justify-center gap-2.5 px-6 py-2.5 rounded-xl font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900 transition duration-300 shadow-sm border border-gray-200/70 group text-sm">
                            <i class="fa-solid fa-xmark text-gray-500 group-hover:scale-110 transition-transform"></i>
                            <span>Cancel</span>
                        </a>

                        <!-- Save / Update Button -->
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 px-6 py-2.5 rounded-xl font-semibold text-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition duration-300 shadow-sm group cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-xs group-hover:scale-110 transition-transform"></i>
                            <span>{{ !empty($categorie->id) ? 'Update' : 'Save' }}</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

@endsection
