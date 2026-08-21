<?php

namespace App\Providers;

use App\Events\ContactMessageReceived;
use App\Events\NewsletterSubscriberCreated;
use App\Listeners\NotifyAdminsOfContactMessage;
use App\Listeners\NotifyAdminsOfNewsletterSubscription;
use App\Listeners\SendContactReceivedMail;
use App\Listeners\SendNewsletterSubscriptionMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ContactMessageReceived::class => [
            SendContactReceivedMail::class,
            NotifyAdminsOfContactMessage::class,
        ],
        NewsletterSubscriberCreated::class => [
            SendNewsletterSubscriptionMail::class,
            NotifyAdminsOfNewsletterSubscription::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
