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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function mainAddress()
    {
        return $this->hasOne(Address::class)->where('is_main', true);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}