<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\AdministrativeLeaderResource;
use App\Models\AdministrativeLeader;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdministrativeLeaderController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = AdministrativeLeader::query()->with('media');

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return AdministrativeLeaderResource::collection($query->orderBy('name')->get());
    }

    public function show(int $id): AdministrativeLeaderResource
    {
        $leader = AdministrativeLeader::query()
            ->where('active', true)
            ->with('media')
            ->findOrFail($id);

        return new AdministrativeLeaderResource($leader);
    }
}
