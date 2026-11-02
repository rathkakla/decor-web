<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bNegotiation extends Model
{
    use HasFactory;

    protected $table = 'b2b_negotiations';

    protected $fillable = [
        'rfq_id',
        'sender_id',
        'message',
        'attachment_url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function rfq()
    {
        return $this->belongsTo(B2bRfq::class, 'rfq_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getIsFromDesignerAttribute(): bool
    {
        return $this->rfq && $this->sender_id === $this->rfq->designer?->user_id;
    }
}
