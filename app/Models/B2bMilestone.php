<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2bMilestone extends Model
{
    use HasFactory;

    protected $table = 'b2b_milestones';

    protected $fillable = [
        'order_id',
        'stage_name',
        'stage_order',
        'progress_percent',
        'target_date',
        'photo_url',
        'qc_notes',
        'is_approved_by_designer',
    ];

    protected $casts = [
        'stage_order' => 'integer',
        'progress_percent' => 'integer',
        'target_date' => 'date',
        'is_approved_by_designer' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(B2bOrder::class, 'order_id');
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->progress_percent >= 100;
    }
}
