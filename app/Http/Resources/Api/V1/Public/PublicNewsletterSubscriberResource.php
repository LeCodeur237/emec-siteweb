<?php

namespace App\Http\Resources\Api\V1\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicNewsletterSubscriberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'subscribed_at' => $this->subscribed_at?->toISOString(),
            'unsubscribed_at' => $this->unsubscribed_at?->toISOString(),
        ];
    }
}
