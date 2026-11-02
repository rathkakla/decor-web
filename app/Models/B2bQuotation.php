<?php

namespace App\Models;

use App\Support\B2bNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bQuotation extends Model
{
    use HasFactory;

    protected $table = 'b2b_quotations';

    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_SENT = 'SENT';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_REVISED = 'REVISED';
    public const STATUS_EXPIRED = 'EXPIRED';

    protected $fillable = [
        'quotation_number',
        'rfq_id',
        'material_cost',
        'labor_cost',
        'shipping_cost',
        'tax_percent',
        'total_price',
        'dp_percentage',
        'dp_amount',
        'estimated_days',
        'valid_until',
        'warranty_notes',
        'notes',
        'items',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'total_price' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'dp_percentage' => 'integer',
        'estimated_days' => 'integer',
        'valid_until' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $quotation) {
            $quotation->quotation_number ??= B2bNumber::make(B2bNumber::QUOTATION, 'b2b_quotations', 'quotation_number');
        });
    }

    public function rfq()
    {
        return $this->belongsTo(B2bRfq::class, 'rfq_id');
    }

    public function order()
    {
        return $this->hasOne(B2bOrder::class, 'quotation_id');
    }

    /**
     * Hitung ulang total dari komponen biaya + PPN, lalu dp_amount.
     */
    public function recalculate(): self
    {
        $subtotal = (float) $this->material_cost + (float) $this->labor_cost + (float) $this->shipping_cost;
        $tax = $subtotal * ((float) ($this->tax_percent ?? 0) / 100);

        $this->total_price = round($subtotal + $tax, 2);
        $this->dp_amount = round($this->total_price * ((int) ($this->dp_percentage ?? 0) / 100), 2);

        return $this;
    }

    public function getRemainingAmountAttribute(): float
    {
        return round((float) $this->total_price - (float) $this->dp_amount, 2);
    }
}
