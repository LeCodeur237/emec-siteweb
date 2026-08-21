<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChurchLeader extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'church_id',
        'name',
        'responsibility',
        'image',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
    ];

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }
}
