<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreEventCategoryRequest;
use App\Http\Requests\Api\V1\Admin\UpdateEventCategoryRequest;
use App\Http\Resources\Api\V1\Admin\AdminEventCategoryResource;
use App\Models\EventCategory;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminEventCategoryController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', EventCategory::class);

        $query = EventCategory::query()->withCount('events')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'created_at', 'updated_at'], 'name');

        return AdminEventCategoryResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(EventCategory $eventCategory): AdminEventCategoryResource
    {
        $this->authorize('view', $eventCategory);

        return new AdminEventCategoryResource($eventCategory->loadCount('events'));
    }

    public function store(StoreEventCategoryRequest $request): AdminEventCategoryResource
    {
        $this->authorize('create', EventCategory::class);

        return new AdminEventCategoryResource(EventCategory::create($request->validated())->loadCount('events'));
    }

    public function update(UpdateEventCategoryRequest $request, EventCategory $eventCategory): AdminEventCategoryResource
    {
        $this->authorize('update', $eventCategory);

        $eventCategory->fill($request->validated())->save();

        return new AdminEventCategoryResource($eventCategory->loadCount('events'));
    }

    public function destroy(EventCategory $eventCategory): JsonResponse
    {
        $this->authorize('delete', $eventCategory);

        $eventCategory->delete();

        return response()->json(null, 204);
    }
}
