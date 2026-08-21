<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\AdministrativeLeader;

class UpdateAdministrativeLeaderRequest extends StoreAdministrativeLeaderRequest
{
    public function authorize(): bool
    {
        /** @var AdministrativeLeader|null $administrativeLeader */
        $administrativeLeader = $this->route('administrativeLeader');

        return $administrativeLeader !== null && ($this->user()?->can('update', $administrativeLeader) ?? false);
    }

    public function rules(): array
    {
        $rules = parent::rules();
        foreach ($rules as $key => $rule) {
            array_unshift($rules[$key], $key === 'name' || $key === 'responsibility' ? 'sometimes' : 'sometimes');
        }
        $rules['name'] = ['sometimes', 'required', 'string', 'max:255'];
        $rules['responsibility'] = ['sometimes', 'required', 'string', 'max:255'];

        return $rules;
    }
}
