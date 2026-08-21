<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\Church;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreChurchRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Church::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash:ascii', Rule::unique('churches', 'slug')],
            'baptism_name' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'locality' => ['nullable', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'circumscription' => ['nullable', 'string', 'max:255'],
            'mission_field' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pastor_vision' => ['nullable', 'string'],
            'contact' => ['nullable', 'string', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
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
