@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow border border-gray-200 overflow-hidden">

            <div class="flex justify-between items-center p-6 bg-gray-50">
                <h2 class="text-3xl font-bold text-gray-800">
                    Product Details
                </h2>

                <a href="{{ route('products.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
                    Back To Products
                </a>
            </div>

            <div class="p-6">

                <div class="grid md:grid-cols-2 gap-8">

                    <div>
                        @php
                            // Direct VariantImage table se is product ki saari images nikal rahe hain
                            $variantImages = \App\Models\VariantImage::where('product_id', $product->id)->get();
                            // Pehli image ko main display ke liye select kar rahe hain
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
                                    <div class="cursor-pointer border-2 {{ $index === 0 ? 'border-gray-300' : 'border-gray-200' }} hover:border-blue-500 rounded-lg overflow-hidden h-20 w-20 thumbnail-btn"
                                         onclick="changeImage('{{ asset('upload/product/variants/'.$vImage->image_path) }}', this)">
                                        <img src="{{ asset('storage/'.$vImage->image_path) }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">

                        <h1 class="text-4xl font-bold text-gray-900">
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
                                <p class="text-sm text-gray-500">Regular Price</p>
                                <p class="font-semibold text-green-600">
                                    Rs {{ number_format($product->regular_price,2) }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500">Base Price</p>
                                <p class="font-semibold text-blue-600">
                                    Rs {{ number_format($product->base_price,2) }}
                                </p>
                            </div>

                        </div>

                        <div>
                            <span class="font-semibold">Status:</span>

                            @if($product->status == 'active')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Active
                        </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Inactive
                        </span>
                            @endif
                        </div>

                        <div>
                            <span class="font-semibold">Featured:</span>

                            @if($product->is_featured)
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                            Yes
                        </span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                            No
                        </span>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-bold text-lg mb-2">
                                Description
                            </h4>

                            <p class="text-gray-600 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                    </div>

                </div>

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
                                    // Variants ko image path ke mutabiq group kar rahe hain
                                    $groupedVariants = $product->variants->groupBy(function($variant) {
                                        return $variant->variantImage ? $variant->variantImage->image_path : 'no-image';
                                    });
                                    $serialNumber = 1;
                                @endphp

                                @foreach($groupedVariants as $imagePath => $variants)
                                    @foreach($variants as $index => $variant)
                                        <tr class="hover:bg-gray-50/80 transition-colors">

                                            {{-- Serial Number --}}
                                            <td class="border border-gray-300 px-4 py-3 text-gray-800 align-middle">{{ $serialNumber++ }}</td>

                                            {{-- Image column: Isme p-0 use kiya hai taaki white space bilkul khatam ho jaye --}}
                                            @if($index === 0)
                                                <td class="border border-gray-300 p-0 text-center align-middle bg-white" rowspan="{{ $variants->count() }}" style="width: 100px; min-width: 100px;">
                                                    <div class="w-full h-24 overflow-hidden relative group p-2">
                                                        @if($imagePath !== 'no-image')
                                                            <img src="{{ asset('storage/'.$imagePath) }}"
                                                                 class="w-80 p-1 h-full object-cover transform  transition-transform duration-200 cursor-pointer rounded-lg border border-gray-200 shadow-sm"
                                                                 alt="Variant Thumbnail">
                                                        @else
                                                            <div class="w-full h-full bg-gray-50 flex flex-col items-center justify-center text-[11px] text-gray-400 font-medium rounded-lg border border-dashed border-gray-200">
                                                                <i class="fa-regular fa-image text-lg mb-1 opacity-60"></i>
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

    <script>
        function changeImage(imageSrc, element) {
            // 1. Main image source replace karein
            document.getElementById('mainProductImage').src = imageSrc;

            // 2. Pehle se active thumbnail borders ko hata kar gray karein
            document.querySelectorAll('.thumbnail-btn').forEach(btn => {
                btn.classList.remove('border-blue-500');
                btn.classList.add('border-gray-200');
            });

            // 3. Current select kiye gaye thumbnail ko blue border lagayein
            element.classList.remove('border-gray-200');
            element.classList.add('border-blue-500');
        }
    </script>

@endsection
