<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total_price',
        'shipping_courier',
        'shipping_recipient',
        'shipping_phone',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_address',
        'payment_method',
        'status',
        'has_reviewed',
    ];

    // Relasi: Satu pesanan punya banyak barang (Order Items)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Satu pesanan dimiliki oleh satu Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function productReturn()
    {
        return $this->hasOne(ProductReturn::class);
    }
}