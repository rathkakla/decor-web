<?php

namespace App\Models;

use App\Support\B2bNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bRfq extends Model
{
    use HasFactory;

    protected $table = 'b2b_rfqs';

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_QUOTED = 'QUOTED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_IN_ORDER = 'IN_ORDER';
    public const STATUS_CANCELLED = 'CANCELLED';

    public const SAMPLE_NOT_REQUESTED = 'NOT_REQUESTED';
    public const SAMPLE_PREPARING = 'PREPARING';
    public const SAMPLE_SENT = 'SENT_BY_COURIER';
    public const SAMPLE_APPROVED = 'APPROVED_BY_DESIGNER';

    public const WOOD_TYPES = [
        'Jati Solid',
        'Mahoni',
        'Sungkai',
        'Plywood HPL',
        'Plywood Duco',
    ];

    protected $fillable = [
        'rfq_number',
        'designer_id',
        'seller_id',
        'project_name',
        'wood_type',
        'budget_ceiling',
        'deadline',
        'delivery_address',
        'description',
        'items',
        'cad_url',
        'boq_url',
        'moodboard_url',
        'sample_status',
        'tracking_number',
        'courier_name',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'budget_ceiling' => 'decimal:2',
        'deadline' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $rfq) {
            $rfq->rfq_number ??= B2bNumber::make(B2bNumber::RFQ, 'b2b_rfqs', 'rfq_number');
        });
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function quotations()
    {
        return $this->hasMany(B2bQuotation::class, 'rfq_id');
    }

    public function latestQuotation()
    {
        return $this->hasOne(B2bQuotation::class, 'rfq_id')->latestOfMany();
    }

    public function order()
    {
        return $this->hasOne(B2bOrder::class, 'rfq_id');
    }

    public function negotiations()
    {
        return $this->hasMany(B2bNegotiation::class, 'rfq_id')->orderBy('created_at');
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeForDesigner($query, int $designerId)
    {
        return $query->where('designer_id', $designerId);
    }

    public function getIsSampleApprovedAttribute(): bool
    {
        return $this->sample_status === self::SAMPLE_APPROVED;
    }
}
