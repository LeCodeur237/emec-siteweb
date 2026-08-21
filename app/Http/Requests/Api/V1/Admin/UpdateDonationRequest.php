<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Donation;
use Illuminate\Validation\Rule;

class UpdateDonationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Donation|null $donation */
        $donation = $this->route('donation');

        return $donation !== null && ($this->user()?->can('update', $donation) ?? false);
    }

    public function rules(): array
    {
        /** @var Donation|null $donation */
        $donation = $this->route('donation');

        return [
            'donation_campaign_id' => ['sometimes', 'nullable', 'integer', 'exists:donation_campaigns,id'],
            'donation_method_id' => ['sometimes', 'nullable', 'integer', 'exists:donation_methods,id'],
            'donor_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'donor_email' => ['sometimes', 'nullable', 'email:rfc', 'max:255'],
            'donor_phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'transaction_reference' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('donations', 'transaction_reference')->ignore($donation?->id)],
            'status' => ['sometimes', Rule::in(['pending', 'paid', 'failed', 'cancelled', 'refunded'])],
            'anonymous' => ['sometimes', 'boolean'],
            'paid_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
