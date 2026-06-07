<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationQuote extends Model
{
    protected $fillable = [
        'consultation_id',
        'amount',
        'notes',
        'status',
        'items',
        'revision_notes',
        'design_image',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}