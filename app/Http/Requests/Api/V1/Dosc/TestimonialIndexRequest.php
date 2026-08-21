<?php

namespace App\Http\Requests\Api\V1\Dosc;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class TestimonialIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'social_action_id' => ['sometimes', 'integer', 'exists:social_actions,id'],
            'social_project_id' => ['sometimes', 'integer', 'exists:social_projects,id'],
            'sort' => ['sometimes', Rule::in(['created_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
