<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreGroupRequest;
use App\Http\Requests\Api\V1\Admin\UpdateGroupRequest;
use App\Http\Resources\Api\V1\Admin\AdminGroupResource;
use App\Models\Group;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminGroupController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Group::class);

        $query = Group::query()->withCount('leaders')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('short_description', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'created_at', 'updated_at'], 'name');

        return AdminGroupResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Group $group): AdminGroupResource
    {
        $this->authorize('view', $group);

        return new AdminGroupResource($group->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function store(StoreGroupRequest $request): AdminGroupResource
    {
        $this->authorize('create', Group::class);

        return new AdminGroupResource(Group::create($request->validated())->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function update(UpdateGroupRequest $request, Group $group): AdminGroupResource
    {
        $this->authorize('update', $group);

        $group->fill($request->validated())->save();

        return new AdminGroupResource($group->load(['leaders', 'media'])->loadCount('leaders'));
    }

    public function destroy(Group $group): JsonResponse
    {
        $this->authorize('delete', $group);

        $group->media()->detach();
        $group->delete();

        return response()->json(null, 204);
    }
}
