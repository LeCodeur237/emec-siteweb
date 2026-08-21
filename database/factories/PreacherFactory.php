<?php

namespace Database\Factories;

use App\Models\Preacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Preacher>
 */
class PreacherFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(100, 999)),
            'role' => fake()->optional()->randomElement(['Pasteur', 'Ancien', 'Enseignant biblique', 'Invite']),
            'bio' => fake()->optional()->paragraph(),
            'image' => null,
            'active' => true,
        ];
    }
}
