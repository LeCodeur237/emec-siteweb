<?php

namespace App\Listeners;

use App\Events\NewsletterSubscriberCreated;
use App\Mail\NewsletterSubscriptionConfirmedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNewsletterSubscriptionMail implements ShouldQueue
{
    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function handle(NewsletterSubscriberCreated $event): void
    {
        Mail::to($event->subscriber->email)->send(new NewsletterSubscriptionConfirmedMail($event->subscriber));
    }
}
