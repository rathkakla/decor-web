<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'phone_number',
        'bank_name',
        'account_number',
        'store_address',
        'store_description',
        'rating',
        'store_image',
        'store_banner',
        'status',
        'rejection_reason',
        // Profil B2B (pabrik mebel rekanan)
        'wood_specialties',
        'monthly_capacity',
        'city',
        'svlk_verified',
        'b2b_enabled',
        'portfolio_url',
    ];

    protected $casts = [
        'wood_specialties' => 'array',
        'monthly_capacity' => 'integer',
        'svlk_verified' => 'boolean',
        'b2b_enabled' => 'boolean',
        'rating' => 'decimal:1',
    ];

    protected $appends = ['store_image_url', 'store_banner_url'];

    public function getStoreImageUrlAttribute()
    {
        if ($this->store_image) {
            return asset('storage/' . $this->store_image);
        }
        return $this->user->avatar_url;
    }

    public function getStoreBannerUrlAttribute()
    {
        if ($this->store_banner) {
            return asset('storage/' . $this->store_banner);
        }
        return 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1400';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function b2bRfqs()
    {
        return $this->hasMany(B2bRfq::class, 'seller_id');
    }

    public function b2bOrders()
    {
        return $this->hasManyThrough(
            B2bOrder::class,
            B2bRfq::class,
            'seller_id',
            'rfq_id'
        );
    }
}