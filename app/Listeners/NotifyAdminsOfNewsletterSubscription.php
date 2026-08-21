<?php

namespace App\Listeners;

use App\Events\NewsletterSubscriberCreated;
use App\Notifications\NewsletterSubscriberNotification;
use App\Services\Notifications\AdminNotificationRecipientService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfNewsletterSubscription implements ShouldQueue
{
    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(private AdminNotificationRecipientService $recipients) {}

    public function handle(NewsletterSubscriberCreated $event): void
    {
        Notification::send(
            $this->recipients->usersFor('communication.manage'),
            new NewsletterSubscriberNotification($event->subscriber)
        );
    }
}
