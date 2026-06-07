<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeConsultation extends Model
{
    protected $fillable = [
        'customer_id',
        'designer_id',
        'expires_at',
        'is_completed',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class);
    }
}