<?php

namespace Tests\Feature\Api;

use App\Models\Message;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\Event;
use App\Models\ImpactStat;
use App\Models\Media;
use App\Models\NewsletterSubscriber;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WeeklyProgram;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_profile_and_dashboard_require_authentication(): void
    {
        $this->getJson('/api/v1/admin/me')->assertUnauthorized();
        $this->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
    }

    public function test_admin_me_returns_roles_and_permissions(): void
    {
        $user = $this->userWithRole('messages_editor');

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.roles.0.slug', 'messages_editor')
            ->assertJsonFragment(['slug' => 'messages.view'])
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token');
    }

    public function test_dashboard_only_exposes_counts_allowed_by_permissions(): void
    {
        Message::factory()->count(2)->create();
        SocialProject::factory()->count(3)->create();
        SocialAction::factory()->count(4)->create(['social_project_id' => null]);
        User::factory()->count(2)->create();

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.messages_count', 2)
            ->assertJsonMissingPath('data.social_projects_count')
            ->assertJsonMissingPath('data.users_count');

        Sanctum::actingAs($this->userWithRole('dosc_editor'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.social_projects_count', 3)
            ->assertJsonPath('data.social_actions_count', 4)
            ->assertJsonMissingPath('data.messages_count')
            ->assertJsonMissingPath('data.users_count');
    }

    public function test_dashboard_exposes_detailed_metrics_for_authorized_modules(): void
    {
        Message::factory()->create(['status' => 'published', 'featured' => true]);
        Message::factory()->create(['status' => 'draft', 'featured' => false]);
        Event::factory()->create(['status' => 'published', 'start_at' => now()->addDay()]);
        Event::factory()->create(['status' => 'draft', 'start_at' => now()->subDay()]);
        WeeklyProgram::create([
            'title' => 'Culte dominical',
            'day_of_week' => 0,
            'start_time' => '09:00:00',
            'active' => true,
        ]);
        SocialProject::factory()->create([
            'status' => 'active',
            'featured' => true,
            'goal_amount' => 100000,
            'raised_amount' => 25000,
        ]);
        SocialAction::factory()->create([
            'social_project_id' => null,
            'status' => 'published',
            'beneficiaries_count' => 45,
        ]);
        ImpactStat::create(['label' => 'Familles', 'value' => '45', 'active' => true]);
        Testimonial::create(['name' => 'Beneficiaire', 'quote' => 'Merci pour le soutien.', 'published' => true]);
        $campaign = DonationCampaign::create(['title' => 'Solidarite', 'goal_amount' => 250000, 'active' => true]);
        $method = DonationMethod::create(['name' => 'Mobile Money', 'type' => 'mobile_money', 'active' => true]);
        Donation::create([
            'donation_campaign_id' => $campaign->id,
            'donation_method_id' => $method->id,
            'amount' => 15000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        Donation::create([
            'donation_campaign_id' => $campaign->id,
            'donation_method_id' => $method->id,
            'amount' => 5000,
            'status' => 'pending',
        ]);
        ContactMessage::create([
            'name' => 'Contact EMEC',
            'email' => 'contact@example.test',
            'message' => 'Bonjour',
            'status' => 'new',
        ]);
        NewsletterSubscriber::create([
            'email' => 'newsletter@example.test',
            'status' => 'subscribed',
            'subscribed_at' => now(),
        ]);
        Media::factory()->create(['file_type' => 'image']);
        SiteSetting::create(['key' => 'site.name', 'value' => 'EMEC']);

        Sanctum::actingAs($this->userWithRole('super_admin'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.messages_count', 2)
            ->assertJsonPath('data.published_messages_count', 1)
            ->assertJsonPath('data.draft_messages_count', 1)
            ->assertJsonPath('data.featured_messages_count', 1)
            ->assertJsonPath('data.events_count', 2)
            ->assertJsonPath('data.published_events_count', 1)
            ->assertJsonPath('data.upcoming_events_count', 1)
            ->assertJsonPath('data.active_weekly_programs_count', 1)
            ->assertJsonPath('data.active_social_projects_count', 1)
            ->assertJsonPath('data.social_projects_goal_amount', 100000)
            ->assertJsonPath('data.social_projects_raised_amount', 25000)
            ->assertJsonPath('data.published_social_actions_count', 1)
            ->assertJsonPath('data.social_actions_beneficiaries_count', 45)
            ->assertJsonPath('data.active_impact_stats_count', 1)
            ->assertJsonPath('data.published_testimonials_count', 1)
            ->assertJsonPath('data.active_donation_campaigns_count', 1)
            ->assertJsonPath('data.active_donation_methods_count', 1)
            ->assertJsonPath('data.donations_count', 2)
            ->assertJsonPath('data.paid_donations_count', 1)
            ->assertJsonPath('data.pending_donations_count', 1)
            ->assertJsonPath('data.paid_donations_amount', 15000)
            ->assertJsonPath('data.new_contact_messages_count', 1)
            ->assertJsonPath('data.active_newsletter_subscribers_count', 1)
            ->assertJsonPath('data.image_media_count', 1)
            ->assertJsonPath('data.site_settings_count', 1)
            ->assertJsonPath('data.roles_count', 7)
            ->assertJsonPath('data.notifications_count', 0)
            ->assertJsonPath('data.unread_notifications_count', 0);

        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->getJson('/api/v1/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.donations_count', 2)
            ->assertJsonPath('data.paid_donations_amount', 15000)
            ->assertJsonMissingPath('data.messages_count')
            ->assertJsonMissingPath('data.contact_messages_count')
            ->assertJsonMissingPath('data.users_count');
    }

    public function test_roles_gate_message_admin_access_correctly(): void
    {
        Message::factory()->create(['status' => 'draft']);

        Sanctum::actingAs($this->userWithRole('messages_editor'));
        $this->getJson('/api/v1/admin/messages')->assertOk();

        Sanctum::actingAs($this->userWithRole('super_admin'));
        $this->getJson('/api/v1/admin/messages')->assertOk();

        Sanctum::actingAs($this->userWithRole('dosc_editor'));
        $this->getJson('/api/v1/admin/messages')->assertForbidden();
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
