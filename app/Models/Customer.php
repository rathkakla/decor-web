<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memberi izin pengisian data
    protected $fillable = [
    'user_id',
    'phone',
    'profile_image',
    'address', 
    'city',    
    'address_2', // <-- Tambahkan ini
    'city_2',    // <-- Tambahkan ini
];

    // Opsional: Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}