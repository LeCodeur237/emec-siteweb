<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'image',
        'color',
        'contact',
        'email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function leaders(): HasMany
    {
        return $this->hasMany(GroupLeader::class);
    }
}
