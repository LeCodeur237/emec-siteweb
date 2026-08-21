<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminEmecIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', 'max:30'],
            'city' => ['sometimes', 'string', 'max:255'],
            'region' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', 'max:255'],
            'event_category_id' => ['sometimes', 'integer', 'exists:event_categories,id'],
            'church_id' => ['sometimes', 'integer', 'exists:churches,id'],
            'group_id' => ['sometimes', 'integer', 'exists:groups,id'],
            'featured' => ['sometimes', 'boolean'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'day_of_week' => ['sometimes', 'integer', 'between:1,7'],
            'sort' => ['sometimes', 'string', 'max:50'],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
