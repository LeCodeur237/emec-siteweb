<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminMessageTaxonomyIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreMessageCategoryRequest;
use App\Http\Requests\Api\V1\Admin\UpdateMessageCategoryRequest;
use App\Http\Resources\Api\V1\Admin\AdminMessageCategoryResource;
use App\Models\MessageCategory;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminMessageCategoryController extends ApiController
{
    public function index(AdminMessageTaxonomyIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', MessageCategory::class);

        $query = MessageCategory::query()
            ->withCount('messages')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'created_at', 'updated_at'], 'name');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'asc';

        return AdminMessageCategoryResource::collection(
            $query->orderBy($sort, $direction)->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(MessageCategory $messageCategory): AdminMessageCategoryResource
    {
        $this->authorize('view', $messageCategory);

        return new AdminMessageCategoryResource($messageCategory->loadCount('messages'));
    }

    public function store(StoreMessageCategoryRequest $request): AdminMessageCategoryResource
    {
        $this->authorize('create', MessageCategory::class);

        $category = MessageCategory::create($request->validated());

        return new AdminMessageCategoryResource($category->loadCount('messages'));
    }

    public function update(UpdateMessageCategoryRequest $request, MessageCategory $messageCategory): AdminMessageCategoryResource
    {
        $this->authorize('update', $messageCategory);

        $messageCategory->fill($request->validated());
        $messageCategory->save();

        return new AdminMessageCategoryResource($messageCategory->loadCount('messages'));
    }

    public function destroy(MessageCategory $messageCategory): JsonResponse
    {
        $this->authorize('delete', $messageCategory);

        $messageCategory->delete();

        return response()->json(null, 204);
    }
}
