<?php

namespace Tests\Feature\Api;

use App\Events\ContactMessageReceived;
use App\Events\NewsletterSubscriberCreated;
use App\Events\NewsletterSubscriberUnsubscribed;
use App\Listeners\NotifyAdminsOfContactMessage;
use App\Listeners\NotifyAdminsOfNewsletterSubscription;
use App\Listeners\SendContactReceivedMail;
use App\Listeners\SendNewsletterSubscriptionMail;
use App\Mail\ContactReceivedMail;
use App\Mail\NewsletterSubscriptionConfirmedMail;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use App\Notifications\NewsletterSubscriberNotification;
use App\Services\Notifications\AdminNotificationRecipientService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationsEventsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_contact_creates_message_and_dispatches_event_without_exposing_email(): void
    {
        Event::fake([ContactMessageReceived::class]);

        $this->postJson('/api/v1/contact', [
            'name' => '<b>Visiteur</b>',
            'email' => 'VISITEUR@example.test',
            'phone' => '699000000',
            'subject' => '<script>Info</script>',
            'message' => '<p>Bonjour EMEC.</p>',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'new')
            ->assertJsonMissing(['email' => 'visiteur@example.test']);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Visiteur',
            'email' => 'visiteur@example.test',
            'subject' => 'Info',
            'message' => 'Bonjour EMEC.',
            'status' => 'new',
        ]);

        Event::assertDispatched(ContactMessageReceived::class);
    }

    public function test_public_contact_validation_rejects_spam_and_invalid_content(): void
    {
        $this->postJson('/api/v1/contact', [
            'name' => '',
            'email' => 'bad-email',
            'message' => '',
            'website' => 'spam.example',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message', 'website']);
    }

    public function test_newsletter_subscribe_is_idempotent_and_unsubscribe_requires_token(): void
    {
        Event::fake([
            NewsletterSubscriberCreated::class,
            NewsletterSubscriberUnsubscribed::class,
        ]);

        $this->postJson('/api/v1/newsletter/subscribe', [
            'name' => 'Abonne',
            'email' => 'ABONNE@example.test',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'subscribed')
            ->assertJsonMissing(['email' => 'abonne@example.test']);

        $this->postJson('/api/v1/newsletter/subscribe', [
            'name' => 'Abonne',
            'email' => 'abonne@example.test',
        ])->assertOk();

        Event::assertDispatchedTimes(NewsletterSubscriberCreated::class, 1);

        $subscriber = NewsletterSubscriber::where('email', 'abonne@example.test')->firstOrFail();
        $this->assertNotNull($subscriber->unsubscribe_token);

        $this->postJson('/api/v1/newsletter/unsubscribe', [
            'email' => 'abonne@example.test',
            'unsubscribe_token' => str_repeat('x', 64),
        ])->assertNotFound();

        $this->postJson('/api/v1/newsletter/unsubscribe', [
            'email' => 'abonne@example.test',
            'unsubscribe_token' => $subscriber->unsubscribe_token,
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'unsubscribed');

        Event::assertDispatched(NewsletterSubscriberUnsubscribed::class);
    }

    public function test_listeners_are_queued_and_send_mail_or_admin_notifications(): void
    {
        Mail::fake();
        Notification::fake();

        $admin = $this->userWithRole('admin');
        $contact = ContactMessage::create([
            'name' => 'Contact',
            'email' => 'contact@example.test',
            'message' => 'Message.',
        ]);
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Abonne',
            'email' => 'newsletter@example.test',
            'subscribed_at' => now(),
            'unsubscribe_token' => str_repeat('a', 64),
        ]);

        $contactMailListener = new SendContactReceivedMail;
        $newsletterMailListener = new SendNewsletterSubscriptionMail;
        $contactNotificationListener = new NotifyAdminsOfContactMessage(new AdminNotificationRecipientService);
        $newsletterNotificationListener = new NotifyAdminsOfNewsletterSubscription(new AdminNotificationRecipientService);

        $this->assertInstanceOf(ShouldQueue::class, $contactMailListener);
        $this->assertInstanceOf(ShouldQueue::class, $newsletterMailListener);
        $this->assertInstanceOf(ShouldQueue::class, $contactNotificationListener);
        $this->assertInstanceOf(ShouldQueue::class, $newsletterNotificationListener);

        $contactMailListener->handle(new ContactMessageReceived($contact));
        $newsletterMailListener->handle(new NewsletterSubscriberCreated($subscriber));
        $contactNotificationListener->handle(new ContactMessageReceived($contact));
        $newsletterNotificationListener->handle(new NewsletterSubscriberCreated($subscriber));

        Mail::assertQueued(ContactReceivedMail::class, fn ($mail) => $mail->hasTo('contact@example.test'));
        Mail::assertQueued(NewsletterSubscriptionConfirmedMail::class, fn ($mail) => $mail->hasTo('newsletter@example.test'));
        Notification::assertSentTo($admin, ContactMessageNotification::class);
        Notification::assertSentTo($admin, NewsletterSubscriberNotification::class);
    }

    public function test_admin_notifications_api_is_protected_and_idempotently_marks_read(): void
    {
        $admin = $this->userWithRole('admin');
        $contact = ContactMessage::create([
            'name' => 'Contact',
            'email' => 'contact@example.test',
            'message' => 'Message.',
        ]);

        $admin->notifyNow(new ContactMessageNotification($contact));
        $notification = $admin->notifications()->firstOrFail();

        $this->getJson('/api/v1/admin/notifications')->assertUnauthorized();

        Sanctum::actingAs($this->userWithRole('messages_editor'));
        $this->getJson('/api/v1/admin/notifications')->assertForbidden();

        Sanctum::actingAs($admin);
        $this->getJson('/api/v1/admin/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.data.event', 'contact_message_received');

        $this->getJson('/api/v1/admin/notifications/unread')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->patchJson("/api/v1/admin/notifications/{$notification->id}/read")->assertOk();
        $this->assertNotNull($admin->notifications()->whereKey($notification->id)->firstOrFail()->read_at);

        $this->patchJson("/api/v1/admin/notifications/{$notification->id}/read")->assertOk();
        $this->getJson('/api/v1/admin/notifications/unread')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->deleteJson("/api/v1/admin/notifications/{$notification->id}")->assertNoContent();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
