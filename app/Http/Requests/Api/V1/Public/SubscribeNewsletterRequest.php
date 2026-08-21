<?php

namespace App\Http\Requests\Api\V1\Public;

use App\Http\Requests\Api\V1\ApiFormRequest;

class SubscribeNewsletterRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'website' => ['prohibited'],
        ];
    }
}
