<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Categorie::class, 'parent_id','id');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

}
