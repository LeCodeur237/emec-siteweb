<?php

namespace App\Providers;

use App\Models\AdministrativeLeader;
use App\Models\Church;
use App\Models\ChurchLeader;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Group;
use App\Models\GroupLeader;
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
use App\Models\SocialActionStat;
use App\Models\SocialProject;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WeeklyProgram;
use App\Policies\AdministrativeLeaderPolicy;
use App\Policies\ChurchLeaderPolicy;
use App\Policies\ChurchPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\DonationCampaignPolicy;
use App\Policies\DonationMethodPolicy;
use App\Policies\DonationPolicy;
use App\Policies\EventCategoryPolicy;
use App\Policies\EventPolicy;
use App\Policies\GroupLeaderPolicy;
use App\Policies\GroupPolicy;
use App\Policies\ImpactStatPolicy;
use App\Policies\MediaPolicy;
use App\Policies\MessageCategoryPolicy;
use App\Policies\MessagePolicy;
use App\Policies\MessageSeriesPolicy;
use App\Policies\NewsletterSubscriberPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PreacherPolicy;
use App\Policies\RolePolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\SocialActionPolicy;
use App\Policies\SocialActionStatPolicy;
use App\Policies\SocialProjectPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\UserPolicy;
use App\Policies\WeeklyProgramPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Message::class => MessagePolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Preacher::class => PreacherPolicy::class,
        MessageCategory::class => MessageCategoryPolicy::class,
        MessageSeries::class => MessageSeriesPolicy::class,
        Media::class => MediaPolicy::class,
        Church::class => ChurchPolicy::class,
        ChurchLeader::class => ChurchLeaderPolicy::class,
        AdministrativeLeader::class => AdministrativeLeaderPolicy::class,
        Group::class => GroupPolicy::class,
        GroupLeader::class => GroupLeaderPolicy::class,
        EventCategory::class => EventCategoryPolicy::class,
        Event::class => EventPolicy::class,
        WeeklyProgram::class => WeeklyProgramPolicy::class,
        DonationCampaign::class => DonationCampaignPolicy::class,
        DonationMethod::class => DonationMethodPolicy::class,
        Donation::class => DonationPolicy::class,
        ContactMessage::class => ContactMessagePolicy::class,
        NewsletterSubscriber::class => NewsletterSubscriberPolicy::class,
        SiteSetting::class => SiteSettingPolicy::class,
        SocialProject::class => SocialProjectPolicy::class,
        SocialAction::class => SocialActionPolicy::class,
        SocialActionStat::class => SocialActionStatPolicy::class,
        Testimonial::class => TestimonialPolicy::class,
        ImpactStat::class => ImpactStatPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
