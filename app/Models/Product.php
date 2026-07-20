<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(VariantImage::class);
    }

    public function category()
    {
        // Yahan 'category_id' lazmi specify karein kyunki aapke model ka naam Categorie hai
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function prod_brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id','id');
    }
    public function variant_images()
    {
        return $this->hasMany(VariantImage::class);
    }

    public function mainVariantImage()
    {
        // Product has many variants -> and variant belongs to VariantImage where is_main = 1
        return $this->hasOneThrough(
            VariantImage::class,
            ProductVariant::class,
            'product_id',
            'id',
            'id',
            'variant_image_id'
        )->where('variant_images.is_main', 1);
    }

    public function mainVariant()
    {
        return $this->hasOne(ProductVariant::class, 'product_id')
            ->whereHas('variantImage', function ($query) {
                $query->where('is_main', 1);
            });
    }
}
