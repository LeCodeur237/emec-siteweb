<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SiteSetting;
use Illuminate\Validation\Rule;

class UpdateSiteSettingRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var SiteSetting|null $siteSetting */
        $siteSetting = $this->route('siteSetting');

        return $siteSetting !== null && ($this->user()?->can('update', $siteSetting) ?? false);
    }

    public function rules(): array
    {
        /** @var SiteSetting|null $siteSetting */
        $siteSetting = $this->route('siteSetting');

        return [
            'key' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-z0-9_.-]+$/', Rule::unique('site_settings', 'key')->ignore($siteSetting?->id)],
            'value' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', Rule::in(['string', 'text', 'integer', 'float', 'boolean', 'json', 'url', 'email'])],
            'group' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
