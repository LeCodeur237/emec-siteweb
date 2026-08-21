<?php

namespace Database\Factories;

use App\Models\Church;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Church>
 */
class ChurchFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Assemblee Locale '.fake()->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(100, 999)),
            'baptism_name' => fake()->optional()->words(3, true),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'neighborhood' => fake()->optional()->streetName(),
            'locality' => fake()->optional()->city(),
            'sector' => fake()->optional()->word(),
            'district' => fake()->optional()->word(),
            'circumscription' => fake()->optional()->word(),
            'mission_field' => fake()->optional()->city(),
            'region' => fake()->randomElement(['Centre', 'Littoral', 'Ouest', 'Sud', 'Nord']),
            'description' => fake()->paragraph(),
            'pastor_vision' => fake()->optional()->paragraph(),
            'contact' => fake()->optional()->phoneNumber(),
            'map_url' => null,
            'image' => null,
            'status' => 'published',
            'active' => true,
        ];
    }
}
