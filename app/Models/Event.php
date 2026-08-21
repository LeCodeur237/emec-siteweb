<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'event_category_id',
        'title',
        'slug',
        'description',
        'image',
        'start_at',
        'end_at',
        'location',
        'city',
        'featured',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
}
