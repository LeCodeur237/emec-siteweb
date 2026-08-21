<?php

namespace App\Http\Requests\Api\V1\Dosc;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class SocialProjectIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['active'])],
            'featured' => ['sometimes', 'boolean'],
            'deadline_from' => ['sometimes', 'date'],
            'deadline_to' => ['sometimes', 'date', 'after_or_equal:deadline_from'],
            'sort' => ['sometimes', Rule::in(['title', 'deadline', 'created_at', 'beneficiaries_count'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
