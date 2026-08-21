<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'social_action_id',
        'name',
        'location',
        'quote',
        'avatar',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(SocialAction::class, 'social_action_id');
    }
}
