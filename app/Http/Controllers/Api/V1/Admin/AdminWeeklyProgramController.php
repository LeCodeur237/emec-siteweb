<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreWeeklyProgramRequest;
use App\Http\Requests\Api\V1\Admin\UpdateWeeklyProgramRequest;
use App\Http\Resources\Api\V1\Admin\AdminWeeklyProgramResource;
use App\Models\WeeklyProgram;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminWeeklyProgramController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', WeeklyProgram::class);

        $query = WeeklyProgram::query()
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('location', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('day_of_week'), fn ($query) => $query->where('day_of_week', $request->integer('day_of_week')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['title', 'day_of_week', 'start_time', 'created_at', 'updated_at'], 'day_of_week');

        return AdminWeeklyProgramResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))
                ->orderBy('start_time')
                ->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(WeeklyProgram $weeklyProgram): AdminWeeklyProgramResource
    {
        $this->authorize('view', $weeklyProgram);

        return new AdminWeeklyProgramResource($weeklyProgram);
    }

    public function store(StoreWeeklyProgramRequest $request): AdminWeeklyProgramResource
    {
        $this->authorize('create', WeeklyProgram::class);

        return new AdminWeeklyProgramResource(WeeklyProgram::create($request->validated()));
    }

    public function update(UpdateWeeklyProgramRequest $request, WeeklyProgram $weeklyProgram): AdminWeeklyProgramResource
    {
        $this->authorize('update', $weeklyProgram);

        $weeklyProgram->fill($request->validated())->save();

        return new AdminWeeklyProgramResource($weeklyProgram);
    }

    public function destroy(WeeklyProgram $weeklyProgram): JsonResponse
    {
        $this->authorize('delete', $weeklyProgram);

        $weeklyProgram->delete();

        return response()->json(null, 204);
    }
}
