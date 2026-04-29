<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'is_selected'];

    // Relasi balik ke Cart
    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    // Relasi ke Product (Sangat penting untuk menampilkan detail barang di keranjang)
    public function product() {
        return $this->belongsTo(Product::class);
    }
}