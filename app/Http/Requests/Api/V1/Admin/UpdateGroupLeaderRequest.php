<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\GroupLeader;

class UpdateGroupLeaderRequest extends StoreGroupLeaderRequest
{
    public function authorize(): bool
    {
        /** @var GroupLeader|null $groupLeader */
        $groupLeader = $this->route('groupLeader');

        return $groupLeader !== null && ($this->user()?->can('update', $groupLeader) ?? false);
    }

    public function rules(): array
    {
        return [
            'group_id' => ['sometimes', 'required', 'integer', 'exists:groups,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'responsibility' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
