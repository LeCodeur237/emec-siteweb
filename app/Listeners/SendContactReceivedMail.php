<?php

namespace App\Listeners;

use App\Events\ContactMessageReceived;
use App\Mail\ContactReceivedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendContactReceivedMail implements ShouldQueue
{
    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function handle(ContactMessageReceived $event): void
    {
        Mail::to($event->contactMessage->email)->send(new ContactReceivedMail($event->contactMessage));
    }
}
