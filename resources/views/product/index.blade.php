@extends('layouts.app')

@section('content')

    <div class=" mx-auto p-6">

        <!-- Card Wrapper -->
        <div class="bg-white shadow rounded-xl border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50/50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Products
                </h2>

                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-300 shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <i class="fa-solid fa-folder-plus text-sm"></i>
                    <span>Add Product</span>
                </a>
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-[280px_auto] gap-3 items-center">

                    <!-- Search Input (Floating Label Layout) -->
                    <div class="relative w-full">
                        <!-- Search Icon -->
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-emerald-500 transition-colors duration-200 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <!-- Input Field -->
                        <input
                                type="text"
                                name="search"
                                id="search_product"
                                value="{{ request('search') }}"
                                placeholder=" "
                                class="peer w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-gray-50/50 text-gray-700 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200">

                        <!-- Floating Label -->
                        <label
                                for="search_product"
                                class="absolute left-10 top-2 text-gray-400 text-sm pointer-events-none transition-all duration-200
            peer-focus:text-xs peer-focus:text-emerald-600 peer-focus:-translate-y-4 peer-focus:left-3 peer-focus:bg-white peer-focus:px-1
            peer-[:not(:placeholder-shown)]:-translate-y-4 peer-[:not(:placeholder-shown)]:left-3 peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-1 peer-[:not(:placeholder-shown)]:text-xs">
                            Search product...
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <!-- Search Button (Emerald Green) -->
                        <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500/50 text-sm font-medium">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>

                        <!-- Clear Button -->
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-center transition duration-200 shadow-sm text-sm font-medium">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Clear</span>
                        </a>
                    </div>

                </div>

            </form>

            <!-- Table Container -->
            <div class="px-6 pb-6 overflow-x-auto">

                <!-- Table with Outer Border and Sharp Edges -->
                <table class="w-full text-left border-collapse border border-gray-200">

                    <!-- Table Head -->
                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 text-xs uppercase font-semibold">
                        <th class="px-4 py-3.5 border-r border-gray-200 w-12">#</th>
                        <th class="px-4 py-3.5 border-r border-gray-200 w-24">Image</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Name</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Category</th>
                        <th class="px-4 py-3.5 border-r border-gray-200">Brand</th>
                        <th class="px-4 py-3.5 border-r border-gray-200 text-center">Type</th>
                        <th class="px-4 py-3.5 border-r border-gray-200 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center w-36">Action</th>
                    </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                    @if(count($records) > 0)

                        @foreach($records as $key => $record)

                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">

                                <!-- ID -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-500 font-medium">
                                    {{ $records->firstItem() + $key }}
                                </td>

                                <!-- Product Image -->
                                <td class="px-4 py-3.5 border-r border-gray-200">
                                    @php
                                        $firstVariant = $record->variants->first();
                                        $firstVariantImage = $firstVariant ? $firstVariant->variantImage : null;
                                    @endphp

                                    @if($record->mainVariantImage)
                                        <img src="{{ asset('storage/' . $record->mainVariantImage->image_path) }}"
                                             alt="{{ $record->name }}"
                                             class="w-12 h-12 object-cover rounded-xl border border-gray-200 shadow-xs hover:scale-105 transition-transform duration-200">
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">No Image</span>
                                    @endif
                                </td>

                                <!-- Product Name -->
                                <td class="px-4 py-3.5 border-r border-gray-200 font-semibold text-gray-800">
                                    {{ $record->name }}
                                </td>

                                <!-- Category -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600 text-xs">
                                @if(optional($record->category)->parent && optional($record->category->parent)->parent)
                                    <!-- Agar 3-level (Child) hai -->
                                        <span class="text-gray-400 font-normal">{{ $record->category->parent->parent->name }} &gt; </span>
                                        <span class="text-gray-400 font-normal">{{ $record->category->parent->name }} &gt; </span>
                                        <span class="font-semibold text-emerald-600">{{ $record->category->name }}</span>
                                @elseif(optional($record->category)->parent)
                                    <!-- Agar 2-level (Sub) hai -->
                                        <span class="text-gray-400 font-normal">{{ $record->category->parent->name }} &gt; </span>
                                        <span class="font-semibold text-emerald-600">{{ $record->category->name }}</span>
                                @else
                                    <!-- Agar Direct Main Category hai -->
                                        <span class="font-semibold text-emerald-600">{{ $record->category->name ?? 'N/A' }}</span>
                                    @endif
                                </td>

                                <!-- Brand -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-gray-600">
                                    {{ $record->prod_brand->name ?? 'No Brand' }}
                                </td>

                                <!-- Product Type Column -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-center whitespace-nowrap">
                                    @switch($record->product_type)
                                        @case('featured')
                                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs px-3 py-1 rounded-full font-medium border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Featured
                                            </span>
                                        @break

                                        @case('trending')
                                        <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs px-3 py-1 rounded-full font-medium border border-purple-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                                Trending
                                            </span>
                                        @break

                                        @case('bestseller')
                                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                Bestseller
                                            </span>
                                        @break

                                        @case('new_arrival')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full font-medium border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                New Arrival
                                            </span>
                                        @break

                                        @case('hot_deal')
                                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 text-xs px-3 py-1 rounded-full font-medium border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Hot Deal
                                            </span>
                                        @break

                                        @case('special_offer')
                                        <span class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 text-xs px-3 py-1 rounded-full font-medium border border-orange-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                                Special Offer
                                            </span>
                                        @break

                                        @case('top_rated')
                                        <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 text-xs px-3 py-1 rounded-full font-medium border border-indigo-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                                Top Rated
                                            </span>
                                        @break

                                        @case('limited_edition')
                                        <span class="inline-flex items-center gap-1.5 bg-pink-50 text-pink-700 text-xs px-3 py-1 rounded-full font-medium border border-pink-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                                Limited Edition
                                            </span>
                                        @break

                                        @case('upcoming')
                                        <span class="inline-flex items-center gap-1.5 bg-sky-50 text-sky-700 text-xs px-3 py-1 rounded-full font-medium border border-sky-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                                Upcoming
                                            </span>
                                        @break

                                        @default
                                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-medium border border-gray-200">
                                                Normal
                                            </span>
                                    @endswitch
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3.5 border-r border-gray-200 text-center">
                                    @if($record->status == 'active')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-full font-medium border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs px-3 py-1 rounded-full font-medium border border-red-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-2">

                                        <!-- Flash Sale Modal Button -->
                                        <button type="button"
                                                onclick="openFlashSaleModal('{{ $record->id }}', '{{ addslashes($record->name) }}')"
                                                class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                title="Add to Flash Sale">
                                            <i class="fa-solid fa-bolt text-xs"></i>
                                        </button>

                                        <!-- View -->
                                        <a href="{{ route('products.show', $record->id) }}"
                                           class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-100 transition-all duration-200 shadow-xs cursor-pointer"
                                           title="View Product">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('products.edit', $record) }}"
                                           class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-200 shadow-xs cursor-pointer"
                                           title="Edit Product">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <!-- Delete Form with SweetAlert Integration -->
                                        <form action="{{ route('products.destroy', $record) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="delete-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-all duration-200 shadow-xs cursor-pointer"
                                                    title="Delete Product">
                                                <i class="fa-solid fa-trash text-xs pointer-events-none"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    @else

                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 block text-gray-300"></i>
                                <span>No Products Found</span>
                            </td>
                        </tr>

                    @endif

                    </tbody>

                </table>

                <!-- Pagination -->
                @if($records->hasPages())
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        {{ $records->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Flash Sale Modal -->
    <div id="flashSaleModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative animate-in fade-in zoom-in duration-200">

            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Add to Flash Sale</h3>
                    <p id="modal-product-name" class="text-xs text-emerald-600 font-semibold mt-0.5"></p>
                </div>
                <button type="button" onclick="closeFlashSaleModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Flash Sale Form -->
            <form id="flashSaleForm" action="{{ route('flash-sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="modal_product_id">

                <div class="space-y-4 mb-6">
                    <!-- Discount Percentage -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Discount Percentage (%)</label>
                        <input type="number" name="discount_percentage" min="1" max="99" placeholder="e.g. 20"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-emerald-500 outline-none" required>
                        <p class="text-[10px] text-gray-400 mt-1">This percentage will apply to all variants of this product.</p>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Start Time</label>
                        <input type="datetime-local" name="start_time"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">End Time</label>
                        <input type="datetime-local" name="end_time"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-emerald-500 outline-none" required>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeFlashSaleModal()"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl cursor-pointer transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-md cursor-pointer transition">
                        Save Flash Sale
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal JavaScript Functions -->
    <script>
        function openFlashSaleModal(productId, productName) {
            document.getElementById('modal_product_id').value = productId;
            document.getElementById('modal-product-name').innerText = "Product: " + productName;
            document.getElementById('flashSaleModal').classList.remove('hidden');
        }

        function closeFlashSaleModal() {
            document.getElementById('flashSaleModal').classList.add('hidden');
        }
    </script>

@endsection