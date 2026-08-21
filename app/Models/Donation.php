<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_campaign_id',
        'donation_method_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'transaction_reference',
        'status',
        'anonymous',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DonationCampaign::class, 'donation_campaign_id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(DonationMethod::class, 'donation_method_id');
    }
}
