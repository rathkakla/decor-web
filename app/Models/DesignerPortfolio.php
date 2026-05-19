<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignerPortfolio extends Model
{
    use HasFactory;

    protected $table = 'designer_portfolios';

    protected $fillable = [
        'designer_id',
        'consultation_id',
        'title',
        'image_url',
        'is_360',
        'description',
        'category',
        'budget',
        'area',
        'duration',
    ];

    public function designer()
    {
        return $this->belongsTo(Designer::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}