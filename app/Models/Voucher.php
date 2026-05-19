<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_purchase',
        'max_discount',
        'start_date',
        'end_date',
        'quota'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }
}