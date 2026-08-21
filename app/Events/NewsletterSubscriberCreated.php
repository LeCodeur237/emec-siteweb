<?php

namespace App\Events;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriberCreated implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber) {}
}
