<?php

namespace App\Http\Requests\Api\V1\Dosc;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class DonationCampaignIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'social_project_id' => ['sometimes', 'integer', 'exists:social_projects,id'],
            'active' => ['sometimes', 'boolean'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'sort' => ['sometimes', Rule::in(['start_date', 'end_date', 'created_at', 'title'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
