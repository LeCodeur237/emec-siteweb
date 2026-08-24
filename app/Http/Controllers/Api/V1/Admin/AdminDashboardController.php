<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Church;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\Event;
use App\Models\Group;
use App\Models\ImpactStat;
use App\Models\Media;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\MessageSeries;
use App\Models\NewsletterSubscriber;
use App\Models\Permission;
use App\Models\Preacher;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WeeklyProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminDashboardController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->loadMissing('roles.permissions');
        $counts = [];

        if ($this->canAny($user, ['messages.view', 'messages.manage'])) {
            $counts['messages_count'] = Message::count();
            $counts['published_messages_count'] = Message::where('status', 'published')->count();
            $counts['draft_messages_count'] = Message::where('status', 'draft')->count();
            $counts['featured_messages_count'] = Message::where('featured', true)->count();
            $counts['preachings_count'] = Message::whereNotNull('preached_at')->count();
            $counts['preachers_count'] = Preacher::count();
            $counts['message_categories_count'] = MessageCategory::count();
            $counts['message_series_count'] = MessageSeries::count();
            $counts['daily_message_views'] = $this->dailyMessageViews();
            $counts['latest_messages'] = Message::query()
                ->with('preacher:id,name')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn (Message $message) => [
                    'id' => $message->id,
                    'title' => $message->title,
                    'status' => $message->status,
                    'views' => $message->views,
                    'preached_at' => $message->preached_at?->toDateString(),
                    'preacher_name' => $message->preacher?->name,
                ])
                ->values()
                ->all();
            $counts['dashboard_preachers'] = Preacher::query()
                ->withCount('messages')
                ->orderByDesc('messages_count')
                ->orderBy('name')
                ->limit(6)
                ->get()
                ->map(fn (Preacher $preacher) => [
                    'id' => $preacher->id,
                    'name' => $preacher->name,
                    'role' => $preacher->role,
                    'active' => $preacher->active,
                    'messages_count' => $preacher->messages_count,
                ])
                ->values()
                ->all();
        }

        if ($this->canAny($user, ['events.view', 'events.manage'])) {
            $counts['events_count'] = Event::count();
            $counts['published_events_count'] = Event::where('status', 'published')->count();
            $counts['upcoming_events_count'] = Event::where('start_at', '>=', now())->count();
            $counts['weekly_programs_count'] = WeeklyProgram::count();
            $counts['active_weekly_programs_count'] = WeeklyProgram::where('active', true)->count();
        }

        if ($this->canAny($user, ['churches.view', 'churches.manage'])) {
            $counts['churches_count'] = Church::count();
            $counts['active_churches_count'] = Church::where('active', true)->count();
            $counts['published_churches_count'] = Church::where('status', 'published')->count();
        }

        if ($this->canAny($user, ['groups.view', 'groups.manage'])) {
            $counts['groups_count'] = Group::count();
            $counts['active_groups_count'] = Group::where('active', true)->count();
        }

        if ($this->canAny($user, ['dosc.projects.view', 'dosc.manage'])) {
            $counts['social_projects_count'] = SocialProject::count();
            $counts['active_social_projects_count'] = SocialProject::where('status', 'active')->count();
            $counts['featured_social_projects_count'] = SocialProject::where('featured', true)->count();
            $counts['social_projects_goal_amount'] = (float) SocialProject::sum('goal_amount');
            $counts['social_projects_raised_amount'] = (float) SocialProject::sum('raised_amount');
        }

        if ($this->canAny($user, ['dosc.actions.view', 'dosc.manage'])) {
            $counts['social_actions_count'] = SocialAction::count();
            $counts['published_social_actions_count'] = SocialAction::where('status', 'published')->count();
            $counts['social_actions_beneficiaries_count'] = (int) SocialAction::sum('beneficiaries_count');
            $counts['impact_stats_count'] = ImpactStat::count();
            $counts['active_impact_stats_count'] = ImpactStat::where('active', true)->count();
            $counts['testimonials_count'] = Testimonial::count();
            $counts['published_testimonials_count'] = Testimonial::where('published', true)->count();
        }

        if ($this->canAny($user, ['donations.view', 'donations.manage'])) {
            $counts['donation_campaigns_count'] = DonationCampaign::count();
            $counts['active_donation_campaigns_count'] = DonationCampaign::where('active', true)->count();
            $counts['donation_methods_count'] = DonationMethod::count();
            $counts['active_donation_methods_count'] = DonationMethod::where('active', true)->count();
            $counts['donations_count'] = Donation::count();
            $counts['paid_donations_count'] = Donation::where('status', 'paid')->count();
            $counts['pending_donations_count'] = Donation::where('status', 'pending')->count();
            $counts['paid_donations_amount'] = (float) Donation::where('status', 'paid')->sum('amount');
            $counts['daily_paid_donations'] = $this->dailyPaidDonations();
        }

        if ($user->hasPermission('communication.manage')) {
            $counts['contact_messages_count'] = ContactMessage::count();
            $counts['new_contact_messages_count'] = ContactMessage::where('status', 'new')->count();
            $counts['answered_contact_messages_count'] = ContactMessage::where('status', 'answered')->count();
            $counts['newsletter_subscribers_count'] = NewsletterSubscriber::count();
            $counts['active_newsletter_subscribers_count'] = NewsletterSubscriber::where('status', 'subscribed')->count();
        }

        if ($this->canAny($user, ['media.view', 'media.manage'])) {
            $counts['media_count'] = Media::count();
            $counts['image_media_count'] = Media::where('file_type', 'image')->count();
            $counts['document_media_count'] = Media::where('file_type', 'document')->count();
        }

        if ($user->hasPermission('settings.manage')) {
            $counts['site_settings_count'] = SiteSetting::count();
        }

        if ($this->canAny($user, ['users.view', 'users.manage'])) {
            $counts['users_count'] = User::count();
            $counts['active_users_count'] = User::where('status', 'active')->count();
        }

        if ($user->hasPermission('roles.manage')) {
            $counts['roles_count'] = Role::count();
            $counts['permissions_count'] = Permission::count();
        }

        if ($user->hasPermission('notifications.view')) {
            $counts['notifications_count'] = $user->notifications()->count();
            $counts['unread_notifications_count'] = $user->unreadNotifications()->count();
        }

        if ($this->canAny($user, [
            'messages.view',
            'messages.manage',
            'events.view',
            'events.manage',
            'dosc.projects.view',
            'dosc.actions.view',
            'dosc.manage',
        ])) {
            $counts['main_site_publications_count'] =
                Message::where('status', 'published')->count()
                + Event::where('status', 'published')->count()
                + SocialProject::where('status', 'active')->count()
                + SocialAction::where('status', 'published')->count()
                + Testimonial::where('published', true)->count();
        }

        return response()->json(['data' => $counts]);
    }

    private function canAny(User $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, array{date: string, label: string, value: int}>
     */
    private function dailyMessageViews(): array
    {
        $days = $this->lastSevenDays();
        $totals = Message::query()
            ->selectRaw('DATE(COALESCE(preached_at, created_at)) as day, SUM(views) as total')
            ->where('status', 'published')
            ->whereRaw('DATE(COALESCE(preached_at, created_at)) >= ?', [$days->first()['date']])
            ->groupBy('day')
            ->pluck('total', 'day');

        return $days
            ->map(fn (array $day) => [
                ...$day,
                'value' => (int) ($totals[$day['date']] ?? 0),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{date: string, label: string, value: float}>
     */
    private function dailyPaidDonations(): array
    {
        $days = $this->lastSevenDays();
        $totals = Donation::query()
            ->selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', '>=', $days->first()['date'])
            ->groupBy('day')
            ->pluck('total', 'day');

        return $days
            ->map(fn (array $day) => [
                ...$day,
                'value' => (float) ($totals[$day['date']] ?? 0),
            ])
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, array{date: string, label: string}>
     */
    private function lastSevenDays(): Collection
    {
        return collect(range(6, 0))->map(function (int $offset) {
            $date = now()->subDays($offset);

            return [
                'date' => $date->toDateString(),
                'label' => $date->format('d/m'),
            ];
        });
    }
}
