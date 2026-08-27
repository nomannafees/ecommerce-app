<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sort_order',
        'is_title',
        'is_image',
        'is_description',
    ];
}
