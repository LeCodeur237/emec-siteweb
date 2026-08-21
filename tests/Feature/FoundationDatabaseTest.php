<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationMethod;
use App\Models\Media;
use App\Models\Message;
use App\Models\NewsletterSubscriber;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\SocialAction;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoundationDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_permission_seed_creates_initial_access_matrix(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $superAdmin = Role::where('slug', 'super_admin')->firstOrFail();

        $this->assertSame(7, Role::count());
        $this->assertSame(46, Permission::count());
        $this->assertSame(46, $superAdmin->permissions()->count());
    }

    public function test_message_factory_creates_related_content_records(): void
    {
        $message = Message::factory()->create();

        $this->assertSame(1, $message->preacher->messages()->count());
        $this->assertSame(1, $message->category->messages()->count());
        $this->assertSame(1, $message->series->messages()->count());
        $this->assertIsInt($message->views);
        $this->assertIsBool($message->featured);
    }

    public function test_dosc_donations_and_media_relationships_are_wired(): void
    {
        $action = SocialAction::factory()->create();
        $action->stats()->create(['label' => 'Beneficiaires', 'value' => '120']);
        $action->testimonials()->create(['quote' => 'Temoignage fictif', 'published' => true]);

        $campaign = DonationCampaign::create([
            'social_project_id' => $action->social_project_id,
            'title' => 'Campagne fictive',
            'goal_amount' => 250000,
            'active' => true,
        ]);

        $method = DonationMethod::create([
            'name' => 'Methode fictive',
            'type' => 'mobile_money',
            'provider' => 'Test',
            'active' => true,
        ]);

        $donation = Donation::create([
            'donation_campaign_id' => $campaign->id,
            'donation_method_id' => $method->id,
            'amount' => 10000,
            'currency' => 'XAF',
            'status' => 'pending',
        ]);

        $media = Media::create([
            'file_name' => 'photo.jpg',
            'file_path' => 'media/photo.jpg',
            'file_type' => 'image',
            'mime_type' => 'image/jpeg',
        ]);

        $action->media()->attach($media->id);

        $this->assertSame(1, $action->project->actions()->count());
        $this->assertSame(1, $action->stats()->count());
        $this->assertSame(1, $action->testimonials()->count());
        $this->assertSame(1, $campaign->donations()->count());
        $this->assertSame(1, $method->donations()->count());
        $this->assertSame('10000.00', $donation->amount);
        $this->assertSame(1, $action->media()->count());
        $this->assertSame(1, $media->socialActions()->count());
    }

    public function test_communication_and_settings_defaults_are_available_on_models(): void
    {
        $contact = new ContactMessage([
            'name' => 'Contact fictif',
            'email' => 'contact@example.test',
            'message' => 'Message fictif',
        ]);

        $subscriber = new NewsletterSubscriber([
            'email' => 'newsletter@example.test',
            'subscribed_at' => now(),
        ]);

        $setting = new SiteSetting([
            'key' => 'contact_email',
            'value' => 'contact@example.test',
        ]);

        $this->assertSame('new', $contact->status);
        $this->assertSame('subscribed', $subscriber->status);
        $this->assertSame('string', $setting->type);
    }
}
