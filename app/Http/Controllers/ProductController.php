<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\VariantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'prod_brand', 'variants.variantImage'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $records = $query->paginate(5);

        return view('product.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent_data = Categorie::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();

        return view('product.create-edit', compact('parent_data', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name'        => 'required|string|max:255',
        ]);

        // Slug
        $slug = Str::slug($request->name);

        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Product Save
        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'brand_id'    => $request->brand_id,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'status'      => $request->status ?? 'active',
        ]);

        // Variant Save
        if ($request->has('variants_group')) {

            $mainVariantIndex = $request->input('is_main');

            foreach ($request->variants_group as $index => $group) {

                $variantImageId = null;

                // Variant Image Upload
                if ($request->hasFile("variants_group.{$index}.color_image")) {

                    $vImage = $request->file("variants_group.{$index}.color_image");

                    $filename = time() . "_variant_{$index}_" .
                        preg_replace('/[^A-Za-z0-9\-.]/', '_', $vImage->getClientOriginalName());

                    $folder = storage_path('app/public/products/variants');

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    $vImage->move($folder, $filename);

                    $isMainImage = ($mainVariantIndex !== null && (int)$mainVariantIndex === (int)$index) ? 1 : 0;

                    $variantImage = VariantImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'products/variants/' . $filename,
                        'is_main'    => $isMainImage,
                    ]);

                    $variantImageId = $variantImage->id;
                }

                // Sizes
                if (isset($group['items']) && is_array($group['items'])) {

                    foreach ($group['items'] as $item) {

                        $sku = !empty($item['sku'])
                            ? $item['sku']
                            : 'SKU-' . strtoupper(Str::random(8));

                        ProductVariant::create([
                            'product_id'       => $product->id,
                            'variant_image_id' => $variantImageId,
                            'color_name'       => $group['color'] ?? 'Default',
                            'size_system'      => $group['size_system'] ?? null,
                            'size'             => $item['size'],
                            'cut_price'        => $item['cut_price'],
                            'price'            => $item['price'],
                            'stock'            => $item['quantity'],
                            'sku'              => $sku,
                        ]);
                    }
                }
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product and Variants saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'prod_brand', 'variants.variantImage']);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $parent_data = Categorie::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();
        $product->with('variants.variantImage');

//        dd($product);

        return view('product.create-edit', compact('parent_data', 'product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Slug update logic
        $slug = Str::slug($request->name);
        if ($slug !== $product->slug) {
            $count = Product::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $product->id)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
        } else {
            $slug = $product->slug;
        }



        // Main Image update handler
        if ($request->hasFile('main_image')) {
            // Purani file delete karein (Using direct storage path)
            if (!empty($product->main_image)) {
                $oldMainImagePath = storage_path('app/public/products/' . $product->main_image);
                if (file_exists($oldMainImagePath)) {
                    @unlink($oldMainImagePath);
                }
            }


        }

        // Product update
        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'brand_id'    => $request->brand_id,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'status'      => $request->status ?? 'active',

        ]);

        if ($request->has('variants_group')) {
            $keepVariantIds = [];
            $selectedMainImageId = $request->input('is_main');

            foreach ($request->variants_group as $index => $group) {
                $variantImageId = !empty($group['old_variant_image_id']) ? $group['old_variant_image_id'] : null;

                if (!$variantImageId && !empty($group['color'])) {
                    $existingVariant = ProductVariant::where('product_id', $product->id)
                        ->where('color_name', $group['color'])
                        ->whereNotNull('variant_image_id')
                        ->first();

                    if ($existingVariant) {
                        $variantImageId = $existingVariant->variant_image_id;
                    }
                }

                // Variants color images upload
                if ($request->hasFile("variants_group.{$index}.color_image")) {
                    if ($variantImageId) {
                        $oldImgRecord = VariantImage::find($variantImageId);
                        if ($oldImgRecord) {
                            // Purani variant image direct file system se delete karein
                            $oldVariantPath = storage_path('app/public/' . $oldImgRecord->image_path);
                            if (file_exists($oldVariantPath)) {
                                @unlink($oldVariantPath);
                            }
                            $oldImgRecord->delete();
                        }
                    }

                    $vImage = $request->file('variants_group')[$index]['color_image'];
                    $vImageName = time() . '_variant_' . $index . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '_', $vImage->getClientOriginalName());

                    // Same store method wala path folder aur movement logic
                    $folder = storage_path('app/public/products/variants');
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    $vImage->move($folder, $vImageName);

                    $variantImageRecord = VariantImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'products/variants/' . $vImageName, // Store format ke mutabik relative path save kiya
                        'is_main'    => 0,
                    ]);

                    $variantImageId = $variantImageRecord->id;

                    if ($selectedMainImageId !== null && (int)$selectedMainImageId === (int)($group['old_variant_image_id'] ?? 0)) {
                        $selectedMainImageId = $variantImageId;
                    }
                }

                // Variants items (Sizes, Price, Stock) loop
                if (isset($group['items']) && is_array($group['items'])) {
                    foreach ($group['items'] as $item) {
                        $sku = !empty($item['sku']) ? $item['sku'] : 'SKU-' . strtoupper(Str::random(8));

                        $variant = ProductVariant::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'color_name' => $group['color'] ?? 'Default',
                                'size'       => $item['size'],
                            ],
                            [
                                'variant_image_id' => $variantImageId,
                                'size_system'      => $group['size_system'] ?? null,
                                'cut_price'        => $item['cut_price'],
                                'price'            => $item['price'],
                                'stock'            => $item['quantity'],
                                'sku'              => $sku,
                            ]
                        );
                        $keepVariantIds[] = $variant->id;
                    }
                }
            }

            // Jo variants form mein nahi aaye unhe database se remove karein
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $keepVariantIds)
                ->delete();

            // Global Selection Update
            if ($selectedMainImageId) {
                VariantImage::where('product_id', $product->id)->update(['is_main' => 0]);
                VariantImage::where('id', $selectedMainImageId)->update(['is_main' => 1]);
            }

            // Unused images clean up direct file system se
            $usedImageIds = ProductVariant::where('product_id', $product->id)
                ->whereNotNull('variant_image_id')
                ->pluck('variant_image_id')
                ->toArray();

            $unusedImages = VariantImage::where('product_id', $product->id)
                ->whereNotIn('id', $usedImageIds)
                ->get();

            foreach ($unusedImages as $oldImg) {
                $unusedImgPath = storage_path('app/public/' . $oldImg->image_path);
                if (file_exists($unusedImgPath)) {
                    @unlink($unusedImgPath);
                }
                $oldImg->delete();
            }
        }

        return redirect()->route('products.index')->with('success', 'Product and Variants updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 1. Main Image Delete karein (Using direct storage path)
        if ($product->main_image) {
            $mainImagePath = storage_path('app/public/products/' . $product->main_image);
            if (file_exists($mainImagePath)) {
                @unlink($mainImagePath);
            }
        }

        // 2. Variant Images Delete karein (Using direct storage path based on image_path)
        $variant_images = VariantImage::where('product_id', $product->id)->get();
        foreach ($variant_images as $v_img) {
            $variantImagePath = storage_path('app/public/' . $v_img->image_path);
            if (file_exists($variantImagePath)) {
                @unlink($variantImagePath);
            }
            $v_img->delete();
        }

        $product->variants()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product and all its variants deleted successfully!');
    }
}
