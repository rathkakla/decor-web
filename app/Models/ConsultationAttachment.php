<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'uploaded_by',
        'file_url',
        'file_type',
    ];

    public function getFileUrlAttribute($value)
    {
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}