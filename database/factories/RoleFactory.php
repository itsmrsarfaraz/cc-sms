<?php

namespace Database\Factories;

use App\Enums\AccessScope;
use App\Models\Institution;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institution::factory(),
            'scope' => AccessScope::Institution,
            'slug' => fake()->unique()->slug(2),
            'name' => fake()->unique()->jobTitle(),
            'description' => null,
            'is_system' => false,
        ];
    }

    /**
     * A platform role belongs to no institution.
     */
    public function platform(): static
    {
        return $this->state(fn (array $attributes) => [
            'institution_id' => null,
            'scope' => AccessScope::Platform,
        ]);
    }
}