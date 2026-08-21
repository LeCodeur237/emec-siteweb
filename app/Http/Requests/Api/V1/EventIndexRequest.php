<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

class EventIndexRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'event_category_id' => ['sometimes', 'integer', 'exists:event_categories,id'],
            'city' => ['sometimes', 'string', 'max:120'],
            'status' => ['sometimes', 'string', 'max:30'],
            'featured' => ['sometimes', 'boolean'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'search' => ['sometimes', 'string', 'max:120'],
            'sort' => ['sometimes', Rule::in(['start_at', 'title', 'created_at', 'city'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
