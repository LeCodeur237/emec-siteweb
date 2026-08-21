<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialProject extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'image',
        'goal_amount',
        'raised_amount',
        'beneficiaries_count',
        'deadline',
        'status',
        'featured',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'beneficiaries_count' => 'integer',
        'deadline' => 'date',
        'featured' => 'boolean',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(SocialAction::class);
    }

    public function donationCampaigns(): HasMany
    {
        return $this->hasMany(DonationCampaign::class);
    }
}
