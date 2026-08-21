<?php

namespace Tests\Feature\Api;

use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\Role;
use App\Models\SocialProject;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminDonationCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_donation_admin_routes_require_authentication(): void
    {
        [$campaign, $method, $donation] = $this->donationFixture();

        foreach ([
            ['getJson', '/api/v1/admin/donation-campaigns'],
            ['getJson', "/api/v1/admin/donation-campaigns/{$campaign->id}"],
            ['postJson', '/api/v1/admin/donation-campaigns'],
            ['patchJson', "/api/v1/admin/donation-campaigns/{$campaign->id}"],
            ['deleteJson', "/api/v1/admin/donation-campaigns/{$campaign->id}"],
            ['getJson', '/api/v1/admin/donation-methods'],
            ['getJson', "/api/v1/admin/donation-methods/{$method->id}"],
            ['postJson', '/api/v1/admin/donation-methods'],
            ['patchJson', "/api/v1/admin/donation-methods/{$method->id}"],
            ['deleteJson', "/api/v1/admin/donation-methods/{$method->id}"],
            ['getJson', '/api/v1/admin/donations'],
            ['getJson', "/api/v1/admin/donations/{$donation->id}"],
            ['postJson', '/api/v1/admin/donations'],
            ['patchJson', "/api/v1/admin/donations/{$donation->id}"],
            ['deleteJson', "/api/v1/admin/donations/{$donation->id}"],
        ] as [$methodName, $uri]) {
            $this->{$methodName}($uri, [])->assertUnauthorized();
        }
    }

    public function test_user_without_donation_permissions_is_forbidden(): void
    {
        [$campaign, $method, $donation] = $this->donationFixture();

        Sanctum::actingAs($this->userWithRole('messages_editor'));

        $this->getJson('/api/v1/admin/donation-campaigns')->assertForbidden();
        $this->postJson('/api/v1/admin/donation-campaigns', ['title' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/donation-campaigns/{$campaign->id}", ['title' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/donation-campaigns/{$campaign->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/donation-methods')->assertForbidden();
        $this->postJson('/api/v1/admin/donation-methods', ['name' => 'Interdit'])->assertForbidden();
        $this->patchJson("/api/v1/admin/donation-methods/{$method->id}", ['name' => 'Interdit'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/donation-methods/{$method->id}")->assertForbidden();

        $this->getJson('/api/v1/admin/donations')->assertForbidden();
        $this->postJson('/api/v1/admin/donations', ['amount' => 1000])->assertForbidden();
        $this->patchJson("/api/v1/admin/donations/{$donation->id}", ['status' => 'paid'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/donations/{$donation->id}")->assertForbidden();
    }

    public function test_finance_manager_can_crud_donation_campaigns_methods_and_donations(): void
    {
        $project = SocialProject::factory()->create(['title' => 'Projet dons']);
        [$campaign, $method] = $this->donationFixture($project);

        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->getJson("/api/v1/admin/donation-campaigns?search=Campagne&social_project_id={$project->id}&active=true&sort=title&direction=asc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.project.id', $project->id)
            ->assertJsonPath('data.0.donations_count', 1);

        $campaignId = $this->postJson('/api/v1/admin/donation-campaigns', [
            'social_project_id' => $project->id,
            'title' => 'Campagne speciale',
            'description' => 'Soutien cible',
            'goal_amount' => 1500000,
            'active' => true,
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-31',
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Campagne speciale')
            ->json('data.id');

        $methodId = $this->postJson('/api/v1/admin/donation-methods', [
            'name' => 'Orange Money',
            'type' => 'mobile_money',
            'provider' => 'Orange',
            'account_name' => 'EMEC',
            'account_number' => '699000000',
            'instructions' => 'Envoyer puis confirmer.',
            'active' => true,
        ])
            ->assertCreated()
            ->assertJsonPath('data.provider', 'Orange')
            ->json('data.id');

        $donationId = $this->postJson('/api/v1/admin/donations', [
            'donation_campaign_id' => $campaignId,
            'donation_method_id' => $methodId,
            'donor_name' => 'Donateur Test',
            'donor_email' => 'donateur@example.test',
            'donor_phone' => '699000001',
            'amount' => 25000,
            'currency' => 'XAF',
            'transaction_reference' => 'TX-4D-001',
            'status' => 'paid',
            'anonymous' => false,
            'paid_at' => '2026-09-10 12:00:00',
        ])
            ->assertCreated()
            ->assertJsonPath('data.campaign.id', $campaignId)
            ->assertJsonPath('data.method.id', $methodId)
            ->assertJsonPath('data.transaction_reference', 'TX-4D-001')
            ->json('data.id');

        $this->getJson("/api/v1/admin/donations?search=TX-4D&donation_campaign_id={$campaignId}&donation_method_id={$methodId}&social_project_id={$project->id}&status=paid&anonymous=false&paid_from=2026-09-01&paid_to=2026-09-30")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $donationId);

        $this->patchJson("/api/v1/admin/donations/{$donationId}", [
            'status' => 'refunded',
            'transaction_reference' => 'TX-4D-001-R',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'refunded')
            ->assertJsonPath('data.transaction_reference', 'TX-4D-001-R');

        $this->patchJson("/api/v1/admin/donation-campaigns/{$campaignId}", ['active' => false])
            ->assertOk()
            ->assertJsonPath('data.active', false);

        $this->patchJson("/api/v1/admin/donation-methods/{$methodId}", ['active' => false])
            ->assertOk()
            ->assertJsonPath('data.active', false);

        $this->deleteJson("/api/v1/admin/donations/{$donationId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/donation-campaigns/{$campaignId}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/donation-methods/{$methodId}")->assertNoContent();
        $this->assertDatabaseMissing('donations', ['id' => $donationId]);
        $this->assertDatabaseMissing('donation_campaigns', ['id' => $campaignId]);
        $this->assertDatabaseMissing('donation_methods', ['id' => $methodId]);
    }

    public function test_donation_view_permission_does_not_allow_writes(): void
    {
        [$campaign, $method, $donation] = $this->donationFixture();
        $user = User::factory()->create(['status' => 'active']);
        $role = Role::create([
            'name' => 'Donation Viewer',
            'slug' => 'donation_viewer',
            'description' => 'Read only donation access.',
        ]);
        $role->permissions()->sync(Role::where('slug', 'finance_manager')->firstOrFail()
            ->permissions()
            ->where('slug', 'donations.view')
            ->pluck('permissions.id')
            ->all());
        $user->roles()->attach($role);

        Sanctum::actingAs($user);

        $this->getJson('/api/v1/admin/donations')->assertOk();
        $this->getJson('/api/v1/admin/donation-campaigns')->assertOk();
        $this->getJson('/api/v1/admin/donation-methods')->assertOk();
        $this->postJson('/api/v1/admin/donations', ['amount' => 1000])->assertForbidden();
        $this->patchJson("/api/v1/admin/donation-campaigns/{$campaign->id}", ['title' => 'Non'])->assertForbidden();
        $this->patchJson("/api/v1/admin/donation-methods/{$method->id}", ['name' => 'Non'])->assertForbidden();
        $this->deleteJson("/api/v1/admin/donations/{$donation->id}")->assertForbidden();
    }

    public function test_donation_admin_validation_rejects_invalid_payloads(): void
    {
        Donation::create([
            'amount' => 1000,
            'transaction_reference' => 'DUPLICATE-TX',
            'status' => 'paid',
        ]);

        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->postJson('/api/v1/admin/donation-campaigns', [
            'social_project_id' => 999999,
            'title' => '',
            'goal_amount' => -1,
            'end_date' => '2026-01-01',
            'start_date' => '2026-02-01',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['social_project_id', 'title', 'goal_amount', 'end_date']);

        $this->postJson('/api/v1/admin/donation-methods', [
            'name' => '',
            'type' => 'crypto',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'type']);

        $this->postJson('/api/v1/admin/donations', [
            'donation_campaign_id' => 999999,
            'donation_method_id' => 999999,
            'donor_email' => 'bad-email',
            'amount' => -1,
            'currency' => 'XAFC',
            'transaction_reference' => 'DUPLICATE-TX',
            'status' => 'unknown',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'donation_campaign_id',
                'donation_method_id',
                'donor_email',
                'amount',
                'currency',
                'transaction_reference',
                'status',
            ]);
    }

    public function test_deleting_campaign_or_method_preserves_donations_with_null_relations(): void
    {
        [$campaign, $method, $donation] = $this->donationFixture();

        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->deleteJson("/api/v1/admin/donation-campaigns/{$campaign->id}")->assertNoContent();
        $this->deleteJson("/api/v1/admin/donation-methods/{$method->id}")->assertNoContent();

        $donation->refresh();

        $this->assertNull($donation->donation_campaign_id);
        $this->assertNull($donation->donation_method_id);
    }

    public function test_missing_donation_admin_resources_return_not_found(): void
    {
        Sanctum::actingAs($this->userWithRole('finance_manager'));

        $this->getJson('/api/v1/admin/donation-campaigns/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/donation-methods/999999')->assertNotFound();
        $this->getJson('/api/v1/admin/donations/999999')->assertNotFound();
    }

    private function donationFixture(?SocialProject $project = null): array
    {
        $project ??= SocialProject::factory()->create();
        $campaign = DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne de dons',
            'description' => 'Campagne test',
            'goal_amount' => 1000000,
            'active' => true,
            'start_date' => '2026-08-01',
            'end_date' => '2026-12-31',
        ]);
        $method = DonationMethod::create([
            'name' => 'MTN Money',
            'type' => 'mobile_money',
            'provider' => 'MTN',
            'account_name' => 'EMEC',
            'account_number' => '650000000',
            'active' => true,
        ]);
        $donation = Donation::create([
            'donation_campaign_id' => $campaign->id,
            'donation_method_id' => $method->id,
            'donor_name' => 'Donateur',
            'donor_email' => 'donateur@example.test',
            'donor_phone' => '650000001',
            'amount' => 10000,
            'currency' => 'XAF',
            'transaction_reference' => 'TX-FIXTURE-'.$campaign->id,
            'status' => 'paid',
            'anonymous' => false,
            'paid_at' => '2026-08-21 10:00:00',
        ]);

        return [$campaign, $method, $donation];
    }

    private function userWithRole(string $roleSlug): User
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->roles()->attach(Role::where('slug', $roleSlug)->firstOrFail());

        return $user;
    }
}
