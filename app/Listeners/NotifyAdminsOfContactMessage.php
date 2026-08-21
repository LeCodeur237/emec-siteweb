<?php

namespace App\Listeners;

use App\Events\ContactMessageReceived;
use App\Notifications\ContactMessageNotification;
use App\Services\Notifications\AdminNotificationRecipientService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminsOfContactMessage implements ShouldQueue
{
    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(private AdminNotificationRecipientService $recipients) {}

    public function handle(ContactMessageReceived $event): void
    {
        Notification::send(
            $this->recipients->usersFor('communication.manage'),
            new ContactMessageNotification($event->contactMessage)
        );
    }
}
