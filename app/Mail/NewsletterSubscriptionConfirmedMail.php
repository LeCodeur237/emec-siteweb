<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function build(): self
    {
        return $this
            ->subject("Confirmation d'inscription - EMEC")
            ->view('emails.newsletter.subscription-confirmed');
    }
}
