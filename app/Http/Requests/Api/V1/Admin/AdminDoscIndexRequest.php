<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminDoscIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'max:30'],
            'featured' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'],
            'active' => ['sometimes', 'boolean'],
            'social_project_id' => ['sometimes', 'integer', 'exists:social_projects,id'],
            'social_action_id' => ['sometimes', 'integer', 'exists:social_actions,id'],
            'category' => ['sometimes', 'string', 'max:255'],
            'location' => ['sometimes', 'string', 'max:255'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'deadline_from' => ['sometimes', 'date'],
            'deadline_to' => ['sometimes', 'date', 'after_or_equal:deadline_from'],
            'sort' => ['sometimes', 'string', 'max:50'],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
