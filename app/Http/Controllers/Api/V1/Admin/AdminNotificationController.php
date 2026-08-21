<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\Admin\AdminNotificationResource;
use App\Support\Api\ApiQueryParameters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminNotificationController extends ApiController
{
    public function index(Request $request)
    {
        $this->authorizeNotifications($request);

        return AdminNotificationResource::collection(
            $request->user()->notifications()->latest()->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function unread(Request $request)
    {
        $this->authorizeNotifications($request);

        return AdminNotificationResource::collection(
            $request->user()->unreadNotifications()->latest()->paginate(ApiQueryParameters::perPage($request))
        );
    }

    public function markRead(Request $request, string $notification): AdminNotificationResource
    {
        $this->authorizeNotifications($request);

        $notification = $this->findOwnedNotification($request, $notification);
        $notification->markAsRead();

        return new AdminNotificationResource($notification->refresh());
    }

    public function destroy(Request $request, string $notification): JsonResponse
    {
        $this->authorizeNotifications($request);

        $this->findOwnedNotification($request, $notification)->delete();

        return response()->json(null, 204);
    }

    private function authorizeNotifications(Request $request): void
    {
        abort_unless($request->user()?->hasPermission('notifications.view'), 403);
    }

    private function findOwnedNotification(Request $request, string $id): DatabaseNotification
    {
        $notification = $request->user()->notifications()->whereKey($id)->first();

        if (! $notification) {
            throw new NotFoundHttpException('Notification not found.');
        }

        return $notification;
    }
}
