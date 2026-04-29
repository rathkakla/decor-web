<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    /**
     * Properti fillable ini adalah 'gerbang' keamanan.
     * Hanya kolom yang terdaftar di sini yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'store_name',
        'phone_number',    // Ditangkap dari form identity/professional
        'bank_name',       // Ditangkap dari form professional
        'account_number',  // Ditangkap dari form professional
        'store_address',   // Ditangkap dari form logistics
        'store_description',
        'rating',
        'store_image',
    ];

    /**
     * Relasi ke User: Satu Seller dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}