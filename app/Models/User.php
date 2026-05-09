<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
    'username',
    'full_name',
    'email',
    'password',
    'role',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function designer()
    {
        return $this->hasOne(Designer::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->role === 'customer' && $this->customer && $this->customer->profile_image) {
            return asset('storage/' . $this->customer->profile_image);
        }

        if ($this->role === 'seller' && $this->seller && $this->seller->store_image) {
            return asset('storage/' . $this->seller->store_image);
        }

        return 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($this->username);
    }
}