<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandsBanner extends Model
{
    protected $fillable = [
        'name', 'button_name', 'description', 'image', 'is_title', 'is_image', 'is_description', 'is_button'
    ];
}
