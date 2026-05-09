<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'order_id',
        'reason',
        'status',
        'return_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}