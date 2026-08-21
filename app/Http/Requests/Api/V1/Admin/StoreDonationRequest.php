<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Donation;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Donation::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'donation_campaign_id' => ['nullable', 'integer', 'exists:donation_campaigns,id'],
            'donation_method_id' => ['nullable', 'integer', 'exists:donation_methods,id'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email:rfc', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'transaction_reference' => ['nullable', 'string', 'max:255', Rule::unique('donations', 'transaction_reference')],
            'status' => ['sometimes', Rule::in(['pending', 'paid', 'failed', 'cancelled', 'refunded'])],
            'anonymous' => ['sometimes', 'boolean'],
            'paid_at' => ['nullable', 'date'],
        ];
    }
}
