<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Http\Requests\Api\V1\ApiFormRequest;
use App\Models\WeeklyProgram;

class StoreWeeklyProgramRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', WeeklyProgram::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
