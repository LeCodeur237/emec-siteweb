<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Admin\AuthenticatedUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->with('roles.permissions')
            ->where('email', $request->string('email')->lower()->toString())
            ->first();

        if (! $user || ! Hash::check($request->string('password')->toString(), $user->password) || ! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are invalid.'],
            ]);
        }

        $token = $user->createToken('admin-api')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated.',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => new AuthenticatedUserResource($user),
        ]);
    }

    public function me(Request $request): AuthenticatedUserResource
    {
        return new AuthenticatedUserResource($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json(['message' => 'Logged out.']);
    }
}
