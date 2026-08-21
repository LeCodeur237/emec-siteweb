<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\MessageSeries;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreMessageSeriesRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', MessageSeries::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('message_series', 'slug')],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge(['slug' => Str::slug($this->string('name')->toString())]);
        }
    }
}
