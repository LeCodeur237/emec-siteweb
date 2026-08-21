<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public ContactMessage $contactMessage) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event' => 'contact_message_received',
            'contact_message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'subject' => $this->contactMessage->subject,
            'status' => $this->contactMessage->status,
            'created_at' => $this->contactMessage->created_at?->toISOString(),
        ];
    }
}
