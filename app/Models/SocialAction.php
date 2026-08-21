<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialAction extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'social_project_id',
        'title',
        'slug',
        'category',
        'description',
        'location',
        'action_date',
        'image',
        'youtube_video_id',
        'beneficiaries_count',
        'status',
    ];

    protected $casts = [
        'action_date' => 'date',
        'beneficiaries_count' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(SocialProject::class, 'social_project_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany(SocialActionStat::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
