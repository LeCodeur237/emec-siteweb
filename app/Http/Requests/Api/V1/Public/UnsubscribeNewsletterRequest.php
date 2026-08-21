<?php

namespace App\Http\Requests\Api\V1\Public;

use App\Http\Requests\Api\V1\ApiFormRequest;

class UnsubscribeNewsletterRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:255'],
            'unsubscribe_token' => ['required', 'string', 'size:64'],
        ];
    }
}
