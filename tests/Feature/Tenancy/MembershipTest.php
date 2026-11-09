<?php

namespace Tests\Feature\Tenancy;

use App\Enums\AccountStatus;
use App\Enums\MembershipStatus;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_one_user_can_belong_to_multiple_institutions(): void
    {
        $user = User::factory()->create();
        $first = Institution::factory()->create();
        $second = Institution::factory()->create();

        InstitutionMembership::factory()->for($user)->for($first)->create();
        InstitutionMembership::factory()->for($user)->for($second)->create();

        $this->assertCount(2, $user->memberships);
        $this->assertEqualsCanonicalizing(
            [$first->id, $second->id],
            $user->institutions->pluck('id')->all(),
        );
    }

    public function test_a_user_cannot_have_two_memberships_in_the_same_institution(): void
    {
        $user = User::factory()->create();
        $institution = Institution::factory()->create();

        InstitutionMembership::factory()->for($user)->for($institution)->create();

        $this->expectException(QueryException::class);

        InstitutionMembership::factory()->for($user)->for($institution)->create();
    }

    public function test_membership_status_is_cast_and_independent_per_institution(): void
    {
        $user = User::factory()->create();
        $first = Institution::factory()->create();
        $second = Institution::factory()->create();

        InstitutionMembership::factory()->for($user)->for($first)->create();
        InstitutionMembership::factory()->suspended()->for($user)->for($second)->create();

        $statuses = $user->memberships()
            ->get()
            ->mapWithKeys(fn ($m) => [$m->institution_id => $m->status]);

        $this->assertSame(MembershipStatus::Active, $statuses[$first->id]);
        $this->assertSame(MembershipStatus::Suspended, $statuses[$second->id]);
    }

    public function test_a_user_with_memberships_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        InstitutionMembership::factory()->for($user)->create();

        $this->expectException(QueryException::class);

        $user->delete();
    }

    public function test_a_deactivated_account_is_reported_as_inactive(): void
    {
        $active = User::factory()->create();
        $deactivated = User::factory()->deactivated()->create();

        $this->assertTrue($active->fresh()->isActive());
        $this->assertFalse($deactivated->fresh()->isActive());
        $this->assertSame(AccountStatus::Deactivated, $deactivated->fresh()->status);
    }
}