<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\ChurchLeader;

class UpdateChurchLeaderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var ChurchLeader|null $churchLeader */
        $churchLeader = $this->route('churchLeader');

        return $churchLeader !== null && ($this->user()?->can('update', $churchLeader) ?? false);
    }

    public function rules(): array
    {
        return [
            'church_id' => ['sometimes', 'required', 'integer', 'exists:churches,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'responsibility' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
