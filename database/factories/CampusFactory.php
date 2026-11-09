<?php

namespace Database\Factories;

use App\Enums\CampusStatus;
use App\Models\Campus;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campus>
 */
class CampusFactory extends Factory
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
            'name' => fake()->city().' Campus',
            'code' => strtoupper(fake()->unique()->bothify('C###')),
            'status' => CampusStatus::Active,
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('+92 3## #######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampusStatus::Inactive,
        ]);
    }
}