@extends('layouts.app')
@section('content')
    <div class="max-w-6xl mx-auto p-6 space-y-6">
        <form action="{{ !empty($product) ? route('products.update', $product->id) : route('products.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @if(!empty($product))
                @method('PUT')
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-8">

                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ !empty($product) ? 'Edit Product' : 'Create Product' }}
                    </h2>
                    <a href="{{ route('products.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition duration-300 shadow-xs font-medium">
                        List Products
                    </a>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                        <div class="relative">
                            <select name="category_id"
                                    class="appearance-none cursor-pointer bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 pr-12 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option hidden value="">Select Category</option>
                                @foreach($parent_data as $parent)
                                    <option disabled class="font-bold text-gray-500">{{ $parent->name }}</option>
                                    @foreach($parent->children as $child)
                                        <option
                                            value="{{ $child->id }}" {{ old('category_id', $product->category_id ?? '') == $child->id ? 'selected' : '' }}>
                                            <i style="color: red">↳</i> {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-gray-500 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                               class="bg-gray-50 cursor-pointer border border-gray-300 rounded-xl w-full px-4 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>


                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Brand</label>
                        <div class="relative flex items-center">
                            <select name="brand_id"
                                    class="appearance-none bg-gray-50 border border-gray-300 rounded-xl w-full pl-4 pr-10 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-gray-700">
                                <option value="" hidden>Select Brand</option>
                                @foreach($brands as $brand)
                                    <option
                                        value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-0 pr-4 pointer-events-none text-gray-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                        <div class="relative">
                            <select name="status"
                                    class="appearance-none cursor-pointer bg-gray-50 border border-gray-300 rounded-xl w-full px-4 py-3 pr-12 text-sm focus:outline-hidden">
                                <option
                                    value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option
                                    value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-gray-500 text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }} class="rounded-sm cursor-pointer border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label class="text-sm font-medium text-gray-700">Featured Product</label>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block mb-2 text-sm font-semibold text-gray-700 tracking-wide">Product
                        Description</label>
                    <div class="rounded-xl overflow-hidden shadow-2xs border border-gray-200 bg-white">
                        <textarea id="description-editor" name="description" rows="6"
                                  class="w-full px-4 py-3 text-sm focus:outline-hidden invisible">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-8 mt-6">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Product Variants Settings</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Manage color specific items and global country size
                            charts</p>
                    </div>
                    <button type="button" onclick="addNewVariantGroup()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition shadow-xs cursor-pointer">
                        + Add More Color Variant
                    </button>
                </div>

                <div id="variant-groups-wrapper" class="space-y-8">

                    @php
                        $groupedVariants = isset($product) && $product->variants->isNotEmpty() ? $product->variants->groupBy('color_name') : collect([null]);
                        $groupIndex = 0;
                    @endphp

                    @foreach($groupedVariants as $colorName => $variants)
                        @php
                            // 🔥 SAFE CHECK: Agar $variants null nahi hai aur object/collection hai, tabhi pluck karein, warna khali array [] dein
                            $savedSizes = (!empty($variants) && is_object($variants)) ? $variants->pluck('size')->toArray() : [];

                            // SAFE CHECK: First variant nikalne ke liye bhi safe checking
                            $firstVariant = (!empty($variants) && is_object($variants)) ? $variants->first() : null;

                            // Size System ko nikalen taake dropdown select ho sake
                            $currentSizeSystem = $firstVariant ? trim(strval($firstVariant->size_system)) : '';

                            $variantImage = $firstVariant ? $firstVariant->variantImage : null;
                            $hasImage = $variantImage && !empty($variantImage->image_path);
                        @endphp

                        <div class="variant-group bg-gray-50/50 border border-gray-200 rounded-2xl p-6 relative"
                             data-index="{{ $groupIndex }}">

                            @if($groupIndex > 0)
                                <button type="button" onclick="removeVariantGroup({{ $groupIndex }})"
                                        class="absolute top-4 right-4 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                                    ✕ Remove Color Block
                                </button>
                            @endif

                            <div class="grid gap-6 md:grid-cols-2">
                                {{-- Color Name Input --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Color Name</label>
                                    <input type="text" name="variants_group[{{ $groupIndex }}][color]"
                                           value="{{ $colorName ? $colorName : '' }}"
                                           placeholder="e.g. Royal Blue, Midnight Black"
                                           class="variant-color-input bg-white border border-gray-300 rounded-xl w-full px-4 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                           oninput="generateVariantTableRows({{ $groupIndex }})">
                                </div>

                                {{-- Image Upload & Preview Container --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Upload Variant Color
                                        Image</label>
                                    <div class="relative w-full h-24">

                                        <label for="variant-file-{{ $groupIndex }}"
                                               class="group relative flex items-center justify-center w-full h-full border-2 border-dashed border-gray-300 hover:border-blue-500 bg-white hover:bg-gray-50/30 rounded-xl cursor-pointer transition-all duration-200 overflow-hidden shadow-2xs">

                                            <div id="variant-text-box-{{ $groupIndex }}"
                                                 class="flex items-center gap-3 p-4 pointer-events-none transition-all {{ $hasImage ? 'hidden' : '' }}">
                                                <div
                                                    class="w-10 h-10 bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 rounded-lg flex items-center justify-center transition-all duration-300">
                                                    <i class="fa-regular fa-image text-lg"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p class="text-gray-700 font-medium text-xs">Upload variant
                                                        image</p>
                                                    <p class="text-gray-400 text-[10px] mt-0.5">Click or drag & drop</p>
                                                </div>
                                            </div>

                                            <img id="variant-preview-img-{{ $groupIndex }}"
                                                 src="{{ $hasImage ? asset('storage/' . $variantImage->image_path) : '' }}"
                                                 class="{{ $hasImage ? '' : 'hidden' }} w-full h-full object-contain bg-gray-50 absolute inset-0 rounded-xl">


                                            <div id="variant-overlay-{{ $groupIndex }}"
                                                 class="{{ $hasImage ? '' : 'hidden' }} absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-all duration-200 gap-1.5 font-medium text-xs">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>Change Image</span>
                                            </div>

                                            <input type="file" id="variant-file-{{ $groupIndex }}"
                                                   name="variants_group[{{ $groupIndex }}][color_image]"
                                                   class="hidden variant-image-input" accept="image/*"
                                                   onchange="updateVariantImagePreview(this, {{ $groupIndex }})">
                                        </label>

                                        <button type="button" id="variant-remove-btn-{{ $groupIndex }}"
                                                onclick="clearVariantImage({{ $groupIndex }})"
                                                class="{{ $hasImage ? 'flex' : 'hidden' }} absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white w-6 h-6 rounded-full items-center justify-center shadow-md transition transform hover:scale-110 z-10 text-xs cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="is_main"
                                            {{-- 🔥 FIX: Index index ke bajaye Image ki ID ko value banayein --}}
                                            value="{{ isset($variantImage) && $variantImage ? $variantImage->id : 'new_' . $groupIndex }}"
                                            class="main-variant-radio"
                                            {{ (isset($variantImage) && $variantImage && $variantImage->is_main) ? 'checked' : '' }}
                                        >
                                        <span class="text-sm text-gray-700">
                                            Main Product Image
                                        </span>
                                    </label>
                                </div>

                                {{-- Size System Dropdown --}}
                                <div class="md:col-span-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Select Country Size
                                        System</label>
                                    <div class="relative flex items-center">

                                        <select name="variants_group[{{ $groupIndex }}][size_system]"
                                                class="country-size-select appearance-none bg-white border border-gray-300 rounded-xl w-full pl-4 pr-10 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-gray-700"
                                                onchange="renderSizes({{ $groupIndex }})">

                                            <option value="" hidden>Choose System</option>
                                            <option
                                                value="uk" {{ (old("variants_group.{$groupIndex}.size_system", $firstVariant->size_system ?? '') == 'uk') ? 'selected' : '' }}>
                                                UK System
                                            </option>
                                            <option
                                                value="height_suit" {{ (old("variants_group.{$groupIndex}.size_system", $firstVariant->size_system ?? '') == 'height_suit') ? 'selected' : '' }}>
                                                Height Suit Only System
                                            </option>
                                            <option
                                                value="int" {{ (old("variants_group.{$groupIndex}.size_system", $firstVariant->size_system ?? '') == 'int') ? 'selected' : '' }}>
                                                INT (Universal Standard)
                                            </option>
                                            <option
                                                value="eu" {{ (old("variants_group.{$groupIndex}.size_system", $firstVariant->size_system ?? '') == 'eu') ? 'selected' : '' }}>
                                                EU Standard Chart
                                            </option>
                                            <option
                                                value="other" {{ (old("variants_group.{$groupIndex}.size_system", $firstVariant->size_system ?? '') == 'other') ? 'selected' : '' }}>
                                                Other Fulllook Options
                                            </option>
                                        </select>

                                        <div class="absolute right-0 pr-4 pointer-events-none text-gray-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                 stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Checkboxes Container --}}
                                <div class="md:col-span-2">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Select Sizes (Multiple
                                        Options)</label>
                                    <div
                                        class="sizes-checkbox-container grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 bg-white p-4 border border-gray-200 rounded-xl min-h-14 shadow-2xs"
                                        data-saved="{{ json_encode($savedSizes) }}">
                                        <span
                                            class="text-gray-400 text-xs col-span-full flex items-center justify-center">Please choose a system first to load checkboxes...</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Variants Matrix Table --}}
                            <div
                                class="variants-table-wrapper mt-8 overflow-x-auto {{ isset($product) ? '' : 'hidden' }}">
                                <table
                                    class="w-full text-sm text-left text-gray-500 border border-gray-200 rounded-xl overflow-hidden shadow-2xs">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100/80">
                                    <tr>
                                        <th class="px-4 py-3.5">Color</th>
                                        <th class="px-4 py-3.5">Size</th>
                                        <th class="px-4 py-3.5">Cut_price</th>
                                        <th class="px-4 py-3.5 w-1/5">Price</th>
                                        <th class="px-4 py-3.5 w-1/5">Quantity</th>
                                        <th class="px-4 py-3.5">Seller SKU</th>
                                    </tr>
                                    </thead>
                                    <tbody class="variants-table-body divide-y divide-gray-200 bg-white">
                                    @if(isset($product) && $variants)
                                        @foreach($variants as $itemIndex => $variant)
                                            <tr class="hover:bg-gray-50/80 transition duration-150"
                                                data-size="{{ $variant->size }}">
                                                <td class="px-4 py-3 font-semibold text-gray-800 text-xs">{{ $variant->color_name }}</td>
                                                <td class="px-4 py-3 font-semibold text-gray-600 text-xs">{{ $variant->size }}</td>
                                                <td class="px-4 py-3">
                                                    <input type="number" step="0.01"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][cut_price]"
                                                           value="{{ $variant->cut_price }}"
                                                           class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden"
                                                           required>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number" step="0.01"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][price]"
                                                           value="{{ $variant->price }}"
                                                           class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden"
                                                           required>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="number"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][quantity]"
                                                           value="{{ $variant->stock }}"
                                                           class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden"
                                                           required>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input type="text"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][sku]"
                                                           value="{{ $variant->sku }}"
                                                           class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden">
                                                    <input type="hidden"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][color]"
                                                           value="{{ $variant->color_name }}">
                                                    <input type="hidden"
                                                           name="variants_group[{{ $groupIndex }}][items][{{ $itemIndex }}][size]"
                                                           value="{{ $variant->size }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @php $groupIndex++; @endphp
                    @endforeach

                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('products.index') }}"
                   class="text-gray-600 hover:text-gray-900 font-medium px-6 py-3 text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-xl text-sm px-6 py-3 shadow-xs cursor-pointer transition">
                    {{ !empty($product) ? 'Update Product' : 'Save Product' }}
                </button>
            </div>

        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <style>
        .ck-editor__editable_inline {
            min-height: 240px;
            border-bottom-left-radius: 0.75rem !important;
            border-bottom-right-radius: 0.75rem !important;
            background-color: #ffffff !important;
            padding: 0 1.25rem !important;
        }

        .ck.ck-editor__main > .ck-editor__editable:not(.ck-focused) {
            border-color: #e5e7eb !important;
        }

        .ck-toolbar {
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            padding: 0.5rem !important;
        }
    </style>

    <script>
        ClassicEditor
            .create(document.querySelector('#description-editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });

        const sizeSystems = {
            uk: ['UK 6', 'UK 8', 'UK 10', 'UK 12', 'UK 14', 'UK 16'],
            height_suit: ['Short', 'Regular', 'Long', 'Extra Long'],
            int: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            eu: ['EU 34', 'EU 36', 'EU 38', 'EU 40', 'EU 42', 'EU 44'],
            other: ['Full Size A', 'Full Size B', 'Custom Combo']
        };

        // Global counters handles dynamic addition safely
        let variantGroupCount = {{ isset($product) ? $product->variants->groupBy('color_name')->count() : 1 }};

        function updateVariantImagePreview(input, idx) {
            const textBox = document.getElementById(`variant-text-box-${idx}`);
            const imgTag = document.getElementById(`variant-preview-img-${idx}`);
            const overlay = document.getElementById(`variant-overlay-${idx}`);
            const removeBtn = document.getElementById(`variant-remove-btn-${idx}`);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imgTag.src = e.target.result;
                    textBox.classList.add('hidden');
                    imgTag.classList.remove('hidden');
                    overlay.classList.remove('hidden');
                    removeBtn.classList.remove('hidden');
                    removeBtn.classList.remove('hidden');
                    removeBtn.classList.add('flex');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearVariantImage(idx) {
            const fileInput = document.getElementById(`variant-file-${idx}`);
            const textBox = document.getElementById(`variant-text-box-${idx}`);
            const imgTag = document.getElementById(`variant-preview-img-${idx}`);
            const overlay = document.getElementById(`variant-overlay-${idx}`);
            const removeBtn = document.getElementById(`variant-remove-btn-${idx}`);

            fileInput.value = "";
            imgTag.src = "";
            textBox.classList.remove('hidden');
            imgTag.classList.add('hidden');
            overlay.classList.add('hidden');
            removeBtn.classList.add('hidden');
            removeBtn.classList.remove('flex');
        }

        // 🔥 FIX 1: Render Sizes method now retains saved database inputs state inside Edit Mode
        function renderSizes(index) {
            const group = document.querySelector(`.variant-group[data-index="${index}"]`);
            const system = group.querySelector('.country-size-select').value;
            const container = group.querySelector('.sizes-checkbox-container');

            // Database se purane saved sizes read karne ka element
            let savedSizes = [];
            try {
                savedSizes = JSON.parse(container.getAttribute('data-saved')) || [];
            } catch (e) {
                savedSizes = [];
            }

            container.innerHTML = '';

            if (!system || !sizeSystems[system]) {
                container.innerHTML = '<span class="text-gray-400 text-xs col-span-full flex items-center justify-center">Please choose a system first to load checkboxes...</span>';
                return;
            }

            sizeSystems[system].forEach((size, idx) => {
                const isChecked = savedSizes.includes(size) ? 'checked' : '';
                const div = document.createElement('div');
                div.className = "flex items-center gap-2 bg-gray-50 hover:bg-gray-100/70 p-2.5 border border-gray-200 rounded-xl transition duration-150 cursor-pointer select-none";
                div.innerHTML = `
                <input type="checkbox" id="size-${index}-${idx}" value="${size}" ${isChecked} class="size-checkbox rounded-sm border-gray-300 text-blue-600 focus:ring-blue-500" onchange="generateVariantTableRows(${index})">
                <label for="size-${index}-${idx}" class="text-xs font-semibold text-gray-700 cursor-pointer w-full">${size}</label>
            `;
                container.appendChild(div);
            });

            // Agar database se table pehle se load hui hai, to generate rows tabhi bulayein jab checkbox dasti change ho
            if (savedSizes.length === 0) {
                generateVariantTableRows(index);
            } else {
                // Data attribute clear kar dein taake dobara toggles par refresh ho sake matrix rows
                container.setAttribute('data-saved', '[]');
            }
        }

        // 🔥 FIX 2: Generate Matrix rows handles old inputs gracefully without erasing values
        function generateVariantTableRows(index) {
            const group = document.querySelector(`.variant-group[data-index="${index}"]`);
            const color = group.querySelector('.variant-color-input').value.trim();
            const selectedCheckboxes = group.querySelectorAll('.size-checkbox:checked');
            const tableWrapper = group.querySelector('.variants-table-wrapper');
            const tableBody = group.querySelector('.variants-table-body');

            if (color === '' || selectedCheckboxes.length === 0) {
                tableWrapper.classList.add('hidden');
                tableBody.innerHTML = '';
                return;
            }

            // Maujooda inputs ke numbers/values save rakhein taake change par clear na hon
            let existingData = {};
            tableBody.querySelectorAll('tr').forEach(row => {
                const rowSize = row.getAttribute('data-size');
                if (rowSize) {
                    existingData[rowSize] = {
                        price: row.querySelector('input[name*="[price]"]')?.value || '',
                        quantity: row.querySelector('input[name*="[quantity]"]')?.value || '',
                        sku: row.querySelector('input[name*="[sku]"]')?.value || ''
                    };
                }
            });

            tableWrapper.classList.remove('hidden');
            tableBody.innerHTML = '';

            selectedCheckboxes.forEach((cb, sizeIndex) => {
                const size = cb.value;
                const savedRow = existingData[size] || {
                    price: '',
                    quantity: '',
                    sku: `${color.toUpperCase()}-${size.replace(/\s+/g, '')}`
                };

                const row = document.createElement('tr');
                row.className = "hover:bg-gray-50/80 transition duration-150";
                row.setAttribute('data-size', size);
                row.innerHTML = `
                <td class="px-4 py-3 font-semibold text-gray-800 text-xs">${color}</td>
                <td class="px-4 py-3 font-semibold text-gray-600 text-xs">${size}</td>
                <td class="px-4 py-3">
                    <input type="number" step="0.01" name="variants_group[${index}][items][${sizeIndex}][cut_price]" value="${savedRow.cut_price}" placeholder="0.00" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden" required>
                </td>
                <td class="px-4 py-3">
                    <input type="number" step="0.01" name="variants_group[${index}][items][${sizeIndex}][price]" value="${savedRow.price}" placeholder="0.00" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden" required>
                </td>
                <td class="px-4 py-3">
                    <input type="number" name="variants_group[${index}][items][${sizeIndex}][quantity]" value="${savedRow.quantity}" placeholder="Qty" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden" required>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="variants_group[${index}][items][${sizeIndex}][sku]" value="${savedRow.sku}" placeholder="SKU" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-hidden">
                    <input type="hidden" name="variants_group[${index}][items][${sizeIndex}][color]" value="${color}">
                    <input type="hidden" name="variants_group[${index}][items][${sizeIndex}][size]" value="${size}">
                </td>
            `;
                tableBody.appendChild(row);
            });
        }

        function addNewVariantGroup() {
            const wrapper = document.getElementById('variant-groups-wrapper');
            const index = variantGroupCount;

            const newGroup = document.createElement('div');
            newGroup.className = "variant-group bg-gray-50/50 border border-gray-200 rounded-2xl p-6 relative mt-6 pt-12 animate-fadeIn";
            newGroup.setAttribute('data-index', index);

            newGroup.innerHTML = `
            <button type="button" onclick="removeVariantGroup(${index})" class="absolute top-4 right-4 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                Remove Color Block
            </button>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Color Name</label>
                    <input type="text" name="variants_group[${index}][color]" placeholder="e.g. Red, Black"
                           class="variant-color-input bg-white border border-gray-300 rounded-xl w-full px-4 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                           oninput="generateVariantTableRows(${index})">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Upload Variant Color Image</label>
                    <div class="relative w-full h-24">
                        <label for="variant-file-${index}" class="group relative flex items-center justify-center w-full h-full border-2 border-dashed border-gray-300 hover:border-blue-500 bg-white hover:bg-gray-50/30 rounded-xl cursor-pointer transition-all duration-200 overflow-hidden shadow-2xs">
                            <div id="variant-text-box-${index}" class="flex items-center gap-3 p-4 pointer-events-none transition-all">
                                <div class="w-10 h-10 bg-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 rounded-lg flex items-center justify-center transition-all duration-300">
                                    <i class="fa-regular fa-image text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-gray-700 font-medium text-xs">Upload variant image</p>
                                    <p class="text-gray-400 text-[10px] mt-0.5">Click or drag & drop</p>
                                </div>
                            </div>
                            <img id="variant-preview-img-${index}" src="" class="hidden w-full h-full object-contain bg-gray-50 absolute inset-0 rounded-xl">
                            <div id="variant-overlay-${index}" class="hidden absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-all duration-200 gap-1.5 font-medium text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Change Image</span>
                            </div>
                            <input type="file" id="variant-file-${index}" name="variants_group[${index}][color_image]" class="hidden variant-image-input" accept="image/*" onchange="updateVariantImagePreview(this, ${index})">
                        </label>
                        <button type="button" id="variant-remove-btn-${index}" onclick="clearVariantImage(${index})" class="hidden absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white w-6 h-6 rounded-full items-center justify-center shadow-md transition transform hover:scale-110 z-11 text-xs cursor-pointer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-3">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="is_main" value="${index}" class="main-variant-radio">
        <span class="text-sm text-gray-700">
            Main Product Image
        </span>
    </label>
</div>

               <div class="md:col-span-2">
    <label class="block mb-2 text-sm font-medium text-gray-700">Select Country Size System</label>
    <div class="relative flex items-center">
        <select name="variants_group[${index}][size_system]"
                class="country-size-select appearance-none bg-white border border-gray-300 rounded-xl w-full pl-4 pr-10 py-3 text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-gray-700"
                onchange="renderSizes(${index})">
            <option value="" hidden>Choose System</option>
            <option value="uk">UK System</option>
            <option value="height_suit">Height Suit Only System</option>
            <option value="int">INT (Universal Standard)</option>
            <option value="eu">EU Standard Chart</option>
            <option value="other">Other Fulllook Options</option>
        </select>
        <div class="absolute right-0 pr-4 pointer-events-none text-gray-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Select Sizes (Multiple Options)</label>
                    <div class="sizes-checkbox-container grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 bg-white p-4 border border-gray-200 rounded-xl min-h-14 shadow-2xs" data-saved="[]">
                        <span class="text-gray-400 text-xs col-span-full flex items-center justify-center">Please choose a system first to load checkboxes...</span>
                    </div>
                </div>
            </div>

            <div class="variants-table-wrapper mt-8 overflow-x-auto hidden">
                <table class="w-full text-sm text-left text-gray-500 border border-gray-200 rounded-xl overflow-hidden shadow-2xs">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100/80">
                    <tr>
                        <th class="px-4 py-3.5">Color</th>
                        <th class="px-4 py-3.5">Size</th>
                           <th class="px-4 py-3.5">Cut_price</th>
                        <th class="px-4 py-3.5 w-1/5">Price</th>
                        <th class="px-4 py-3.5 w-1/5">Quantity</th>
                        <th class="px-4 py-3.5">Seller SKU</th>
                    </tr>
                    </thead>
                    <tbody class="variants-table-body divide-y divide-gray-200 bg-white">
                    </tbody>
                </table>
            </div>
        `;

            wrapper.appendChild(newGroup);
            variantGroupCount++;
        }

        function removeVariantGroup(index) {
            const group = document.querySelector(`.variant-group[data-index="${index}"]`);
            if (group) {
                group.remove();
            }
        }

        // Main Single Image Upload Core Listener
        document.getElementById('main_image')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('preview-image').src = event.target.result;
                    document.getElementById('preview-image').classList.remove('hidden');
                    document.getElementById('preview-text').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Multiple Gallery Files Attachment Handler
        const galleryInput = document.getElementById('gallery_images');
        const preview = document.getElementById('gallery-preview');
        let dt = new DataTransfer();

        galleryInput?.addEventListener('change', function (e) {
            for (let file of this.files) {
                dt.items.add(file);
                let reader = new FileReader();
                reader.onload = function (event) {
                    let div = document.createElement('div');
                    div.className = "relative gallery-item";
                    div.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                    <button type="button" class="absolute top-2 right-2 bg-red-600 text-white w-7 h-7 rounded-full remove-image">✕</button>
                `;
                    preview.appendChild(div);
                    div.querySelector('.remove-image').onclick = function () {
                        const index = [...preview.children].indexOf(div);
                        dt.items.remove(index);
                        galleryInput.files = dt.files;
                        div.remove();
                    };
                }
                reader.readAsDataURL(file);
            }
            galleryInput.files = dt.files;
        });

        document.querySelectorAll('.remove-old-image').forEach(btn => {
            btn.addEventListener('click', function () {
                let item = this.closest('.gallery-item');
                let id = item.dataset.id;
                document.getElementById('deleted-images').insertAdjacentHTML(
                    'beforeend',
                    `<input type="hidden" name="delete_images[]" value="${id}">`
                );
                item.remove();
                if (document.querySelectorAll('#gallery-preview .gallery-item').length === 0) {
                    document.getElementById('gallery-text').classList.remove('hidden');
                    document.getElementById('gallery-text').classList.add('flex');
                }
            });
        });

        // 🔥 FIX 3: Edit Mode auto loader engine
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.country-size-select').forEach(function (selectBox) {
                if (selectBox.value !== "") {
                    const idx = selectBox.closest('.variant-group').getAttribute('data-index');
                    renderSizes(idx);
                }
            });
        });
    </script>

@endsection
