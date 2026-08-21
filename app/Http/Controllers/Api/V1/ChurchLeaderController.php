<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\ChurchLeaderResource;
use App\Models\ChurchLeader;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChurchLeaderController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'church_id' => ['sometimes', 'integer', 'exists:churches,id'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = ChurchLeader::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query
            ->when(isset($validated['church_id']), fn ($query) => $query->where('church_id', $validated['church_id']))
            ->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return ChurchLeaderResource::collection($query->orderBy('name')->get());
    }

    public function show(int $id): ChurchLeaderResource
    {
        $leader = ChurchLeader::query()
            ->where('active', true)
            ->findOrFail($id);

        return new ChurchLeaderResource($leader);
    }
}
