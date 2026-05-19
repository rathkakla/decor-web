<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'designer_id',
        'title',
        'description',
        'budget_range',
        'status',
        'cover_image',
        'consultation_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($consultation) {
            if ($consultation->wasChanged('status') && $consultation->status == self::STATUS_COMPLETED) {
                $exists = \App\Models\DesignerPortfolio::where('consultation_id', $consultation->id)->exists();
                if (!$exists) {
                    $acceptedQuote = $consultation->quotes()->where('status', 'accepted')->first();
                    $budget = $acceptedQuote 
                        ? 'Rp ' . number_format($acceptedQuote->amount, 0, ',', '.') 
                        : $consultation->budget_range;

                    $coverImage = $consultation->getRawOriginal('cover_image');
                    if (!$coverImage) {
                        $firstImageAttachment = $consultation->attachments()
                            ->where('file_type', 'image')
                            ->first();
                        if ($firstImageAttachment) {
                            $coverImage = $firstImageAttachment->file_url;
                        }
                    }

                    \App\Models\DesignerPortfolio::create([
                        'designer_id' => $consultation->designer_id,
                        'consultation_id' => $consultation->id,
                        'title' => $consultation->title,
                        'image_url' => $coverImage ?? 'designers/portfolios/default.jpg',
                        'description' => 'Project otomatis ditambahkan setelah menyelesaikan konsultasi dengan customer.',
                        'category' => 'Completed Project',
                        'budget' => $budget,
                        'area' => '-',
                        'duration' => '-',
                    ]);
                }
            }
        });
    }

    public function getCoverImageAttribute($value)
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    // EXACT MAPPING FROM MOBILE APP (consultation_model.dart)
    const STATUS_WAITING_BRIEF = 0;
    const STATUS_DRAFTING = 1;
    const STATUS_ACTIVE = 1; 
    const STATUS_UNDER_REVIEW = 2;
    const STATUS_REVISION_REQUESTED = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_WAITING_APPROVAL = 5; 
    const STATUS_REJECTED = 6;
    const STATUS_WAITING_CONSULTATION_FEE = 7; // This is 'Waiting Payment' in mobile
    
    // New ones for the offer flow (use numbers higher than 7)
    const STATUS_OFFER_RECEIVED = 8;
    const STATUS_WAITING_FINAL_PAYMENT = 9;

    public static function getStatusLabel($status)
    {
        return [
            self::STATUS_WAITING_BRIEF => 'Waiting Brief',
            self::STATUS_DRAFTING => 'Active Workspace',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_REVISION_REQUESTED => 'Revision Requested',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_WAITING_APPROVAL => 'Waiting Approval',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_WAITING_CONSULTATION_FEE => 'Waiting Consultation Fee',
            self::STATUS_OFFER_RECEIVED => 'Offer Received',
            self::STATUS_WAITING_FINAL_PAYMENT => 'Waiting Final Payment',
        ][$status] ?? 'Unknown';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class);
    }

    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(ConsultationAttachment::class);
    }

    public function quotes()
    {
        return $this->hasMany(ConsultationQuote::class)->latest();
    }
}