<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'active' => 'boolean',
    ];
}
