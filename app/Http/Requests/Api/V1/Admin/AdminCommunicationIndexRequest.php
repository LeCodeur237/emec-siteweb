<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use Illuminate\Validation\Rule;

class AdminCommunicationIndexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'max:30'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
            'sort' => ['sometimes', 'string', 'max:50'],
            'direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        ];
    }
}
