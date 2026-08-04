<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProductInteraction extends Model
{

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'category_id',
        'brand_id',
        'weight',
    ];
}
