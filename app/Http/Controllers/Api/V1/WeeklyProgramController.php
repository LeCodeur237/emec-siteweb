<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\WeeklyProgramResource;
use App\Models\WeeklyProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WeeklyProgramController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'day_of_week' => ['sometimes', 'integer', 'min:1', 'max:7'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = WeeklyProgram::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query
            ->when(isset($validated['day_of_week']), fn ($query) => $query->where('day_of_week', $validated['day_of_week']))
            ->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return WeeklyProgramResource::collection(
            $query->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
        );
    }

    public function show(int $id): WeeklyProgramResource
    {
        $program = WeeklyProgram::query()
            ->where('active', true)
            ->findOrFail($id);

        return new WeeklyProgramResource($program);
    }
}
