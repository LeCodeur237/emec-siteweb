<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'provider',
        'account_name',
        'account_number',
        'instructions',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
