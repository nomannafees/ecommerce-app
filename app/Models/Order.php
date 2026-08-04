<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'name',
        'phone',
        'email',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'shipping_address',
        'shipping_cost',
        'subtotal',
        'discount',
        'total',
        'status',
        'payment_status',
    ];

    function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state(){
        return $this->belongsTo(State::class, 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
