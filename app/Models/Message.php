<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'preacher_id',
        'message_category_id',
        'message_series_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'preached_at',
        'duration',
        'youtube_video_id',
        'youtube_url',
        'audio_url',
        'pdf_url',
        'thumbnail',
        'featured',
        'status',
        'views',
    ];

    protected $casts = [
        'preached_at' => 'date',
        'featured' => 'boolean',
        'views' => 'integer',
    ];

    public function preacher(): BelongsTo
    {
        return $this->belongsTo(Preacher::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MessageCategory::class, 'message_category_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(MessageSeries::class, 'message_series_id');
    }
}
