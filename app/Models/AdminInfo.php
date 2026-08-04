<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jazzcash_no',
        'easypaisa_no',
        'address',
        'image',
    ];

    // User Relation
    public function user()
    {
        return $table->belongsTo(User::class);
    }
}
