<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupLeader extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'group_id',
        'name',
        'responsibility',
        'image',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
