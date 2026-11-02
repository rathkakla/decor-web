<?php

namespace App\Models;

use App\Support\B2bNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bOrder extends Model
{
    use HasFactory;

    protected $table = 'b2b_orders';

    public const STATUS_DP_PENDING = 'DP_PENDING';
    public const STATUS_IN_PRODUCTION = 'IN_PRODUCTION';
    public const STATUS_READY_FOR_DELIVERY = 'READY_FOR_DELIVERY';
    public const STATUS_DELIVERED = 'DELIVERED';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_CANCELLED = 'CANCELLED';

    public const PAYMENT_UNPAID = 'UNPAID';
    public const PAYMENT_DP_HELD = 'DP_HELD';
    public const PAYMENT_FULL_HELD = 'FULL_HELD';
    public const PAYMENT_RELEASED = 'RELEASED_TO_SELLER';

    public const PAYOUT_NOT_ELIGIBLE = 'NOT_ELIGIBLE';
    public const PAYOUT_AVAILABLE = 'AVAILABLE';
    public const PAYOUT_REQUESTED = 'REQUESTED';
    public const PAYOUT_PAID = 'PAID';

    /** 5 pos produksi sesuai brief. */
    public const STAGES = [
        1 => 'Persiapan Bahan Baku Kayu',
        2 => 'Pemotongan & Pembentukan Rangka',
        3 => 'Perakitan Konstruksi',
        4 => 'Finishing Semprot & Pelapisan',
        5 => 'Pemeriksaan Kendali Mutu (QC)',
    ];

    protected $fillable = [
        'order_number',
        'rfq_id',
        'quotation_id',
        'total_amount',
        'escrow_balance',
        'payment_status',
        'dp_paid_at',
        'settled_at',
        'status',
        'payout_status',
        'payout_requested_at',
        'payout_paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'escrow_balance' => 'decimal:2',
        'dp_paid_at' => 'datetime',
        'settled_at' => 'datetime',
        'payout_requested_at' => 'datetime',
        'payout_paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            $order->order_number ??= B2bNumber::make(B2bNumber::ORDER, 'b2b_orders', 'order_number');
        });
    }

    public function rfq()
    {
        return $this->belongsTo(B2bRfq::class, 'rfq_id');
    }

    public function quotation()
    {
        return $this->belongsTo(B2bQuotation::class, 'quotation_id');
    }

    public function milestones()
    {
        return $this->hasMany(B2bMilestone::class, 'order_id')->orderBy('stage_order');
    }

    public function delivery()
    {
        return $this->hasOne(B2bDelivery::class, 'order_id');
    }

    public function bast()
    {
        return $this->hasOne(B2bBast::class, 'order_id');
    }

    public function getDesignerAttribute()
    {
        return $this->rfq?->designer;
    }

    public function getSellerAttribute()
    {
        return $this->rfq?->seller;
    }

    public function getProgressPercentAttribute(): int
    {
        $milestones = $this->relationLoaded('milestones') ? $this->milestones : $this->milestones()->get();

        if ($milestones->isEmpty()) {
            return 0;
        }

        return (int) round($milestones->avg('progress_percent'));
    }

    public function getRemainingAmountAttribute(): float
    {
        return round((float) $this->total_amount - (float) $this->escrow_balance, 2);
    }

    /**
     * Seed 5 pos produksi saat kontrak dibuat.
     */
    public function createMilestones(): void
    {
        foreach (self::STAGES as $order => $name) {
            $this->milestones()->firstOrCreate(
                ['stage_name' => $name],
                ['stage_order' => $order, 'progress_percent' => 0]
            );
        }
    }
}
