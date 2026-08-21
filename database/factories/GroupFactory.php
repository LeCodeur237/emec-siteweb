<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Group>
 */
class GroupFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Jeunesse EMEC',
            'Femmes dynamiques',
            'Hommes de service',
            'Groupe de louange',
            'Intercession',
        ]).' '.fake()->unique()->numberBetween(10, 99);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'short_description' => fake()->sentence(12),
            'image' => null,
            'color' => fake()->hexColor(),
            'contact' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'active' => true,
        ];
    }
}
