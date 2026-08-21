<?php

namespace App\Services;

use App\Events\NewsletterSubscriberCreated;
use App\Events\NewsletterSubscriberUnsubscribed;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(array $data): array
    {
        $email = mb_strtolower(trim($data['email']));
        $name = $this->nullableClean(Arr::get($data, 'name'));
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (! $subscriber) {
            $subscriber = NewsletterSubscriber::create([
                'name' => $name,
                'email' => $email,
                'status' => 'subscribed',
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'unsubscribe_token' => Str::random(64),
            ]);

            NewsletterSubscriberCreated::dispatch($subscriber);

            return [$subscriber, true];
        }

        if ($subscriber->status !== 'subscribed') {
            $subscriber->forceFill([
                'name' => $name ?? $subscriber->name,
                'status' => 'subscribed',
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'unsubscribe_token' => $subscriber->unsubscribe_token ?: Str::random(64),
            ])->save();

            NewsletterSubscriberCreated::dispatch($subscriber);

            return [$subscriber, false];
        }

        if ($name && $subscriber->name !== $name) {
            $subscriber->forceFill(['name' => $name])->save();
        }

        return [$subscriber, false];
    }

    public function unsubscribe(array $data): NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('email', mb_strtolower(trim($data['email'])))
            ->where('unsubscribe_token', $data['unsubscribe_token'])
            ->firstOrFail();

        if ($subscriber->status !== 'unsubscribed') {
            $subscriber->forceFill([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ])->save();

            NewsletterSubscriberUnsubscribed::dispatch($subscriber);
        }

        return $subscriber;
    }

    private function nullableClean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim(strip_tags($value));

        return $cleaned === '' ? null : $cleaned;
    }
}
