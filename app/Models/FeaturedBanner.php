<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedBanner extends Model
{
    protected $fillable = [
        'name',
        'button_name',
        'description',
        'image',
        'sort_order',
        'is_title',
        'is_image',
        'is_description',
    ];


}
