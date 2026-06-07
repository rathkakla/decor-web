<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'studio_name',
        'specialty',
        'bio',
        'education',
        'awards',
        'experience_years',
        'designer_image',
        'banner_image',
        'is_open',
        'instagram_url',
        'linkedin_url',
        'bank_name',
        'account_number',
        'digital_signature',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function portfolios()
    {
        return $this->hasMany(DesignerPortfolio::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function reviews()
    {
        return $this->hasMany(ConsultationReview::class, 'designer_id');
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? number_format($avg, 1) : '0.0';
    }

    public function getAverageProjectDurationAttribute()
    {
        $reviews = $this->reviews()->whereNotNull('project_duration')->where('project_duration', '!=', '')->get();
        if ($reviews->isEmpty()) {
            return '-';
        }

        $totalMonths = 0;
        $count = 0;

        foreach ($reviews as $review) {
            $months = $this->parseDurationToMonths($review->project_duration);
            if ($months !== null) {
                $totalMonths += $months;
                $count++;
            }
        }

        if ($count === 0) {
            return '-';
        }

        $average = $totalMonths / $count;
        
        // Format to 1 decimal place if it has a fraction, otherwise whole number
        if (floor($average) == $average) {
            return number_format($average, 0) . ' Months';
        }
        return number_format($average, 1) . ' Months';
    }

    private function parseDurationToMonths($duration)
    {
        if (!$duration) return null;
        
        // Check if it's in the format "X-Y Months" (e.g. 1-3 Months)
        if (preg_match('/(\d+)-(\d+)\s+Months/i', $duration, $matches)) {
            $min = (int) $matches[1];
            $max = (int) $matches[2];
            return ($min + $max) / 2.0;
        }
        
        // Check if it's single number "X Months"
        if (preg_match('/(\d+)\s+Months/i', $duration, $matches)) {
            return (float) $matches[1];
        }
        
        // Check if it's "X Month"
        if (preg_match('/(\d+)\s+Month/i', $duration, $matches)) {
            return (float) $matches[1];
        }
        
        // Check if it contains weeks
        if (preg_match('/(\d+)-(\d+)\s+Weeks/i', $duration, $matches)) {
            $min = (int) $matches[1];
            $max = (int) $matches[2];
            return (($min + $max) / 2.0) / 4.0;
        }
        if (preg_match('/(\d+)\s+Weeks/i', $duration, $matches)) {
            return ((float) $matches[1]) / 4.0;
        }
        if (preg_match('/(\d+)\s+Week/i', $duration, $matches)) {
            return ((float) $matches[1]) / 4.0;
        }

        return null;
    }
}