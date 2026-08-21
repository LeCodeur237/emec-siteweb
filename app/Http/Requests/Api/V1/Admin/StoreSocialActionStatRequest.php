<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialActionStat;

class StoreSocialActionStatRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', SocialActionStat::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'social_action_id' => ['required', 'integer', 'exists:social_actions,id'],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }
}
