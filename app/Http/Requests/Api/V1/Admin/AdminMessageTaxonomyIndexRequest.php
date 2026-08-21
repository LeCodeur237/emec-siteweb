<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use Illuminate\Validation\Rule;

class AdminMessageTaxonomyIndexRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        $routeName = (string) $this->route()?->getName();
        $model = str_contains($routeName, 'message-categories')
            ? MessageCategory::class
            : MessageSeries::class;

        return $this->user()?->can('viewAny', $model) ?? false;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', Rule::in(['name', 'created_at', 'updated_at'])],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
