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
        'payment_proof',
        'chat_expires_at',
    ];

    protected $casts = [
        'chat_expires_at' => 'datetime',
    ];

    protected $appends = ['consultation_fee', 'final_amount', 'is_chat_expired'];

    public function getConsultationFeeAttribute()
    {
        return $this->consultation_type === 'request_proposal' ? 250000 : 50000;
    }

    public function getFinalAmountAttribute()
    {
        $quote = $this->quotes()->where('status', 'accepted')->first();
        return $quote ? (float)$quote->amount : null;
    }

    public function getIsChatExpiredAttribute()
    {
        if ($this->consultation_type === 'chat_consultation' && $this->chat_expires_at) {
            return $this->chat_expires_at->isPast();
        }
        return false;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($consultation) {
            if ($consultation->isDirty('status') && $consultation->status == self::STATUS_ACTIVE) {
                if ($consultation->consultation_type === 'chat_consultation' && is_null($consultation->chat_expires_at)) {
                    $consultation->chat_expires_at = now()->addMinutes(30);
                }
            }
        });

        static::updated(function ($consultation) {
            if ($consultation->wasChanged('status') && $consultation->status == self::STATUS_COMPLETED) {
                // Hanya tipe request_proposal yang otomatis masuk portfolio
                if ($consultation->consultation_type === 'request_proposal') {
                    $exists = \App\Models\DesignerPortfolio::where('consultation_id', $consultation->id)->exists();
                    if (!$exists) {
                        $acceptedQuote = $consultation->quotes()->where('status', 'accepted')->first();
                        $budget = $acceptedQuote 
                            ? 'Rp ' . number_format($acceptedQuote->amount, 0, ',', '.') 
                            : $consultation->budget_range;

                        $coverImage = $consultation->getRawOriginal('cover_image');
                        if (!$coverImage && $acceptedQuote && $acceptedQuote->design_image) {
                            $designImages = json_decode($acceptedQuote->design_image, true);
                            $coverImage = is_array($designImages) && count($designImages) > 0 ? $designImages[0] : $acceptedQuote->design_image;
                        }

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
                            'status' => 'approved',
                        ]);
                    }
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

    public function review()
    {
        return $this->hasOne(ConsultationReview::class, 'consultation_id');
    }
}