<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

abstract class ApiFormRequest extends FormRequest
{
    public function expectsJson(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        foreach (['active', 'featured', 'published', 'anonymous'] as $key) {
            if (! $this->has($key) || ! is_string($this->query($key))) {
                continue;
            }

            $value = strtolower($this->query($key));

            if (in_array($value, ['true', '1'], true)) {
                $this->merge([$key => true]);
            }

            if (in_array($value, ['false', '0'], true)) {
                $this->merge([$key => false]);
            }
        }
    }
}
