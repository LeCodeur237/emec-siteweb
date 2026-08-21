<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Message;
use Illuminate\Validation\Rule;

class AdminMessageIndexRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Message::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'preacher_id' => ['sometimes', 'integer', 'exists:preachers,id'],
            'message_category_id' => ['sometimes', 'integer', 'exists:message_categories,id'],
            'message_series_id' => ['sometimes', 'integer', 'exists:message_series,id'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
            'featured' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', Rule::in(['preached_at', 'title', 'created_at', 'updated_at', 'views'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
