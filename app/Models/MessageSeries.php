<?php

namespace App\Models;

use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MessageSeries extends Model
{
    use HasFactory;
    use HasMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
