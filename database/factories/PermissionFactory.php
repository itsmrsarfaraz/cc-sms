<?php

namespace Database\Factories;

use App\Enums\AccessScope;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->bothify('module###.action???'),
            'scope' => AccessScope::Institution,
            'description' => null,
        ];
    }

    public function platform(): static
    {
        return $this->state(fn (array $attributes) => [
            'scope' => AccessScope::Platform,
        ]);
    }
}