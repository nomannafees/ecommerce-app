<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
