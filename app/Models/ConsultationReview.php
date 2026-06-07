<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationReview extends Model
{
    protected $fillable = [
        'consultation_id',
        'customer_id',
        'designer_id',
        'rating',
        'comment',
        'project_duration',
        'designer_reply',
        'designer_replied_at',
    ];

    protected $casts = [
        'designer_replied_at' => 'datetime',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class, 'designer_id');
    }
}