<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}
}
