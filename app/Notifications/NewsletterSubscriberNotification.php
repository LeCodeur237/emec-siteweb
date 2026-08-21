<?php

namespace App\Notifications;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewsletterSubscriberNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event' => 'newsletter_subscriber_created',
            'newsletter_subscriber_id' => $this->subscriber->id,
            'name' => $this->subscriber->name,
            'status' => $this->subscriber->status,
            'subscribed_at' => $this->subscriber->subscribed_at?->toISOString(),
        ];
    }
}
