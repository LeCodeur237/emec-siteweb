<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminPreacherIndexRequest;
use App\Http\Requests\Api\V1\Admin\StorePreacherRequest;
use App\Http\Requests\Api\V1\Admin\UpdatePreacherRequest;
use App\Http\Resources\Api\V1\Admin\AdminPreacherResource;
use App\Models\Preacher;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminPreacherController extends ApiController
{
    public function index(AdminPreacherIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Preacher::class);

        $query = Preacher::query()
            ->withCount('messages')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'role', 'created_at', 'updated_at'], 'name');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'asc';

        return AdminPreacherResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Preacher $preacher): AdminPreacherResource
    {
        $this->authorize('view', $preacher);

        return new AdminPreacherResource($preacher->load('media')->loadCount('messages'));
    }

    public function store(StorePreacherRequest $request): AdminPreacherResource
    {
        $this->authorize('create', Preacher::class);

        $preacher = Preacher::create($request->validated());

        return new AdminPreacherResource($preacher->load('media')->loadCount('messages'));
    }

    public function update(UpdatePreacherRequest $request, Preacher $preacher): AdminPreacherResource
    {
        $this->authorize('update', $preacher);

        $preacher->fill($request->validated());
        $preacher->save();

        return new AdminPreacherResource($preacher->load('media')->loadCount('messages'));
    }

    public function destroy(Preacher $preacher): JsonResponse
    {
        $this->authorize('delete', $preacher);

        $preacher->media()->detach();
        $preacher->delete();

        return response()->json(null, 204);
    }
}
