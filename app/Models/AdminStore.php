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
    ];

    // Boolean values ko automatic true/false casting ke liye
    protected $casts = [
        'is_logo' => 'boolean',
        'is_title' => 'boolean',
    ];
}

