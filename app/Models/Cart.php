<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['customer_id'];

    // Menghubungkan ke item-item di dalam keranjang
    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }
}