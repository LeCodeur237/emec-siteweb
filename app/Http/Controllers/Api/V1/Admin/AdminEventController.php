<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminEmecIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreEventRequest;
use App\Http\Requests\Api\V1\Admin\UpdateEventRequest;
use App\Http\Resources\Api\V1\Admin\AdminEventResource;
use App\Models\Event;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminEventController extends ApiController
{
    public function index(AdminEmecIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Event::class);

        $query = Event::query()->with('category')
            ->when($request->filled('event_category_id'), fn ($query) => $query->where('event_category_id', $request->integer('event_category_id')))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('city', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('location', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->string('city')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')))
            ->when($request->filled('from'), fn ($query) => $query->where('start_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->where('start_at', '<=', $request->date('to')));

        $sort = ApiQueryParameters::sort($request, ['title', 'start_at', 'created_at', 'updated_at'], 'start_at');

        return AdminEventResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Event $event): AdminEventResource
    {
        $this->authorize('view', $event);

        return new AdminEventResource($event->load(['category', 'media']));
    }

    public function store(StoreEventRequest $request): AdminEventResource
    {
        $this->authorize('create', Event::class);

        return new AdminEventResource(Event::create($request->validated())->load(['category', 'media']));
    }

    public function update(UpdateEventRequest $request, Event $event): AdminEventResource
    {
        $this->authorize('update', $event);

        $event->fill($request->validated())->save();

        return new AdminEventResource($event->load(['category', 'media']));
    }

    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $event->media()->detach();
        $event->delete();

        return response()->json(null, 204);
    }
}
