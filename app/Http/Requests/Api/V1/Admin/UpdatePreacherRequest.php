<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Preacher;
use Illuminate\Validation\Rule;

class UpdatePreacherRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var Preacher|null $preacher */
        $preacher = $this->route('preacher');

        return $preacher !== null && ($this->user()?->can('update', $preacher) ?? false);
    }

    public function rules(): array
    {
        /** @var Preacher|null $preacher */
        $preacher = $this->route('preacher');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('preachers', 'slug')->ignore($preacher?->id)],
            'role' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
