<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstitutionMembership>
 */
class InstitutionMembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'institution_id' => Institution::factory(),
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ];
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MembershipStatus::Suspended,
        ]);
    }
}