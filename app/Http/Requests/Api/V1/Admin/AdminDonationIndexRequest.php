<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminDonationIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', 'max:30'],
            'type' => ['sometimes', 'string', 'max:50'],
            'provider' => ['sometimes', 'string', 'max:255'],
            'donation_campaign_id' => ['sometimes', 'integer', 'exists:donation_campaigns,id'],
            'donation_method_id' => ['sometimes', 'integer', 'exists:donation_methods,id'],
            'social_project_id' => ['sometimes', 'integer', 'exists:social_projects,id'],
            'anonymous' => ['sometimes', 'boolean'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'paid_from' => ['sometimes', 'date'],
            'paid_to' => ['sometimes', 'date', 'after_or_equal:paid_from'],
            'sort' => ['sometimes', 'string', 'max:50'],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
