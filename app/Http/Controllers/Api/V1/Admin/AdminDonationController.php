<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Admin\AdminDonationIndexRequest;
use App\Http\Requests\Api\V1\Admin\StoreDonationRequest;
use App\Http\Requests\Api\V1\Admin\UpdateDonationRequest;
use App\Http\Resources\Api\V1\Admin\AdminDonationResource;
use App\Models\Donation;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminDonationController extends ApiController
{
    public function index(AdminDonationIndexRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Donation::class);

        $query = Donation::query()->with(['campaign', 'method'])
            ->when($request->filled('donation_campaign_id'), fn ($query) => $query->where('donation_campaign_id', $request->integer('donation_campaign_id')))
            ->when($request->filled('donation_method_id'), fn ($query) => $query->where('donation_method_id', $request->integer('donation_method_id')))
            ->when($request->filled('social_project_id'), fn ($query) => $query->whereHas('campaign', fn ($query) => $query->where('social_project_id', $request->integer('social_project_id'))))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('anonymous'), fn ($query) => $query->where('anonymous', $request->boolean('anonymous')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')->toDateString()))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')->toDateString()))
            ->when($request->filled('paid_from'), fn ($query) => $query->whereDate('paid_at', '>=', $request->date('paid_from')->toDateString()))
            ->when($request->filled('paid_to'), fn ($query) => $query->whereDate('paid_at', '<=', $request->date('paid_to')->toDateString()))
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($query) => $query
                ->where('donor_name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('donor_email', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('donor_phone', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('transaction_reference', 'like', '%'.$request->string('search')->toString().'%')));

        $sort = ApiQueryParameters::sort($request, ['amount', 'paid_at', 'created_at', 'updated_at'], 'created_at');

        return AdminDonationResource::collection(
            $query->orderBy($sort, ApiQueryParameters::direction($request))->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function show(Donation $donation): AdminDonationResource
    {
        $this->authorize('view', $donation);

        return new AdminDonationResource($donation->load(['campaign', 'method']));
    }

    public function store(StoreDonationRequest $request): AdminDonationResource
    {
        $this->authorize('create', Donation::class);

        return new AdminDonationResource(Donation::create($request->validated())->load(['campaign', 'method']));
    }

    public function update(UpdateDonationRequest $request, Donation $donation): AdminDonationResource
    {
        $this->authorize('update', $donation);

        $donation->fill($request->validated())->save();

        return new AdminDonationResource($donation->load(['campaign', 'method']));
    }

    public function destroy(Donation $donation): JsonResponse
    {
        $this->authorize('delete', $donation);

        $donation->delete();

        return response()->json(null, 204);
    }
}
