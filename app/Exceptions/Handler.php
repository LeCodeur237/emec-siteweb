<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($this->isApiRequest($request)) {
            return $this->renderApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    private function isApiRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }

    private function renderApiException(Request $request, Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        if ($e instanceof HttpExceptionInterface) {
            return response()->json([
                'message' => $e->getMessage() !== '' ? $e->getMessage() : 'HTTP error.',
            ], $e->getStatusCode(), $e->getHeaders());
        }

        if (config('app.debug')) {
            return response()->json([
                'message' => $e->getMessage() !== '' ? $e->getMessage() : 'Server error.',
                'exception' => class_basename($e),
            ], 500);
        }

        return response()->json(['message' => 'Server error.'], 500);
    }
}
