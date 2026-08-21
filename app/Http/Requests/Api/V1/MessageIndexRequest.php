<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Validation\Rule;

class MessageIndexRequest extends ApiFormRequest
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
            'search' => ['sometimes', 'string', 'max:120'],
            'preacher_id' => ['sometimes', 'integer', 'exists:preachers,id'],
            'message_category_id' => ['sometimes', 'integer', 'exists:message_categories,id'],
            'message_series_id' => ['sometimes', 'integer', 'exists:message_series,id'],
            'featured' => ['sometimes', 'boolean'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'sort' => ['sometimes', Rule::in(['preached_at', 'title', 'created_at', 'views'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
