<?php

namespace App\Services;

use App\Events\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Arr;

class ContactMessageService
{
    public function create(array $data): ContactMessage
    {
        $contactMessage = ContactMessage::create([
            'name' => $this->clean($data['name']),
            'email' => mb_strtolower($this->clean($data['email'])),
            'phone' => $this->nullableClean(Arr::get($data, 'phone')),
            'subject' => $this->nullableClean(Arr::get($data, 'subject')),
            'message' => $this->clean($data['message']),
            'status' => 'new',
        ]);

        ContactMessageReceived::dispatch($contactMessage);

        return $contactMessage;
    }

    private function nullableClean(?string $value): ?string
    {
        $cleaned = $value === null ? null : $this->clean($value);

        return $cleaned === '' ? null : $cleaned;
    }

    private function clean(string $value): string
    {
        return trim(strip_tags($value));
    }
}
