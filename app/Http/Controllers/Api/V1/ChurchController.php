<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ChurchIndexRequest;
use App\Http\Resources\Api\V1\ChurchResource;
use App\Models\Church;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChurchController extends ApiController
{
    public function index(ChurchIndexRequest $request): AnonymousResourceCollection
    {
        $query = Church::query();

        if (! $request->has('active')) {
            $query->where('active', true);
        }

        if (! $request->filled('status')) {
            $query->where('status', 'published');
        }

        $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('region', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->string('city')->toString()))
            ->when($request->filled('region'), fn ($query) => $query->where('region', $request->string('region')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->has('active'), fn ($query) => $query->where('active', $request->boolean('active')));

        $sort = ApiQueryParameters::sort($request, ['name', 'city', 'region', 'created_at'], 'name');
        $direction = ApiQueryParameters::direction($request);

        return ChurchResource::collection(
            $query->orderBy($sort, $direction)
                ->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): ChurchResource
    {
        $church = Church::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->where('status', 'published')
            ->with([
                'leaders' => fn ($query) => $query->where('active', true),
                'media',
            ])
            ->firstOrFail();

        return new ChurchResource($church);
    }
}
