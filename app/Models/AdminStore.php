<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'title',
        'address',
        'phone',
        'email',
        'is_logo',
        'is_title',
        'is_sliders',
        'show_mid_banners',
        'show_featured_banner',
        'show_brand_banner',
    ];

    // Boolean values ko automatic true/false casting ke liye
    protected $casts = [
        'is_logo' => 'boolean',
        'is_title' => 'boolean',
        'is_sliders' => 'boolean',
        'show_mid_banners' => 'boolean',
        'show_featured_banner' => 'boolean',
        'show_brand_banner' => 'boolean',
    ];
}

