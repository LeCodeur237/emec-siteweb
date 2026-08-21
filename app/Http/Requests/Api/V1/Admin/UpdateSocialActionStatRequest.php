<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\SocialActionStat;

class UpdateSocialActionStatRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        /** @var SocialActionStat|null $socialActionStat */
        $socialActionStat = $this->route('actionStat');

        return $socialActionStat !== null && ($this->user()?->can('update', $socialActionStat) ?? false);
    }

    public function rules(): array
    {
        return [
            'social_action_id' => ['sometimes', 'required', 'integer', 'exists:social_actions,id'],
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'value' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
