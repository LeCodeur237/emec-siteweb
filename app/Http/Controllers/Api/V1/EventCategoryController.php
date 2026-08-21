<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\EventCategoryResource;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventCategoryController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = EventCategory::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return EventCategoryResource::collection($query->orderBy('name')->get());
    }

    public function show(string $slug): EventCategoryResource
    {
        $category = EventCategory::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return new EventCategoryResource($category);
    }
}
