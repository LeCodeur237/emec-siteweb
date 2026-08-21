<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminUserRoleIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreRoleRequest;
use App\Http\Requests\Api\V1\Admin\UpdateRoleRequest;
use App\Http\Resources\Api\V1\Admin\AdminRoleResource;
use App\Models\Role;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminRoleController extends ApiController
{
    public function index(AdminUserRoleIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);

        $query = Role::query()->with('permissions')->withCount(['users', 'permissions'])
            ->when($request->filled('permission_id'), fn ($query) => $query->whereHas('permissions', fn ($query) => $query->where('permissions.id', $request->integer('permission_id'))))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('slug', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('description', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['name', 'slug', 'created_at', 'updated_at'], 'name');

        return AdminRoleResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Role $role): AdminRoleResource
    {
        $this->authorize('view', $role);

        return new AdminRoleResource($role->load('permissions')->loadCount(['users', 'permissions']));
    }

    public function store(StoreRoleRequest $request): AdminRoleResource
    {
        $this->authorize('create', Role::class);

        $data = $request->validated();
        $permissionIds = $data['permission_ids'] ?? [];
        unset($data['permission_ids']);

        $role = Role::create($data);
        $role->permissions()->sync($permissionIds);

        return new AdminRoleResource($role->load('permissions')->loadCount(['users', 'permissions']));
    }

    public function update(UpdateRoleRequest $request, Role $role): AdminRoleResource
    {
        $this->authorize('update', $role);

        $data = $request->validated();
        $permissionIds = $data['permission_ids'] ?? null;
        unset($data['permission_ids']);

        $role->fill($data)->save();

        if (is_array($permissionIds)) {
            $role->permissions()->sync($permissionIds);
        }

        return new AdminRoleResource($role->load('permissions')->loadCount(['users', 'permissions']));
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return response()->json(null, 204);
    }
}
