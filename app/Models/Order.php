<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Sesuaikan dengan nama kolom yang ada di database kamu ya
    protected $fillable = [
        'customer_id',
        'total_price',
        'shipping_courier',
        'payment_method',
        'status',
    ];
}