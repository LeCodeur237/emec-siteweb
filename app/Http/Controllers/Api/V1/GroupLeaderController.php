<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\GroupLeaderResource;
use App\Models\GroupLeader;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupLeaderController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'group_id' => ['sometimes', 'integer', 'exists:groups,id'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = GroupLeader::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query
            ->when(isset($validated['group_id']), fn ($query) => $query->where('group_id', $validated['group_id']))
            ->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return GroupLeaderResource::collection($query->orderBy('name')->get());
    }

    public function show(int $id): GroupLeaderResource
    {
        $leader = GroupLeader::query()
            ->where('active', true)
            ->findOrFail($id);

        return new GroupLeaderResource($leader);
    }
}
