<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\Admin\AuthenticatedUserResource;
use Illuminate\Http\Request;

class AdminProfileController extends ApiController
{
    public function __invoke(Request $request): AuthenticatedUserResource
    {
        return new AuthenticatedUserResource($request->user());
    }
}
