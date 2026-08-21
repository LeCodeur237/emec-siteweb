<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Church;
use App\Models\Event;
use App\Models\Group;
use App\Models\Message;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('roles.permissions');
        $counts = [];

        if ($user->hasPermission('messages.view') || $user->hasPermission('messages.manage')) {
            $counts['messages_count'] = Message::count();
        }

        if ($user->hasPermission('events.view') || $user->hasPermission('events.manage')) {
            $counts['events_count'] = Event::count();
        }

        if ($user->hasPermission('churches.manage')) {
            $counts['churches_count'] = Church::count();
        }

        if ($user->hasPermission('groups.manage')) {
            $counts['groups_count'] = Group::count();
        }

        if ($user->hasPermission('dosc.projects.view') || $user->hasPermission('dosc.manage')) {
            $counts['social_projects_count'] = SocialProject::count();
        }

        if ($user->hasPermission('dosc.actions.view') || $user->hasPermission('dosc.manage')) {
            $counts['social_actions_count'] = SocialAction::count();
        }

        if ($user->hasPermission('users.view') || $user->hasPermission('users.manage')) {
            $counts['users_count'] = User::count();
        }

        return response()->json(['data' => $counts]);
    }
}
