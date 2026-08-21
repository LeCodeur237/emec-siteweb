<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\MessageSeriesResource;
use App\Models\MessageSeries;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageSeriesController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = MessageSeries::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return MessageSeriesResource::collection($query->orderBy('name')->get());
    }

    public function show(string $slug): MessageSeriesResource
    {
        $series = MessageSeries::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->with('media')
            ->firstOrFail();

        return new MessageSeriesResource($series);
    }
}
