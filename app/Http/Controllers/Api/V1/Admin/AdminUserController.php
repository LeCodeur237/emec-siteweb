<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminUserRoleIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminUserRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminUserRequest;
use App\Http\Resources\Api\V1\Admin\AdminUserResource;
use App\Models\User;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminUserController extends ApiController
{
    public function index(AdminUserRoleIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $query = User::query()->with('roles.permissions')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('role_id'), fn ($query) => $query->whereHas('roles', fn ($query) => $query->where('roles.id', $request->integer('role_id'))))
            ->when($request->filled('permission_id'), fn ($query) => $query->whereHas('roles.permissions', fn ($query) => $query->where('permissions.id', $request->integer('permission_id'))))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('email', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('phone', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['name', 'email', 'status', 'created_at', 'updated_at'], 'created_at');

        return AdminUserResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(User $user): AdminUserResource
    {
        $this->authorize('view', $user);

        return new AdminUserResource($user->load('roles.permissions'));
    }

    public function store(StoreAdminUserRequest $request): AdminUserResource
    {
        $this->authorize('create', User::class);

        $data = $request->validated();
        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $user = User::create($data);
        $user->roles()->sync($roleIds);

        return new AdminUserResource($user->load('roles.permissions'));
    }

    public function update(UpdateAdminUserRequest $request, User $user): AdminUserResource
    {
        $this->authorize('update', $user);

        $data = $request->validated();
        $roleIds = $data['role_ids'] ?? null;
        unset($data['role_ids']);

        if (array_key_exists('password', $data) && $data['password'] === null) {
            unset($data['password']);
        }

        $user->fill($data)->save();

        if (is_array($roleIds)) {
            $user->roles()->sync($roleIds);
        }

        return new AdminUserResource($user->load('roles.permissions'));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->tokens()->delete();
        $user->delete();

        return response()->json(null, 204);
    }
}
