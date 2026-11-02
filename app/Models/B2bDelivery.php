<?php

namespace App\Models;

use App\Support\B2bNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bDelivery extends Model
{
    use HasFactory;

    protected $table = 'b2b_deliveries';

    public const STATUS_PREPARING = 'PREPARING';
    public const STATUS_IN_TRANSIT = 'IN_TRANSIT';
    public const STATUS_DELIVERED = 'DELIVERED';

    protected $fillable = [
        'surat_jalan_number',
        'order_id',
        'courier_service',
        'driver_name',
        'vehicle_plate',
        'delivery_date',
        'destination_address',
        'recipient_name',
        'recipient_phone',
        'workshop_signature_url',
        'workshop_signed_at',
        'driver_signature_url',
        'driver_signed_at',
        'recipient_signature_url',
        'recipient_signed_at',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'workshop_signed_at' => 'datetime',
        'driver_signed_at' => 'datetime',
        'recipient_signed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $delivery) {
            $delivery->surat_jalan_number ??= B2bNumber::make(B2bNumber::SURAT_JALAN, 'b2b_deliveries', 'surat_jalan_number');
        });
    }

    public function order()
    {
        return $this->belongsTo(B2bOrder::class, 'order_id');
    }
}
