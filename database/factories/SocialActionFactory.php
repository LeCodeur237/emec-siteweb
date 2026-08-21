<?php

namespace Database\Factories;

use App\Models\SocialAction;
use App\Models\SocialProject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SocialAction>
 */
class SocialActionFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Distribution de kits',
            'Visite communautaire',
            'Atelier de sensibilisation',
            'Campagne de soutien',
        ]).' '.fake()->unique()->numberBetween(100, 999);

        return [
            'social_project_id' => SocialProject::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['education', 'health', 'food', 'community']),
            'description' => fake()->paragraphs(2, true),
            'location' => fake()->city(),
            'action_date' => fake()->optional()->dateTimeBetween('-1 year', '+3 months'),
            'image' => null,
            'youtube_video_id' => null,
            'beneficiaries_count' => fake()->numberBetween(5, 300),
            'status' => 'published',
        ];
    }
}
