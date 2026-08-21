<?php

namespace Database\Factories;

use App\Models\SocialProject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SocialProject>
 */
class SocialProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Soutien scolaire',
            'Aide alimentaire',
            'Accompagnement medical',
            'Formation professionnelle',
        ]).' '.fake()->unique()->numberBetween(100, 999);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(14),
            'description' => fake()->paragraphs(3, true),
            'image' => null,
            'goal_amount' => fake()->randomFloat(2, 100000, 5000000),
            'raised_amount' => fake()->randomFloat(2, 0, 1000000),
            'beneficiaries_count' => fake()->numberBetween(10, 500),
            'deadline' => fake()->optional()->dateTimeBetween('now', '+1 year'),
            'status' => 'active',
            'featured' => fake()->boolean(25),
        ];
    }
}
