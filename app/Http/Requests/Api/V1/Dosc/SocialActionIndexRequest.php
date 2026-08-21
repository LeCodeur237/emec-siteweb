<?php

namespace App\Http\Requests\Api\V1\Dosc;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class SocialActionIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'social_project_id' => ['sometimes', 'integer', 'exists:social_projects,id'],
            'category' => ['sometimes', 'string', 'max:100'],
            'location' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['published'])],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'sort' => ['sometimes', Rule::in(['action_date', 'title', 'created_at', 'beneficiaries_count'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
