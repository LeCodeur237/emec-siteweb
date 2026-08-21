<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Church;
use Illuminate\Validation\Rule;

class UpdateChurchRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Church|null $church */
        $church = $this->route('church');

        return $church !== null && ($this->user()?->can('update', $church) ?? false);
    }

    public function rules(): array
    {
        /** @var Church|null $church */
        $church = $this->route('church');
        $rules = (new StoreChurchRequest)->rules();
        $rules['name'] = ['sometimes', 'required', 'string', 'max:255'];
        $rules['slug'] = ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('churches', 'slug')->ignore($church?->id)];
        foreach ($rules as $key => $rule) {
            if (! in_array($key, ['name', 'slug'], true)) {
                array_unshift($rules[$key], 'sometimes');
            }
        }

        return $rules;
    }
}
