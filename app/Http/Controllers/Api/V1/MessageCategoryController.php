<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\MessageCategoryResource;
use App\Models\MessageCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageCategoryController extends ApiController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'active' => ['sometimes', 'boolean'],
        ]);

        $query = MessageCategory::query();

        if (! array_key_exists('active', $validated)) {
            $query->where('active', true);
        }

        $query->when(array_key_exists('active', $validated), fn ($query) => $query->where('active', $request->boolean('active')));

        return MessageCategoryResource::collection($query->orderBy('name')->get());
    }

    public function show(string $slug): MessageCategoryResource
    {
        $category = MessageCategory::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        return new MessageCategoryResource($category);
    }
}
