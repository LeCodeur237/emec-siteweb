<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDonationIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreDonationMethodRequest;
use App\Http\Requests\Api\V1\Admin\UpdateDonationMethodRequest;
use App\Http\Resources\Api\V1\Admin\AdminDonationMethodResource;
use App\Models\DonationMethod;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminDonationMethodController extends ApiController
{
    public function index(AdminDonationIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', DonationMethod::class);

        $query = DonationMethod::query()->withCount('donations')
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->when($request->filled('provider'), fn ($query) => $query->where('provider', $request->string('provider')->toString()))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('provider', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('account_name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('account_number', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['name', 'type', 'provider', 'created_at', 'updated_at'], 'name');

        return AdminDonationMethodResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(DonationMethod $donationMethod): AdminDonationMethodResource
    {
        $this->authorize('view', $donationMethod);

        return new AdminDonationMethodResource($donationMethod->loadCount('donations'));
    }

    public function store(StoreDonationMethodRequest $request): AdminDonationMethodResource
    {
        $this->authorize('create', DonationMethod::class);

        return new AdminDonationMethodResource(DonationMethod::create($request->validated())->loadCount('donations'));
    }

    public function update(UpdateDonationMethodRequest $request, DonationMethod $donationMethod): AdminDonationMethodResource
    {
        $this->authorize('update', $donationMethod);

        $donationMethod->fill($request->validated())->save();

        return new AdminDonationMethodResource($donationMethod->loadCount('donations'));
    }

    public function destroy(DonationMethod $donationMethod): JsonResponse
    {
        $this->authorize('delete', $donationMethod);

        $donationMethod->delete();

        return response()->json(null, 204);
    }
}
