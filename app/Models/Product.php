<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id', 'category_id', 'name', 'description', 'price', 'stock','style'];

    // Relasi ke Gambar (Penting untuk tampil di Catalog & Cart)
    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    // Relasi ke Kategori
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Seller (Agar tahu siapa yang punya barang)
    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    // Relasi ke Review
    public function reviews() {
        return $this->hasMany(Review::class);
    }


}