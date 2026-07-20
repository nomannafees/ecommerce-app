<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function image()
    {
        return $this->belongsTo(VariantImage::class, 'variant_image_id');
    }
    public function variantImage()
    {
        return $this->belongsTo(VariantImage::class, 'variant_image_id');
    }
}
