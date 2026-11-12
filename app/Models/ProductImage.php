<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // Pastikan fillable-nya ada supaya bisa simpan data lewat Controller
    protected $fillable = [
        'product_id',
        'img_url',
    ];

    /**
     * Nilai kolom di database berupa path relatif (mis. "/storage/products/x.png").
     * Kembalikan URL absolut supaya bisa langsung dipakai web maupun app mobile.
     */
    public function getImgUrlAttribute($value)
    {
        return storage_url($value);
    }

    // Relasi balik ke Produk (Opsional tapi berguna banget)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}