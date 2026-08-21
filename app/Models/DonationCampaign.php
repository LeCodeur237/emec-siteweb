<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_project_id',
        'title',
        'description',
        'goal_amount',
        'active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(SocialProject::class, 'social_project_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
