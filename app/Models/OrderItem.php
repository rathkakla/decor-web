<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relasi: Satu order item mewakili satu produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi: Satu order item masuk ke dalam satu pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}