<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminSiteSettingIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreSiteSettingRequest;
use App\Http\Requests\Api\V1\Admin\UpdateSiteSettingRequest;
use App\Http\Resources\Api\V1\Admin\AdminSiteSettingResource;
use App\Models\SiteSetting;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminSiteSettingController extends ApiController
{
    public function index(AdminSiteSettingIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', SiteSetting::class);

        $query = SiteSetting::query()
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->when($request->filled('group'), fn ($query) => $query->where('group', $request->string('group')->toString()))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('key', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('value', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('group', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['key', 'type', 'group', 'created_at', 'updated_at'], 'key');

        return AdminSiteSettingResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(SiteSetting $siteSetting): AdminSiteSettingResource
    {
        $this->authorize('view', $siteSetting);

        return new AdminSiteSettingResource($siteSetting);
    }

    public function store(StoreSiteSettingRequest $request): AdminSiteSettingResource
    {
        $this->authorize('create', SiteSetting::class);

        return new AdminSiteSettingResource(SiteSetting::create($request->validated()));
    }

    public function update(UpdateSiteSettingRequest $request, SiteSetting $siteSetting): AdminSiteSettingResource
    {
        $this->authorize('update', $siteSetting);

        $siteSetting->fill($request->validated())->save();

        return new AdminSiteSettingResource($siteSetting);
    }

    public function destroy(SiteSetting $siteSetting): JsonResponse
    {
        $this->authorize('delete', $siteSetting);

        $siteSetting->delete();

        return response()->json(null, 204);
    }
}
