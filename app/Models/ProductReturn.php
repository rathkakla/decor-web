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
        'return_type',
        'reason',
        'status',
        'return_date',
        'video_proof',
        'photo_proof',
        'bank_account_number',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}