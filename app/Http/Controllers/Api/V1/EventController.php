<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\EventIndexRequest;
use App\Http\Resources\Api\V1\EventResource;
use App\Models\Event;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends ApiController
{
    public function index(EventIndexRequest $request): AnonymousResourceCollection
    {
        $query = Event::query()
            ->where('status', 'published')
            ->with('category');

        $query
            ->when($request->filled('event_category_id'), fn ($query) => $query->where('event_category_id', $request->integer('event_category_id')))
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->string('city')->toString()))
            ->when($request->filled('featured'), fn ($query) => $query->where('featured', $request->boolean('featured')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('start_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('start_at', '<=', $request->date('to')->toDateString()))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            });

        $sort = ApiQueryParameters::sort($request, ['start_at', 'title', 'created_at', 'city'], 'start_at');
        $direction = $request->filled('direction') ? ApiQueryParameters::direction($request) : 'asc';

        return EventResource::collection(
            $query->orderBy($sort, $direction)
                ->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): EventResource
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'media'])
            ->firstOrFail();

        return new EventResource($event);
    }
}
