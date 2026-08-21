<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\DonationMethod;
use Illuminate\Validation\Rule;

class UpdateDonationMethodRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var DonationMethod|null $method */
        $method = $this->route('donationMethod');

        return $method !== null && ($this->user()?->can('update', $method) ?? false);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'type' => ['sometimes', 'required', Rule::in(['mobile_money', 'bank_transfer', 'cash', 'other'])],
            'provider' => ['sometimes', 'nullable', 'string', 'max:255'],
            'account_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'account_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'instructions' => ['sometimes', 'nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
