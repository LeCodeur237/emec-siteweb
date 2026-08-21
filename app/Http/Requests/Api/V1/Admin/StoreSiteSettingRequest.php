<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SiteSetting;
use Illuminate\Validation\Rule;

class StoreSiteSettingRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SiteSetting::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('site_settings', 'key')],
            'value' => ['nullable', 'string'],
            'type' => ['sometimes', Rule::in(['string', 'text', 'integer', 'float', 'boolean', 'json', 'url', 'email'])],
            'group' => ['nullable', 'string', 'max:255'],
        ];
    }
}
