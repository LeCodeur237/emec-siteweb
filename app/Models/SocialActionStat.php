<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialActionStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_action_id',
        'label',
        'value',
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(SocialAction::class, 'social_action_id');
    }
}
