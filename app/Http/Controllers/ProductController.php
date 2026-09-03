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
        $parent_data = Categorie::with('children.children')
            ->where('parent_id', 0)
            ->get();

        $brands = Brand::all();

        return view('product.create-edit', compact('parent_data', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required',
            'name'         => 'required|string|max:255',
            // Is line ko update karein:
            'product_type' => 'required|string|in:normal,featured,trending,bestseller,new_arrival,hot_deal,special_offer,top_rated,limited_edition,upcoming',
        ]);

        // Slug Generation
        $slug = Str::slug($request->name);

        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Product Save
        $product = Product::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => $slug,
            'description'  => $request->description,
            'brand_id'     => $request->brand_id,
            'product_type' => $request->product_type ?? 'normal',
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'status'       => $request->status ?? 'active',
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
        $parent_data = Categorie::with('children.children')
            ->where('parent_id', 0)
            ->get();

        $brands = Brand::all();

        $product->load('variants.variantImage');

        return view(
            'product.create-edit',
            compact('parent_data', 'product', 'brands')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id'  => 'required',
            'name'         => 'required|string|max:255',
            // Is line ko update karein:
            'product_type' => 'required|string|in:normal,featured,trending,bestseller,new_arrival,hot_deal,special_offer,top_rated,limited_edition,upcoming',
        ]);

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
            if (!empty($product->main_image)) {
                $oldMainImagePath = storage_path('app/public/products/' . $product->main_image);
                if (file_exists($oldMainImagePath)) {
                    @unlink($oldMainImagePath);
                }
            }
        }

        // Description Images Process
        $description = $this->processDescriptionImages(
            $request->description ?? '',
            $product
        );

        // Product update
        $product->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => $slug,
            'description'  => $description,
            'brand_id'     => $request->brand_id,
            'product_type' => $request->product_type ?? 'normal',
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'status'       => $request->status ?? 'active',
        ]);

        if ($request->has('variants_group')) {

            $keepVariantIds = [];

            // Selected main image ki value
            $mainVariantValue = $request->input('is_main');


            /*
            |--------------------------------------------------------------------------
            | Sab purani images ko pehle non-main karo
            |--------------------------------------------------------------------------
            */

            VariantImage::where('product_id', $product->id)
                ->update([
                    'is_main' => 0
                ]);


            foreach ($request->variants_group as $index => $group) {

                /*
                |--------------------------------------------------------------------------
                | OLD VARIANT IMAGE ID
                |--------------------------------------------------------------------------
                */

                $variantImageId = !empty($group['old_variant_image_id'])
                    ? $group['old_variant_image_id']
                    : null;


                /*
                |--------------------------------------------------------------------------
                | Agar hidden input se image ID nahi mili
                |--------------------------------------------------------------------------
                */

                if (
                    !$variantImageId &&
                    !empty($group['color'])
                ) {

                    $existingVariant = ProductVariant::where(
                        'product_id',
                        $product->id
                    )
                        ->where(
                            'color_name',
                            $group['color']
                        )
                        ->whereNotNull('variant_image_id')
                        ->first();


                    if ($existingVariant) {

                        $variantImageId =
                            $existingVariant->variant_image_id;

                    }
                }


                /*
                |--------------------------------------------------------------------------
                | NEW IMAGE UPLOAD
                |--------------------------------------------------------------------------
                */

                if (
                $request->hasFile(
                    "variants_group.{$index}.color_image"
                )
                ) {

                    $vImage = $request->file(
                        "variants_group.{$index}.color_image"
                    );


                    $vImageName =
                        time()
                        . '_variant_'
                        . $index
                        . '_'
                        . preg_replace(
                            '/[^A-Za-z0-9\-.]/',
                            '_',
                            $vImage->getClientOriginalName()
                        );


                    $folder = storage_path(
                        'app/public/products/variants'
                    );


                    if (!file_exists($folder)) {

                        mkdir($folder, 0777, true);

                    }


                    $vImage->move(
                        $folder,
                        $vImageName
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Create New Variant Image
                    |--------------------------------------------------------------------------
                    */

                    $variantImageRecord = VariantImage::create([

                        'product_id' => $product->id,

                        'image_path' =>
                            'products/variants/' . $vImageName,

                        'is_main' => 0,

                    ]);


                    $variantImageId =
                        $variantImageRecord->id;

                }


                /*
                |--------------------------------------------------------------------------
                | MAIN IMAGE SET
                |--------------------------------------------------------------------------
                */

                if (
                    $variantImageId &&
                    (string)$mainVariantValue === (string)$variantImageId
                ) {

                    VariantImage::where(
                        'id',
                        $variantImageId
                    )->update([

                        'is_main' => 1

                    ]);

                }


                /*
                |--------------------------------------------------------------------------
                | VARIANT ITEMS UPDATE / CREATE
                |--------------------------------------------------------------------------
                */

                if (
                    isset($group['items']) &&
                    is_array($group['items'])
                ) {

                    foreach ($group['items'] as $item) {

                        if (empty($item['size'])) {
                            continue;
                        }


                        $sku = !empty($item['sku'])
                            ? $item['sku']
                            : 'SKU-' . strtoupper(
                                Str::random(8)
                            );


                        $variant =
                            ProductVariant::updateOrCreate(

                                [

                                    'product_id' =>
                                        $product->id,

                                    'color_name' =>
                                        $group['color']
                                        ?? 'Default',

                                    'size' =>
                                        $item['size'],

                                ],

                                [

                                    'variant_image_id' =>
                                        $variantImageId,

                                    'size_system' =>
                                        $group['size_system']
                                        ?? null,

                                    'cut_price' =>
                                        $item['cut_price']
                                        ?? 0,

                                    'price' =>
                                        $item['price']
                                        ?? 0,

                                    'stock' =>
                                        $item['quantity']
                                        ?? 0,

                                    'sku' =>
                                        $sku,

                                ]

                            );


                        $keepVariantIds[] =
                            $variant->id;

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED VARIANTS
            |--------------------------------------------------------------------------
            */

            ProductVariant::where(
                'product_id',
                $product->id
            )
                ->when(
                    !empty($keepVariantIds),
                    function ($query) use ($keepVariantIds) {

                        $query->whereNotIn(
                            'id',
                            $keepVariantIds
                        );

                    }
                )
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | FIND USED IMAGE IDS
            |--------------------------------------------------------------------------
            */

            $usedImageIds =
                ProductVariant::where(
                    'product_id',
                    $product->id
                )
                    ->whereNotNull(
                        'variant_image_id'
                    )
                    ->pluck(
                        'variant_image_id'
                    )
                    ->unique()
                    ->toArray();


            /*
            |--------------------------------------------------------------------------
            | DELETE UNUSED IMAGES
            |--------------------------------------------------------------------------
            */

            $unusedImages =
                VariantImage::where(
                    'product_id',
                    $product->id
                )
                    ->whereNotIn(
                        'id',
                        $usedImageIds
                    )
                    ->get();


            foreach ($unusedImages as $oldImg) {

                $unusedImgPath =
                    storage_path(
                        'app/public/' .
                        $oldImg->image_path
                    );


                if (file_exists($unusedImgPath)) {

                    @unlink($unusedImgPath);

                }


                $oldImg->delete();

            }

        }

        return redirect()->route('products.index')->with('success', 'Product and Variants updated successfully!');
    }

    private function processDescriptionImages(string $description, ?Product $product = null): string
    {
        /*
        |--------------------------------------------------------------------------
        | Description Images Folder
        |--------------------------------------------------------------------------
        */

        $folder = storage_path('app/public/description-images');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }


        /*
        |--------------------------------------------------------------------------
        | Find Existing Images
        |--------------------------------------------------------------------------
        */

        $oldImages = [];

        if ($product && !empty($product->description)) {

            preg_match_all(
                '/<img[^>]+src=["\']([^"\']+)["\']/i',
                $product->description,
                $matches
            );

            $oldImages = $matches[1] ?? [];
        }


        /*
        |--------------------------------------------------------------------------
        | Find Base64 Images From New Description
        |--------------------------------------------------------------------------
        */

        preg_match_all(
            '/<img[^>]+src=["\'](data:image\/[^"\']+)["\']/i',
            $description,
            $base64Matches
        );

        $base64Images = $base64Matches[1] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Upload New Base64 Images
        |--------------------------------------------------------------------------
        */

        foreach ($base64Images as $base64Image) {

            if (
            !preg_match(
                '/^data:image\/(\w+);base64,(.+)$/',
                $base64Image,
                $imageData
            )
            ) {
                continue;
            }


            $extension = strtolower($imageData[1]);

            $allowedExtensions = [
                'jpeg',
                'jpg',
                'png',
                'webp',
                'gif',
            ];

            if (!in_array($extension, $allowedExtensions)) {
                continue;
            }


            $imageContent = base64_decode(
                $imageData[2],
                true
            );

            if ($imageContent === false) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Unique Filename
            |--------------------------------------------------------------------------
            */

            $filename =
                time()
                . '_description_'
                . Str::random(12)
                . '.'
                . $extension;


            $filePath = $folder . '/' . $filename;


            /*
            |--------------------------------------------------------------------------
            | Save Image
            |--------------------------------------------------------------------------
            */

            file_put_contents(
                $filePath,
                $imageContent
            );


            /*
            |--------------------------------------------------------------------------
            | Storage URL
            |--------------------------------------------------------------------------
            */

            $imageUrl = asset(
                'storage/description-images/' . $filename
            );


            /*
            |--------------------------------------------------------------------------
            | Replace Base64 With Storage URL
            |--------------------------------------------------------------------------
            */

            $description = str_replace(
                $base64Image,
                $imageUrl,
                $description
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Find Images Still Used In New Description
        |--------------------------------------------------------------------------
        */

        preg_match_all(
            '/<img[^>]+src=["\']([^"\']+)["\']/i',
            $description,
            $newMatches
        );

        $newImages = $newMatches[1] ?? [];


        /*
        |--------------------------------------------------------------------------
        | Delete Old Images Which Are No Longer Used
        |--------------------------------------------------------------------------
        */

        if ($product) {

            foreach ($oldImages as $oldImage) {

                /*
                |--------------------------------------------------------------------------
                | Convert URL To Relative Storage Path
                |--------------------------------------------------------------------------
                */

                $oldPath = parse_url(
                    $oldImage,
                    PHP_URL_PATH
                );


                if (!$oldPath) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Only Delete Our Description Images
                |--------------------------------------------------------------------------
                */

                if (
                !str_contains(
                    $oldPath,
                    '/storage/description-images/'
                )
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Check Whether Image Is Still Used
                |--------------------------------------------------------------------------
                */

                $stillUsed = false;

                foreach ($newImages as $newImage) {

                    if ($newImage === $oldImage) {

                        $stillUsed = true;

                        break;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Delete If Removed From Description
                |--------------------------------------------------------------------------
                */

                if (!$stillUsed) {

                    $filename = basename($oldPath);

                    $file = $folder . '/' . $filename;

                    if (file_exists($file)) {

                        @unlink($file);

                    }
                }
            }
        }


        return $description;
    }

    public function uploadDescriptionImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);

        $image = $request->file('image');

        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

        $folder = storage_path('app/public/description-images');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $image->move($folder, $filename);

        return response()->json([
            'success' => true,
            'url' => asset('storage/description-images/' . $filename),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 1. Main Image Delete
        if ($product->main_image) {
            $mainImagePath = storage_path('app/public/products/' . $product->main_image);
            if (file_exists($mainImagePath)) {
                @unlink($mainImagePath);
            }
        }

        // 2. Variant Images Delete
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

        return redirect()
            ->route('products.index')
            ->with('success', 'Product and all its variants deleted successfully!');
    }
}
