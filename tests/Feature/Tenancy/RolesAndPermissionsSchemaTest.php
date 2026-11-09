<?php

namespace Tests\Feature\Tenancy;

use App\Models\Campus;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RolesAndPermissionsSchemaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create an assignment the way the future assignment action will:
     * every column set explicitly.
     */
    private function assign(
        InstitutionMembership $membership,
        Role $role,
        ?Campus $campus = null,
        ?int $institutionId = null,
    ): RoleAssignment {
        return RoleAssignment::forceCreate([
            'institution_id' => $institutionId ?? $membership->institution_id,
            'membership_id' => $membership->id,
            'role_id' => $role->id,
            'campus_id' => $campus?->id,
        ]);
    }

    public function test_an_institution_scoped_role_requires_an_institution(): void
    {
        $this->expectException(QueryException::class);

        Role::factory()->create(['institution_id' => null]);
    }

    public function test_a_platform_role_cannot_belong_to_an_institution(): void
    {
        $institution = Institution::factory()->create();

        $this->expectException(QueryException::class);

        Role::factory()->platform()->create(['institution_id' => $institution->id]);
    }

    public function test_role_slugs_repeat_across_institutions_but_not_within_one(): void
    {
        $first = Institution::factory()->create();
        $second = Institution::factory()->create();

        Role::factory()->for($first)->create(['slug' => 'teacher']);
        // The same slug in another institution is allowed.
        Role::factory()->for($second)->create(['slug' => 'teacher']);
        // A platform role may share a slug with an institution role.
        Role::factory()->platform()->create(['slug' => 'teacher']);

        $this->assertSame(3, Role::where('slug', 'teacher')->count());

        $this->expectException(QueryException::class);

        Role::factory()->for($first)->create(['slug' => 'teacher']);
    }

    public function test_platform_role_slugs_are_unique(): void
    {
        Role::factory()->platform()->create(['slug' => 'super-admin']);

        $this->expectException(QueryException::class);

        Role::factory()->platform()->create(['slug' => 'super-admin']);
    }

    public function test_a_role_can_hold_a_permission_only_once(): void
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();

        $role->permissions()->attach($permission->id);

        $this->assertCount(1, $role->permissions);

        $this->expectException(QueryException::class);

        $role->permissions()->attach($permission->id);
    }

    public function test_a_membership_can_hold_roles_institution_wide_and_per_campus(): void
    {
        $institution = Institution::factory()->create();
        [$campusA, $campusB] = Campus::factory()->count(2)->for($institution)->create();
        $membership = InstitutionMembership::factory()->for($institution)->create();
        $role = Role::factory()->for($institution)->create();

        $this->assign($membership, $role);
        $this->assign($membership, $role, $campusA);
        $this->assign($membership, $role, $campusB);

        $this->assertSame(3, RoleAssignment::where('membership_id', $membership->id)->count());
    }

    public function test_a_role_from_another_institution_is_rejected(): void
    {
        $institutionA = Institution::factory()->create();
        $institutionB = Institution::factory()->create();
        $membership = InstitutionMembership::factory()->for($institutionA)->create();
        $foreignRole = Role::factory()->for($institutionB)->create();

        $this->expectException(QueryException::class);

        $this->assign($membership, $foreignRole);
    }

    public function test_a_campus_from_another_institution_is_rejected(): void
    {
        $institutionA = Institution::factory()->create();
        $institutionB = Institution::factory()->create();
        $membership = InstitutionMembership::factory()->for($institutionA)->create();
        $role = Role::factory()->for($institutionA)->create();
        $foreignCampus = Campus::factory()->for($institutionB)->create();

        $this->expectException(QueryException::class);

        $this->assign($membership, $role, $foreignCampus);
    }

    public function test_a_membership_from_another_institution_is_rejected(): void
    {
        $institutionA = Institution::factory()->create();
        $institutionB = Institution::factory()->create();
        $membershipOfA = InstitutionMembership::factory()->for($institutionA)->create();
        $roleOfB = Role::factory()->for($institutionB)->create();

        $this->expectException(QueryException::class);

        // Claims institution B, but the membership belongs to institution A.
        $this->assign($membershipOfA, $roleOfB, null, $institutionB->id);
    }

    public function test_a_duplicate_institution_wide_assignment_is_rejected(): void
    {
        $institution = Institution::factory()->create();
        $membership = InstitutionMembership::factory()->for($institution)->create();
        $role = Role::factory()->for($institution)->create();

        $this->assign($membership, $role);

        $this->expectException(QueryException::class);

        $this->assign($membership, $role);
    }

    public function test_a_role_that_is_in_use_cannot_be_deleted(): void
    {
        $institution = Institution::factory()->create();
        $membership = InstitutionMembership::factory()->for($institution)->create();
        $role = Role::factory()->for($institution)->create();

        $this->assign($membership, $role);

        $this->expectException(QueryException::class);

        $role->delete();
    }

    public function test_a_platform_role_can_be_granted_to_a_user_only_once(): void
    {
        $user = User::factory()->create();
        $role = Role::factory()->platform()->create();
        $row = [
            'user_id' => $user->id,
            'role_id' => $role->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('platform_role_user')->insert($row);

        $this->assertSame(1, DB::table('platform_role_user')->where('user_id', $user->id)->count());

        $this->expectException(QueryException::class);

        DB::table('platform_role_user')->insert($row);
    }
}