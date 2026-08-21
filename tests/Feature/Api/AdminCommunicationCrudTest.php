<?php

namespace Tests\Feature\Api;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminCommunicationCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_communication_admin_routes_require_authentication(): void
    {
        $message = ContactMessage::create([
            'name' => 'Visiteur',
            'email' => 'visiteur@example.test',
            'message' => 'Bonjour EMEC.',
        ]);
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Abonne',
            'email' => 'abonne@example.test',
            'subscribed_at' => now(),
        ]);

        foreach ([
            ['getJson', '/api/v1/admin/contact-messages'],
            ['getJson', "/api/v1/admin/contact-messages/{$message->id}"],
            ['postJson', '/api/v1/admin/contact-messages'],
            ['patchJson', "/api/v1/admin/contact-messages/{$message->id}"],
            ['deleteJson', "/api/v1/admin/contact-messages/{$message->id}"],
            ['getJson', '/api/v1/admin/newsletter-subscribers'],
            ['getJson', "/api/v1/admin/newsletter-subscribers/{$subscriber->id}"],
            ['postJson', '/api/v1/admin/newsletter-subscribers'],
            ['patchJson', "/api/v1/admin/newsletter-subscribers/{$subscriber->id}"],
            ['deleteJson', "/api/v1/admin/newsletter-subscribers/{$subscriber->id}"],
        ] as [$method, $uri]) {
            $this->{$method}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_communication_permission_is_forbidden(): void
    {
        $message = ContactMessage::create([
            'name' => 'Visiteur',
            'email' => 'visiteur@example.test',
            'message' => 'Bonjour EMEC.',
        ]);
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Abonne',
            'email' => 'abonne@example.test',
            'subscribed_at' => now(),
        ]);

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/contact-messages')->assertForbidden();
        $this->postJson('/api/v1/admin/contact-messages', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/contact-messages/{$message->id}", ['status' => 'read'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/contact-messages/{$message->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/newsletter-subscribers')->assertForbidden();
        $this->postJson('/api/v1/admin/newsletter-subscribers', ['email' => 'no@example.test'])->assertForbidden();
        $this->patchJson("/api/v1/admin/newsletter-subscribers/{$subscriber->id}", ['status' => 'unsubscribed'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/newsletter-subscribers/{$subscriber->id}")->assertForbidden();
    }

    public function test_admin_can_crud_contact_messages(): void
    {
        ContactMessage::create([
            'name' => 'Alpha Visiteur',
            'email' => 'alpha@example.test',
            'subject' => 'Priere',
            'message' => 'Besoin de priere.',
            'status' => 'new',
        ]);
        ContactMessage::create([
            'name' => 'Archive',
            'email' => 'archive@example.test',
            'message' => 'Archive',
            'status' => 'archived',
        ]);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/contact-messages?search=Alpha&status=new&sort=name&direction=asc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'alpha@example.test');

        $createdId = $this->postJson('/api/v1/admin/contact-messages', [
            'name' => 'Nouveau contact',
            'email' => 'contact@example.test',
            'phone' => '699000000',
            'subject' => 'Information',
            'message' => 'Je souhaite avoir des informations.',
            'status' => 'new',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'new')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/contact-messages/{$createdId}", [
            'status' => 'answered',
            'read_at' => '2026-08-21 10:00:00',
            'answered_at' => '2026-08-21 11:00:00',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'answered');

        $this->deleteJson("/api/v1/admin/contact-messages/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('contact_messages', ['id' => $createdId]);
    }

    public function test_admin_can_crud_newsletter_subscribers(): void
    {
        NewsletterSubscriber::create([
            'name' => 'Alpha Abonne',
            'email' => 'alpha-news@example.test',
            'status' => 'subscribed',
            'subscribed_at' => '2026-08-01 08:00:00',
        ]);
        NewsletterSubscriber::create([
            'name' => 'Sorti',
            'email' => 'sorti@example.test',
            'status' => 'unsubscribed',
            'subscribed_at' => '2026-08-01 08:00:00',
            'unsubscribed_at' => '2026-08-15 08:00:00',
        ]);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/newsletter-subscribers?search=Alpha&status=subscribed&sort=email&direction=asc')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.email', 'alpha-news@example.test');

        $createdId = $this->postJson('/api/v1/admin/newsletter-subscribers', [
            'name' => 'Nouvel abonne',
            'email' => 'new-subscriber@example.test',
            'status' => 'subscribed',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'subscribed')
            ->json('data.id');

        $this->patchJson("/api/v1/admin/newsletter-subscribers/{$createdId}", [
            'status' => 'unsubscribed',
            'unsubscribed_at' => '2026-08-21 12:00:00',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'unsubscribed');

        $this->deleteJson("/api/v1/admin/newsletter-subscribers/{$createdId}")->assertNoContent();
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $createdId]);
    }

    public function test_communication_validation_rejects_invalid_payloads(): void
    {
        NewsletterSubscriber::create([
            'email' => 'duplicate@example.test',
            'subscribed_at' => now(),
        ]);

        Sanctum::actingAs($this->userWithRole('admin'));

        $this->postJson('/api/v1/admin/contact-messages', [
            'name' => '',
            'email' => 'bad-email',
            'message' => '',
            'status' => 'closed',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message', 'status']);

        $this->postJson('/api/v1/admin/newsletter-subscribers', [
            'email' => 'duplicate@example.test',
            'status' => 'bounced',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'status']);
    }

    public function test_missing_communication_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/v1/admin/contact-messages/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/newsletter-subscribers/999999')->assertNotFound();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
