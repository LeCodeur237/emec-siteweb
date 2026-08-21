<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public ContactMessage $contactMessage) {}

    public function build(): self
    {
        return $this
            ->subject('Votre message a bien ete recu - EMEC')
            ->view('emails.contact.received');
    }
}
