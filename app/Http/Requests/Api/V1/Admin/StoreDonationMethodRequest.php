<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\DonationMethod;
use Illuminate\Validation\Rule;

class StoreDonationMethodRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', DonationMethod::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['mobile_money', 'bank_transfer', 'cash', 'other'])],
            'provider' => ['nullable', 'string', 'max:255'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
