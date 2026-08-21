<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminUserRoleIndexRequest;
use App\Http\Requests\Api\V1\Admin\StorePermissionRequest;
use App\Http\Requests\Api\V1\Admin\UpdatePermissionRequest;
use App\Http\Resources\Api\V1\Admin\AdminPermissionResource;
use App\Models\Permission;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminPermissionController extends ApiController
{
    public function index(AdminUserRoleIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Permission::class);

        $query = Permission::query()->withCount('roles')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('slug', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['name', 'slug', 'created_at', 'updated_at'], 'slug');

        return AdminPermissionResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Permission $permission): AdminPermissionResource
    {
        $this->authorize('view', $permission);

        return new AdminPermissionResource($permission->loadCount('roles'));
    }

    public function store(StorePermissionRequest $request): AdminPermissionResource
    {
        $this->authorize('create', Permission::class);

        return new AdminPermissionResource(Permission::create($request->validated())->loadCount('roles'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): AdminPermissionResource
    {
        $this->authorize('update', $permission);

        $permission->fill($request->validated())->save();

        return new AdminPermissionResource($permission->loadCount('roles'));
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $this->authorize('delete', $permission);

        $permission->roles()->detach();
        $permission->delete();

        return response()->json(null, 204);
    }
}
