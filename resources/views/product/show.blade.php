@extends('layouts.app')

@section('content')

    <div class="p-6">

        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

            <div class="flex justify-between items-center p-6 bg-gray-50">
                <h2 class="text-2xl font-bold text-gray-800">
                    Product Details
                </h2>

                <a href="{{ route('products.index') }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-3 py-1.5 rounded-md transition-colors">
                    Back To Products
                </a>
            </div>

            <div class="p-6">

                <!-- 12 Column Grid System -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                    <!-- Image Section (4 Columns - Reduced from 5) -->
                    <div class="md:col-span-4">
                        @php
                            $variantImages = \App\Models\VariantImage::where('product_id', $product->id)->get();
                            $firstImage = $variantImages->first();
                        @endphp

                        @if($firstImage)
                            <img src="{{ asset('storage/'.$firstImage->image_path) }}"
                                 id="mainProductImage"
                                 class="w-full h-96 object-cover rounded-xl border border-gray-200 transition-all duration-300">
                        @else
                            <div class="w-full h-96 bg-gray-100 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400">
                                No Image Available
                            </div>
                        @endif

                        @if($variantImages->count() > 0)
                            <div class="flex flex-wrap gap-3 mt-4">
                                @foreach($variantImages as $index => $vImage)
                                    <div class="cursor-pointer border-2 {{ $index === 0 ? 'border-gray-100' : 'border-gray-200' }} hover:border-gray-200 rounded-lg overflow-hidden h-20 w-20 thumbnail-btn transition-all"
                                         onclick="changeImage('{{ asset('storage/'.$vImage->image_path) }}', this)">
                                        <img src="{{ asset('storage/'.$vImage->image_path) }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Details Section (8 Columns - Increased from 7) -->
                    <div class="md:col-span-8 space-y-4">

                        <h1 class="text-2xl font-bold text-gray-800 mb-4">
                            {{ $product->name }}
                        </h1>

                        <div class="grid grid-cols-2 gap-4">

                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500">Category</p>
                                <p class="font-semibold">
                                    {{ $product->category->name ?? '-' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500">Brand</p>
                                <p class="font-semibold">
                                    {{ $product->prod_brand->name ?? '-' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500">Price</p>
                                <p class="font-semibold text-green-600">
                                    Rs {{ number_format($product->variants->first()->price ?? 0, 2) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500">Cut Price</p>
                                <p class="font-semibold text-gray-400 line-through">
                                    Rs {{ number_format($product->variants->first()->cut_price ?? 0, 2) }}
                                </p>
                            </div>

                        </div>

                        <div>
                            <span class="font-semibold">Status:</span>

                            @if($product->status == 'active')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Active
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="font-semibold">Featured:</span>

                            @if($product->is_featured)
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Yes
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                                    No
                                </span>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-bold text-lg mb-2">
                                Description
                            </h4>

                            <div class="text-gray-600 leading-relaxed">
                                {!! $product->description !!}
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Professional Flash Sale Management Section (Responsive Fix) -->
                <div class="mt-10 bg-white border border-gray-200/80 rounded-2xl p-4 sm:p-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-bolt text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 tracking-tight">Flash Sale Configuration</h3>
                                <p class="text-xs text-gray-500 font-medium">Manage promotional discount and timeline for this product.</p>
                            </div>
                        </div>

                        @if(!$product->flashSale)
                            <button onclick="openFlashSaleModal()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4.5 py-2.5 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Add Flash Sale</span>
                            </button>
                        @endif
                    </div>

                    @if($product->flashSale)
                        <div class="flex flex-col md:flex-row md:items-center justify-between bg-gradient-to-r from-amber-50/60 via-amber-50/20 to-transparent border border-amber-200/50 rounded-2xl p-4 gap-4">
                            <!-- Data Items -->
                            <div class="grid grid-cols-2 sm:flex sm:items-center gap-4 sm:gap-6 lg:gap-8">
                                <div>
                                    <span class="block text-[10px] font-extrabold uppercase tracking-widest text-amber-600/70 mb-0.5">Discount</span>
                                    <span class="text-sm sm:text-base font-extrabold text-amber-700 tracking-tight">{{ number_format($product->flashSale->discount_percentage, 2) }}% OFF</span>
                                </div>

                                <div class="hidden sm:block h-8 w-[1px] bg-amber-200/80"></div>

                                <div>
                                    <span class="block text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-0.5">Start Time</span>
                                    <span class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($product->flashSale->start_time)->format('M d, Y') }}</span>
                                </div>

                                <div class="hidden sm:block h-8 w-[1px] bg-amber-200/80"></div>

                                <div>
                                    <span class="block text-[10px] font-extrabold uppercase tracking-widest text-gray-400 mb-0.5">End Time</span>
                                    <span class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($product->flashSale->end_time)->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-amber-200/40 md:border-t-0 md:pt-0">
                                <button onclick="openFlashSaleModal('{{ $product->flashSale->discount_percentage }}', '{{ \Carbon\Carbon::parse($product->flashSale->start_time)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($product->flashSale->end_time)->format('Y-m-d') }}')"
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center shadow-2xs transition cursor-pointer" title="Edit Flash Sale">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                <form id="delete-flash-sale-form-{{ $product->flashSale->id }}" action="{{ route('flash-sales.destroy', $product->flashSale->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmDelete({{ $product->flashSale->id }})"
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-red-50/60 border border-red-100 text-red-600 hover:bg-red-100 flex items-center justify-center shadow-2xs transition cursor-pointer" title="Delete Flash Sale">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 text-gray-400 text-xs font-medium">
                            No active flash sale configured for this product yet.
                        </div>
                    @endif
                </div>

                <!-- Product Variants Table -->
                <div class="mt-10">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        Product Variants
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">#</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Image</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Size</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Color</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Price</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">Stock</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold text-gray-700">SKU</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($product->variants->count() > 0)
                                @php
                                    $groupedVariants = $product->variants->groupBy(function($variant) {
                                        return $variant->variantImage ? $variant->variantImage->image_path : 'no-image';
                                    });
                                    $serialNumber = 1;
                                @endphp

                                @foreach($groupedVariants as $imagePath => $variants)
                                    @foreach($variants as $index => $variant)
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="border border-gray-300 px-4 py-3 text-gray-800 align-middle">{{ $serialNumber++ }}</td>

                                            @if($index === 0)
                                                <td class="border border-gray-300 p-2 text-center align-middle bg-white" rowspan="{{ $variants->count() }}" style="width: 100px;">
                                                    <div class="w-20 h-20 mx-auto overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                                                        @if($imagePath !== 'no-image')
                                                            <img src="{{ asset('storage/'.$imagePath) }}"
                                                                 class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform"
                                                                 onclick="changeImage('{{ asset('storage/'.$imagePath) }}', null)"
                                                                 alt="Variant Thumbnail">
                                                        @else
                                                            <div class="w-full h-full bg-gray-50 flex flex-col items-center justify-center text-[11px] text-gray-400 font-medium">
                                                                <span>No Image</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif

                                            <td class="border border-gray-300 px-4 py-3 text-gray-800 align-middle">{{ $variant->size }}</td>
                                            <td class="border border-gray-300 px-4 py-3 text-gray-800 align-middle">{{ $variant->color_name }}</td>
                                            <td class="border border-gray-300 px-4 py-3 font-medium text-gray-900 align-middle">Rs {{ number_format($variant->price, 2) }}</td>
                                            <td class="border border-gray-300 px-4 py-3 text-gray-800 align-middle">{{ $variant->stock }}</td>
                                            <td class="border border-gray-300 px-4 py-3 text-gray-700 font-mono text-sm align-middle">{{ $variant->sku }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="border border-gray-300 text-center py-6 text-gray-500">
                                        No Variants Found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Flash Sale Modal -->
    <div id="flashSaleModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Configure Flash Sale</h3>
                <button onclick="closeFlashSaleModal()" class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('flash-sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Discount Percentage (%)</label>
                        <input type="number" name="discount_percentage" id="modal_discount"
                               min="1" max="99" placeholder="e.g. 50"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-amber-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Start Time</label>
                        <input type="date" name="start_time" id="modal_start_time"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-amber-500 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">End Time</label>
                        <input type="date" name="end_time" id="modal_end_time"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-amber-500 outline-none" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeFlashSaleModal()"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function changeImage(imageSrc, element) {
            const mainImg = document.getElementById('mainProductImage');
            if(mainImg) {
                mainImg.src = imageSrc;
            }

            if (element) {
                document.querySelectorAll('.thumbnail-btn').forEach(btn => {
                    btn.classList.remove('border-blue-500');
                    btn.classList.add('border-gray-200');
                });

                element.classList.remove('border-gray-200');
                element.classList.add('border-blue-500');
            }
        }

        function openFlashSaleModal(discount = '', startTime = '', endTime = '') {
            document.getElementById('modal_discount').value = discount;
            document.getElementById('modal_start_time').value = startTime;
            document.getElementById('modal_end_time').value = endTime;
            document.getElementById('flashSaleModal').classList.remove('hidden');
        }

        function closeFlashSaleModal() {
            document.getElementById('flashSaleModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this flash sale!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-flash-sale-form-' + id).submit();
                }
            });
        }
    </script>

@endsection