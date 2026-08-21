<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\DonationCampaign;

class StoreDonationCampaignRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', DonationCampaign::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'social_project_id' => ['nullable', 'integer', 'exists:social_projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'goal_amount' => ['required', 'numeric', 'min:0'],
            'active' => ['sometimes', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
