<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\PreacherResource;
use App\Models\Preacher;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PreacherController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string', 'max:120'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = Preacher::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");
                });
            })
            ->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return PreacherResource::collection(
            $query->orderBy('name')->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(string $slug): PreacherResource
    {
        $preacher = Preacher::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->with('media')
            ->firstOrFail();

        return new PreacherResource($preacher);
    }
}
