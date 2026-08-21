<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreGroupLeaderRequest;
use App\Http\Requests\Api\V1\Admin\UpdateGroupLeaderRequest;
use App\Http\Resources\Api\V1\Admin\AdminGroupLeaderResource;
use App\Models\GroupLeader;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminGroupLeaderController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', GroupLeader::class);

        $query = GroupLeader::query()->with('group')
            ->when($request->filled('group_id'), fn ($query) => $query->where('group_id', $request->integer('group_id')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('responsibility', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'responsibility', 'created_at', 'updated_at'], 'name');

        return AdminGroupLeaderResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(GroupLeader $groupLeader): AdminGroupLeaderResource
    {
        $this->authorize('view', $groupLeader);

        return new AdminGroupLeaderResource($groupLeader->load('group'));
    }

    public function store(StoreGroupLeaderRequest $request): AdminGroupLeaderResource
    {
        $this->authorize('create', GroupLeader::class);

        return new AdminGroupLeaderResource(GroupLeader::create($request->validated())->load('group'));
    }

    public function update(UpdateGroupLeaderRequest $request, GroupLeader $groupLeader): AdminGroupLeaderResource
    {
        $this->authorize('update', $groupLeader);

        $groupLeader->fill($request->validated())->save();

        return new AdminGroupLeaderResource($groupLeader->load('group'));
    }

    public function destroy(GroupLeader $groupLeader): JsonResponse
    {
        $this->authorize('delete', $groupLeader);

        $groupLeader->delete();

        return response()->json(null, 204);
    }
}
