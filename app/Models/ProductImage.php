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

    // Relasi balik ke Produk (Opsional tapi berguna banget)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}