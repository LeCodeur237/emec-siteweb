<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Church extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'name',
        'slug',
        'baptism_name',
        'city',
        'address',
        'neighborhood',
        'locality',
        'sector',
        'district',
        'circumscription',
        'mission_field',
        'region',
        'description',
        'pastor_vision',
        'contact',
        'map_url',
        'image',
        'status',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function leaders(): HasMany
    {
        return $this->hasMany(ChurchLeader::class);
    }
}
