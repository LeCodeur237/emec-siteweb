<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\GroupResource;
use App\Models\Group;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:120'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = Group::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return GroupResource::collection(
            $query->orderBy('name')->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): GroupResource
    {
        $group = Group::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->with([
                'leaders' => fn ($query) => $query->where('active', true),
                'media',
            ])
            ->firstOrFail();

        return new GroupResource($group);
    }
}
