<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
    ];

    protected $attributes = [
        'status' => 'subscribed',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
