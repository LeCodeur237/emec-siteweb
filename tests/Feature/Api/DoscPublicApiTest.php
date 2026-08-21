<?php

namespace Tests\Feature\Api;

use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\ImpactStat;
use App\Models\Media;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoscPublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_are_paginated_searchable_filterable_sorted_and_public_only(): void
    {
        $project = SocialProject::factory()->create([
            'title' => 'Soutien aux familles',
            'slug' => 'soutien-aux-familles',
            'short_description' => 'Aide sociale ciblee',
            'description' => 'Projet de compassion pour familles vulnerables.',
            'status' => 'active',
            'featured' => true,
            'deadline' => now()->addMonth()->toDateString(),
            'beneficiaries_count' => 120,
        ]);

        SocialProject::factory()->create([
            'title' => 'Projet brouillon',
            'slug' => 'projet-brouillon',
            'status' => 'draft',
            'featured' => true,
        ]);

        $this->getJson('/api/v1/dosc/projects?search=familles&status=active&featured=true&sort=beneficiaries_count&direction=desc&per_page=100000')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $project->slug)
            ->assertJsonPath('data.0.goal_amount', $project->goal_amount)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'title',
                        'slug',
                        'short_description',
                        'description',
                        'image',
                        'goal_amount',
                        'raised_amount',
                        'beneficiaries_count',
                        'deadline',
                        'status',
                        'featured',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_project_detail_loads_public_actions_campaigns_and_media(): void
    {
        $project = SocialProject::factory()->create([
            'slug' => 'projet-detail',
            'status' => 'active',
        ]);

        SocialAction::factory()->create([
            'social_project_id' => $project->id,
            'status' => 'published',
            'slug' => 'action-visible',
        ]);

        SocialAction::factory()->create([
            'social_project_id' => $project->id,
            'status' => 'draft',
            'slug' => 'action-cachee',
        ]);

        $campaign = DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne active',
            'description' => 'Soutien public',
            'goal_amount' => '250000.00',
            'active' => true,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne expiree',
            'goal_amount' => '100000.00',
            'active' => true,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
        ]);

        $media = Media::create([
            'file_name' => 'dosc.jpg',
            'file_path' => 'media/dosc.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
            'alt_text' => 'Action DOSC',
        ]);

        $project->media()->attach($media->id);

        $this->getJson('/api/v1/dosc/projects/projet-detail')
            ->assertOk()
            ->assertJsonPath('data.slug', 'projet-detail')
            ->assertJsonCount(1, 'data.actions')
            ->assertJsonPath('data.actions.0.slug', 'action-visible')
            ->assertJsonCount(1, 'data.donation_campaigns')
            ->assertJsonPath('data.donation_campaigns.0.id', $campaign->id)
            ->assertJsonPath('data.media.0.file_name', 'dosc.jpg');

        $this->getJson('/api/v1/dosc/projects/projet-inexistant')->assertNotFound();
        $this->getJson('/api/v1/dosc/projects/projet-brouillon')->assertNotFound();
    }

    public function test_actions_are_paginated_filterable_sorted_and_detail_exposes_public_relations(): void
    {
        $project = SocialProject::factory()->create(['status' => 'active']);
        $hiddenProject = SocialProject::factory()->create(['status' => 'draft']);

        $action = SocialAction::factory()->create([
            'social_project_id' => $project->id,
            'title' => 'Distribution de kits scolaires',
            'slug' => 'distribution-kits-scolaires',
            'category' => 'education',
            'location' => 'Yaounde',
            'action_date' => '2026-08-10',
            'youtube_video_id' => 'abc123',
            'beneficiaries_count' => 80,
            'status' => 'published',
        ]);

        SocialAction::factory()->create([
            'social_project_id' => $project->id,
            'slug' => 'action-draft',
            'status' => 'draft',
        ]);

        SocialAction::factory()->create([
            'social_project_id' => $hiddenProject->id,
            'slug' => 'action-projet-cache',
            'status' => 'published',
        ]);

        $action->stats()->create(['label' => 'Beneficiaires', 'value' => '80']);
        $action->testimonials()->create(['name' => 'Temoin public', 'quote' => 'Merci pour le soutien.', 'published' => true]);
        $action->testimonials()->create(['name' => 'Temoin prive', 'quote' => 'Cache', 'published' => false]);

        $this->getJson("/api/v1/dosc/actions?search=kits&social_project_id={$project->id}&category=education&location=Yaounde&from=2026-08-01&to=2026-08-31&sort=beneficiaries_count&direction=desc&per_page=100000")
            ->assertOk()
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $action->slug)
            ->assertJsonPath('data.0.project.id', $project->id);

        $this->getJson('/api/v1/dosc/actions/distribution-kits-scolaires')
            ->assertOk()
            ->assertJsonPath('data.youtube_video_id', 'abc123')
            ->assertJsonPath('data.stats.0.label', 'Beneficiaires')
            ->assertJsonCount(1, 'data.testimonials');

        $this->getJson('/api/v1/dosc/actions/action-draft')->assertNotFound();
        $this->getJson('/api/v1/dosc/actions/action-projet-cache')->assertNotFound();
        $this->getJson('/api/v1/dosc/actions/action-inexistante')->assertNotFound();
    }

    public function test_impact_stats_and_testimonials_apply_public_visibility(): void
    {
        ImpactStat::create(['label' => 'Familles', 'value' => '120', 'suffix' => '+', 'icon' => 'heart', 'sort_order' => 2, 'active' => true]);
        ImpactStat::create(['label' => 'Actions', 'value' => '8', 'sort_order' => 1, 'active' => true]);
        ImpactStat::create(['label' => 'Cache', 'value' => '1', 'sort_order' => 0, 'active' => false]);

        $project = SocialProject::factory()->create(['status' => 'active']);
        $action = SocialAction::factory()->create(['social_project_id' => $project->id, 'status' => 'published']);

        $anonymous = Testimonial::create([
            'social_action_id' => $action->id,
            'name' => null,
            'location' => 'Douala',
            'quote' => 'Temoignage anonyme.',
            'avatar' => null,
            'published' => true,
        ]);

        Testimonial::create([
            'social_action_id' => $action->id,
            'name' => 'Cache',
            'quote' => 'Prive',
            'published' => false,
        ]);

        $this->getJson('/api/v1/dosc/impact-stats')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.label', 'Actions')
            ->assertJsonPath('data.1.label', 'Familles');

        $this->getJson("/api/v1/dosc/testimonials?social_action_id={$action->id}&social_project_id={$project->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $anonymous->id)
            ->assertJsonPath('data.0.name', null)
            ->assertJsonPath('data.0.location', 'Douala')
            ->assertJsonMissingPath('data.0.social_action_id')
            ->assertJsonMissingPath('data.0.published');
    }

    public function test_donation_campaigns_are_read_only_current_and_do_not_expose_donations(): void
    {
        $project = SocialProject::factory()->create(['status' => 'active']);

        $current = DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne courante',
            'description' => 'Aide active',
            'goal_amount' => '500000.00',
            'active' => true,
            'start_date' => now()->subWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
        ]);

        DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne inactive',
            'goal_amount' => '200000.00',
            'active' => false,
            'start_date' => now()->subWeek()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
        ]);

        DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne future',
            'goal_amount' => '200000.00',
            'active' => true,
            'start_date' => now()->addWeek()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
        ]);

        DonationCampaign::create([
            'social_project_id' => $project->id,
            'title' => 'Campagne expiree',
            'goal_amount' => '200000.00',
            'active' => true,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
        ]);

        $this->getJson("/api/v1/dosc/donation-campaigns?social_project_id={$project->id}&from=".now()->toDateString().'&to='.now()->addDay()->toDateString())
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $current->id)
            ->assertJsonPath('data.0.goal_amount', '500000.00')
            ->assertJsonPath('data.0.project.slug', $project->slug)
            ->assertDontSee('donor_email')
            ->assertDontSee('donor_phone')
            ->assertDontSee('transaction_reference');

        $this->getJson("/api/v1/dosc/donation-campaigns/{$current->id}")->assertOk();
        $this->getJson('/api/v1/dosc/donation-campaigns/999999')->assertNotFound();
    }

    public function test_donation_methods_expose_only_active_public_payment_instructions(): void
    {
        DonationMethod::create([
            'name' => 'Orange Money',
            'type' => 'mobile_money',
            'provider' => 'Orange',
            'account_name' => 'NTAP RUBEN',
            'account_number' => '+237678660638',
            'instructions' => 'Indiquer DOSC en motif.',
            'active' => true,
        ]);

        DonationMethod::create([
            'name' => 'Methode interne',
            'type' => 'other',
            'provider' => 'Interne',
            'account_name' => 'Cache',
            'account_number' => 'SECRET',
            'instructions' => 'Cache',
            'active' => false,
        ]);

        $this->getJson('/api/v1/dosc/donation-methods')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Orange Money')
            ->assertJsonPath('data.0.type', 'mobile_money')
            ->assertJsonMissingPath('data.0.active')
            ->assertDontSee('password')
            ->assertDontSee('remember_token')
            ->assertDontSee('donor_email')
            ->assertDontSee('transaction_reference');
    }
}
