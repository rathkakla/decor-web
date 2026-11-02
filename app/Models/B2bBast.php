<?php

namespace App\Models;

use App\Support\B2bNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bBast extends Model
{
    use HasFactory;

    protected $table = 'b2b_basts';

    protected $fillable = [
        'bast_number',
        'order_id',
        'designer_signature_url',
        'photo_installed_url',
        'inspection_checklist',
        'is_accepted',
        'signed_at',
    ];

    protected $casts = [
        'inspection_checklist' => 'array',
        'is_accepted' => 'boolean',
        'signed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $bast) {
            $bast->bast_number ??= B2bNumber::make(B2bNumber::BAST, 'b2b_basts', 'bast_number');
        });
    }

    public function order()
    {
        return $this->belongsTo(B2bOrder::class, 'order_id');
    }
}
