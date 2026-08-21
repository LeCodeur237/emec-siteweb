<?php

use App\Http\Controllers\Api\V1\Admin\AdminAdministrativeLeaderController;
use App\Http\Controllers\Api\V1\Admin\AdminChurchController;
use App\Http\Controllers\Api\V1\Admin\AdminChurchLeaderController;
use App\Http\Controllers\Api\V1\Admin\AdminContactMessageController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminDonationCampaignController;
use App\Http\Controllers\Api\V1\Admin\AdminDonationController;
use App\Http\Controllers\Api\V1\Admin\AdminDonationMethodController;
use App\Http\Controllers\Api\V1\Admin\AdminEventCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminEventController;
use App\Http\Controllers\Api\V1\Admin\AdminGroupController;
use App\Http\Controllers\Api\V1\Admin\AdminGroupLeaderController;
use App\Http\Controllers\Api\V1\Admin\AdminImpactStatController;
use App\Http\Controllers\Api\V1\Admin\AdminMediaController;
use App\Http\Controllers\Api\V1\Admin\AdminMessageCategoryController;
use App\Http\Controllers\Api\V1\Admin\AdminMessageController;
use App\Http\Controllers\Api\V1\Admin\AdminMessageSeriesController;
use App\Http\Controllers\Api\V1\Admin\AdminNewsletterSubscriberController;
use App\Http\Controllers\Api\V1\Admin\AdminNotificationController;
use App\Http\Controllers\Api\V1\Admin\AdminPermissionController;
use App\Http\Controllers\Api\V1\Admin\AdminPreacherController;
use App\Http\Controllers\Api\V1\Admin\AdminProfileController;
use App\Http\Controllers\Api\V1\Admin\AdminRoleController;
use App\Http\Controllers\Api\V1\Admin\AdminSiteSettingController;
use App\Http\Controllers\Api\V1\Admin\AdminSocialActionController;
use App\Http\Controllers\Api\V1\Admin\AdminSocialActionStatController;
use App\Http\Controllers\Api\V1\Admin\AdminSocialProjectController;
use App\Http\Controllers\Api\V1\Admin\AdminTestimonialController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\Admin\AdminWeeklyProgramController;
use App\Http\Controllers\Api\V1\AdministrativeLeaderController;
use App\Http\Controllers\Api\V1\ApiInfoController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\ChurchController;
use App\Http\Controllers\Api\V1\ChurchLeaderController;
use App\Http\Controllers\Api\V1\ContactMessageController;
use App\Http\Controllers\Api\V1\Dosc\DonationCampaignController;
use App\Http\Controllers\Api\V1\Dosc\DonationMethodController;
use App\Http\Controllers\Api\V1\Dosc\ImpactStatController;
use App\Http\Controllers\Api\V1\Dosc\SocialActionController;
use App\Http\Controllers\Api\V1\Dosc\SocialProjectController;
use App\Http\Controllers\Api\V1\Dosc\TestimonialController;
use App\Http\Controllers\Api\V1\EventCategoryController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\GroupLeaderController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\MessageCategoryController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\MessageSeriesController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\PreacherController;
use App\Http\Controllers\Api\V1\WeeklyProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/', ApiInfoController::class)->name('api.v1.info');
    Route::get('/health', HealthController::class)->name('api.v1.health');
    Route::post('/contact', [ContactMessageController::class, 'store'])->middleware('throttle:contact')->name('api.v1.contact.store');
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:newsletter')->name('api.v1.newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->middleware('throttle:newsletter')->name('api.v1.newsletter.unsubscribe');

    Route::prefix('auth')->name('api.v1.auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    Route::prefix('admin')->middleware('auth:sanctum')->name('api.v1.admin.')->group(function () {
        Route::get('/me', AdminProfileController::class)->name('me');
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::apiResource('messages', AdminMessageController::class);
        Route::apiResource('preachers', AdminPreacherController::class);
        Route::apiResource('message-categories', AdminMessageCategoryController::class)
            ->parameters(['message-categories' => 'messageCategory']);
        Route::apiResource('message-series', AdminMessageSeriesController::class)
            ->parameters(['message-series' => 'messageSeries']);

        Route::apiResource('churches', AdminChurchController::class);
        Route::apiResource('church-leaders', AdminChurchLeaderController::class)
            ->parameters(['church-leaders' => 'churchLeader']);
        Route::apiResource('administrative-leaders', AdminAdministrativeLeaderController::class)
            ->parameters(['administrative-leaders' => 'administrativeLeader']);
        Route::apiResource('groups', AdminGroupController::class);
        Route::apiResource('group-leaders', AdminGroupLeaderController::class)
            ->parameters(['group-leaders' => 'groupLeader']);
        Route::apiResource('event-categories', AdminEventCategoryController::class)
            ->parameters(['event-categories' => 'eventCategory']);
        Route::apiResource('events', AdminEventController::class);
        Route::apiResource('weekly-programs', AdminWeeklyProgramController::class)
            ->parameters(['weekly-programs' => 'weeklyProgram']);

        Route::apiResource('donation-campaigns', AdminDonationCampaignController::class)
            ->parameters(['donation-campaigns' => 'donationCampaign']);
        Route::apiResource('donation-methods', AdminDonationMethodController::class)
            ->parameters(['donation-methods' => 'donationMethod']);
        Route::apiResource('donations', AdminDonationController::class);
        Route::apiResource('contact-messages', AdminContactMessageController::class)
            ->parameters(['contact-messages' => 'contactMessage']);
        Route::apiResource('newsletter-subscribers', AdminNewsletterSubscriberController::class)
            ->parameters(['newsletter-subscribers' => 'newsletterSubscriber']);
        Route::apiResource('site-settings', AdminSiteSettingController::class)
            ->parameters(['site-settings' => 'siteSetting']);
        Route::apiResource('users', AdminUserController::class);
        Route::apiResource('roles', AdminRoleController::class);
        Route::apiResource('permissions', AdminPermissionController::class);
        Route::get('notifications/unread', [AdminNotificationController::class, 'unread'])->name('notifications.unread');
        Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::patch('notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
        Route::delete('notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::post('media', [AdminMediaController::class, 'store'])->middleware('throttle:media-upload')->name('media.store');
        Route::apiResource('media', AdminMediaController::class)
            ->except(['store'])
            ->parameters(['media' => 'media']);

        Route::prefix('dosc')->name('dosc.')->group(function () {
            Route::apiResource('projects', AdminSocialProjectController::class)
                ->parameters(['projects' => 'project']);
            Route::apiResource('actions', AdminSocialActionController::class)
                ->parameters(['actions' => 'action']);
            Route::apiResource('action-stats', AdminSocialActionStatController::class)
                ->parameters(['action-stats' => 'actionStat']);
            Route::apiResource('testimonials', AdminTestimonialController::class);
            Route::apiResource('impact-stats', AdminImpactStatController::class)
                ->parameters(['impact-stats' => 'impactStat']);
        });
    });

    Route::get('/churches', [ChurchController::class, 'index'])->name('api.v1.churches.index');
    Route::get('/churches/{slug}', [ChurchController::class, 'show'])->name('api.v1.churches.show');

    Route::get('/church-leaders', [ChurchLeaderController::class, 'index'])->name('api.v1.church-leaders.index');
    Route::get('/church-leaders/{id}', [ChurchLeaderController::class, 'show'])->whereNumber('id')->name('api.v1.church-leaders.show');

    Route::get('/administrative-leaders', [AdministrativeLeaderController::class, 'index'])->name('api.v1.administrative-leaders.index');
    Route::get('/administrative-leaders/{id}', [AdministrativeLeaderController::class, 'show'])->whereNumber('id')->name('api.v1.administrative-leaders.show');

    Route::get('/groups', [GroupController::class, 'index'])->name('api.v1.groups.index');
    Route::get('/groups/{slug}', [GroupController::class, 'show'])->name('api.v1.groups.show');

    Route::get('/group-leaders', [GroupLeaderController::class, 'index'])->name('api.v1.group-leaders.index');
    Route::get('/group-leaders/{id}', [GroupLeaderController::class, 'show'])->whereNumber('id')->name('api.v1.group-leaders.show');

    Route::get('/event-categories', [EventCategoryController::class, 'index'])->name('api.v1.event-categories.index');
    Route::get('/event-categories/{slug}', [EventCategoryController::class, 'show'])->name('api.v1.event-categories.show');

    Route::get('/events', [EventController::class, 'index'])->name('api.v1.events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('api.v1.events.show');

    Route::get('/weekly-programs', [WeeklyProgramController::class, 'index'])->name('api.v1.weekly-programs.index');
    Route::get('/weekly-programs/{id}', [WeeklyProgramController::class, 'show'])->whereNumber('id')->name('api.v1.weekly-programs.show');

    Route::get('/preachers', [PreacherController::class, 'index'])->name('api.v1.preachers.index');
    Route::get('/preachers/{slug}', [PreacherController::class, 'show'])->name('api.v1.preachers.show');

    Route::get('/message-categories', [MessageCategoryController::class, 'index'])->name('api.v1.message-categories.index');
    Route::get('/message-categories/{slug}', [MessageCategoryController::class, 'show'])->name('api.v1.message-categories.show');

    Route::get('/message-series', [MessageSeriesController::class, 'index'])->name('api.v1.message-series.index');
    Route::get('/message-series/{slug}', [MessageSeriesController::class, 'show'])->name('api.v1.message-series.show');

    Route::get('/messages', [MessageController::class, 'index'])->name('api.v1.messages.index');
    Route::get('/messages/{slug}', [MessageController::class, 'show'])->name('api.v1.messages.show');

    Route::prefix('dosc')->name('api.v1.dosc.')->group(function () {
        Route::get('/projects', [SocialProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [SocialProjectController::class, 'show'])->name('projects.show');

        Route::get('/actions', [SocialActionController::class, 'index'])->name('actions.index');
        Route::get('/actions/{slug}', [SocialActionController::class, 'show'])->name('actions.show');

        Route::get('/impact-stats', [ImpactStatController::class, 'index'])->name('impact-stats.index');
        Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');

        Route::get('/donation-campaigns', [DonationCampaignController::class, 'index'])->name('donation-campaigns.index');
        Route::get('/donation-campaigns/{id}', [DonationCampaignController::class, 'show'])->whereNumber('id')->name('donation-campaigns.show');

        Route::get('/donation-methods', [DonationMethodController::class, 'index'])->name('donation-methods.index');
    });
});
