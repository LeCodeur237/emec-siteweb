<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Models\WeeklyProgram;

class UpdateWeeklyProgramRequest extends StoreWeeklyProgramRequest
{
    public function authorize(): bool
    {
        /** @var WeeklyProgram|null $weeklyProgram */
        $weeklyProgram = $this->route('weeklyProgram');

        return $weeklyProgram !== null && ($this->user()?->can('update', $weeklyProgram) ?? false);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'day_of_week' => ['sometimes', 'required', 'integer', 'between:1,7'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'nullable', 'date_format:H:i', 'after:start_time'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
