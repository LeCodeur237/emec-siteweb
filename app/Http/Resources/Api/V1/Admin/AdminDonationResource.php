<?php

namespace App\Http\Resources\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'donation_campaign_id' => $this->donation_campaign_id,
            'donation_method_id' => $this->donation_method_id,
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'donor_phone' => $this->donor_phone,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'transaction_reference' => $this->transaction_reference,
            'status' => $this->status,
            'anonymous' => $this->anonymous,
            'paid_at' => $this->paid_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'campaign' => $this->whenLoaded('campaign', fn () => $this->campaign ? [
                'id' => $this->campaign->id,
                'title' => $this->campaign->title,
                'active' => $this->campaign->active,
            ] : null),
            'method' => $this->whenLoaded('method', fn () => $this->method ? [
                'id' => $this->method->id,
                'name' => $this->method->name,
                'type' => $this->method->type,
                'provider' => $this->method->provider,
            ] : null),
        ];
    }
}
