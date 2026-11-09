<?php

namespace Database\Factories;

use App\Enums\InstitutionStatus;
use App\Enums\InstitutionType;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company().' School',
            'slug' => fake()->unique()->slug(3),
            'type' => InstitutionType::School,
            'status' => InstitutionStatus::Active,
            'registration_number' => fake()->bothify('REG-####-??'),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('+92 3## #######'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country_code' => 'PK',
            'timezone' => 'Asia/Karachi',
            'currency' => 'PKR',
        ];
    }

    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InstitutionStatus::Trial,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InstitutionStatus::Suspended,
        ]);
    }
}