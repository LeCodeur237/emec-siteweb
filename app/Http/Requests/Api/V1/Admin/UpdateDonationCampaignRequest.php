<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\DonationCampaign;

class UpdateDonationCampaignRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var DonationCampaign|null $campaign */
        $campaign = $this->route('donationCampaign');

        return $campaign !== null && ($this->user()?->can('update', $campaign) ?? false);
    }

    public function rules(): array
    {
        return [
            'social_project_id' => ['sometimes', 'nullable', 'integer', 'exists:social_projects,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'goal_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'active' => ['sometimes', 'boolean'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
