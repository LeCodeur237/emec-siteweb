<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Group;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Group|null $group */
        $group = $this->route('group');

        return $group !== null && ($this->user()?->can('update', $group) ?? false);
    }

    public function rules(): array
    {
        /** @var Group|null $group */
        $group = $this->route('group');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('groups', 'slug')->ignore($group?->id)],
            'description' => ['sometimes', 'nullable', 'string'],
            'short_description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'color' => ['sometimes', 'nullable', 'string', 'max:30'],
            'contact' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email:rfc', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
