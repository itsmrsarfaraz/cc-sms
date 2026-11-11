<?php

namespace Tests\Feature\Access;

use App\Actions\Access\ProvisionInstitutionRoles;
use App\Actions\Access\ProvisionPlatformRoles;
use App\Actions\Access\SyncPermissionCatalog;
use App\Actions\Institutions\RegisterInstitution;
use App\Enums\AccessScope;
use App\Enums\InstitutionStatus;
use App\Enums\InstitutionType;
use App\Enums\MembershipStatus;
use App\Models\Campus;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleAssignment;
use App\Models\User;
use App\Support\Access\PermissionCatalog;
use App\Support\Access\RoleTemplates;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class AccessProvisioningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function institutionData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'City Grammar School',
            'slug' => 'city-grammar',
            'type' => InstitutionType::School,
            'timezone' => 'Asia/Karachi',
            'currency' => 'PKR',
        ], $overrides);
    }

    public function test_syncing_the_catalog_is_idempotent(): void
    {
        $sync = app(SyncPermissionCatalog::class);
        $expected = count(PermissionCatalog::all());

        $this->assertSame($expected, $sync->handle());
        $this->assertSame(0, $sync->handle());
        $this->assertSame($expected, Permission::count());
    }

    public function test_every_template_permission_exists_in_the_catalog(): void
    {
        $templates = [
            [AccessScope::Institution, RoleTemplates::institutionRoles(), PermissionCatalog::institution()],
            [AccessScope::Platform, RoleTemplates::platformRoles(), PermissionCatalog::platform()],
        ];

        foreach ($templates as [$scope, $roles, $catalog]) {
            foreach ($roles as $slug => $template) {
                foreach (RoleTemplates::resolveKeys($template['permissions'], $scope) as $key) {
                    $this->assertArrayHasKey($key, $catalog, "Role '{$slug}' references unknown permission '{$key}'.");
                }
            }
        }
    }

    public function test_provisioning_creates_every_role_once_and_is_idempotent(): void
    {
        $institution = Institution::factory()->create();
        $provision = app(ProvisionInstitutionRoles::class);
        $expected = count(RoleTemplates::institutionRoles());

        $first = $provision->handle($institution);
        $second = $provision->handle($institution);

        $this->assertCount($expected, $first);
        $this->assertSame($expected, Role::where('institution_id', $institution->id)->count());
        $this->assertSame($first['teacher']->id, $second['teacher']->id);
        $this->assertTrue($first['teacher']->is_system);
        $this->assertSame(AccessScope::Institution, $first['teacher']->scope);
    }

    public function test_owner_gets_every_institution_permission_and_cashier_only_fee_collection(): void
    {
        $institution = Institution::factory()->create();

        $roles = app(ProvisionInstitutionRoles::class)->handle($institution);

        $this->assertEqualsCanonicalizing(
            array_keys(PermissionCatalog::institution()),
            $roles['institution-owner']->permissions()->pluck('key')->all(),
        );
        $this->assertSame(['fees.collect'], $roles['cashier']->permissions()->pluck('key')->all());
    }

    public function test_reprovisioning_never_overwrites_a_customised_role(): void
    {
        $institution = Institution::factory()->create();
        $provision = app(ProvisionInstitutionRoles::class);

        $teacher = $provision->handle($institution)['teacher'];
        $teacher->permissions()->attach(Permission::where('key', 'fees.collect')->firstOrFail()->id);
        $before = $teacher->permissions()->count();

        $provision->handle($institution);

        $this->assertSame($before, $teacher->fresh()->permissions()->count());
    }

    public function test_each_institution_gets_its_own_role_copies(): void
    {
        $provision = app(ProvisionInstitutionRoles::class);
        $a = $provision->handle(Institution::factory()->create());
        $b = $provision->handle(Institution::factory()->create());

        $this->assertNotSame($a['teacher']->id, $b['teacher']->id);

        // Customising institution A's teacher role must not affect institution B.
        $mark = Permission::where('key', 'attendance.mark')->firstOrFail();
        $a['teacher']->permissions()->detach($mark->id);

        $this->assertFalse($a['teacher']->permissions()->where('key', 'attendance.mark')->exists());
        $this->assertTrue($b['teacher']->permissions()->where('key', 'attendance.mark')->exists());
    }

    public function test_registering_an_institution_sets_up_everything(): void
    {
        $owner = User::factory()->create();

        $institution = app(RegisterInstitution::class)->handle($owner, $this->institutionData());

        $this->assertSame(InstitutionStatus::Trial, $institution->fresh()->status);
        $this->assertSame(1, $institution->campuses()->count());
        $this->assertSame(
            count(RoleTemplates::institutionRoles()),
            Role::where('institution_id', $institution->id)->count(),
        );

        $membership = InstitutionMembership::where('user_id', $owner->id)
            ->where('institution_id', $institution->id)
            ->firstOrFail();
        $this->assertSame(MembershipStatus::Active, $membership->status);

        $assignment = RoleAssignment::where('membership_id', $membership->id)->firstOrFail();
        $this->assertNull($assignment->campus_id);
        $this->assertSame('institution-owner', $assignment->role->slug);
    }

    public function test_a_caller_cannot_choose_the_initial_status(): void
    {
        $owner = User::factory()->create();

        $institution = app(RegisterInstitution::class)->handle(
            $owner,
            $this->institutionData(['status' => InstitutionStatus::Active]),
        );

        $this->assertSame(InstitutionStatus::Trial, $institution->fresh()->status);
    }

    public function test_one_user_can_own_several_institutions(): void
    {
        $owner = User::factory()->create();
        $register = app(RegisterInstitution::class);

        $register->handle($owner, $this->institutionData());
        $register->handle($owner, $this->institutionData(['name' => 'Second School', 'slug' => 'second-school']));

        $this->assertSame(2, InstitutionMembership::where('user_id', $owner->id)->count());
        $this->assertSame(2, Institution::count());
    }

    public function test_a_deactivated_account_cannot_register_an_institution(): void
    {
        $owner = User::factory()->deactivated()->create();

        try {
            app(RegisterInstitution::class)->handle($owner, $this->institutionData());
            $this->fail('Expected an InvalidArgumentException.');
        } catch (InvalidArgumentException) {
            // expected
        }

        $this->assertSame(0, Institution::count());
    }

    public function test_a_failed_registration_leaves_nothing_behind(): void
    {
        $owner = User::factory()->create();
        $register = app(RegisterInstitution::class);

        $register->handle($owner, $this->institutionData());

        try {
            // Same slug: the database rejects it, and the transaction rolls back.
            $register->handle($owner, $this->institutionData());
            $this->fail('Expected a QueryException.');
        } catch (QueryException) {
            // expected
        }

        $this->assertSame(1, Institution::count());
        $this->assertSame(1, Campus::count());
        $this->assertSame(count(RoleTemplates::institutionRoles()), Role::whereNotNull('institution_id')->count());
        $this->assertSame(1, InstitutionMembership::count());
    }

    public function test_platform_roles_are_provisioned_without_an_institution(): void
    {
        $provision = app(ProvisionPlatformRoles::class);
        $expected = count(RoleTemplates::platformRoles());

        $roles = $provision->handle();
        $provision->handle();

        $this->assertCount($expected, $roles);
        $this->assertSame($expected, Role::whereNull('institution_id')->count());
        $this->assertNull($roles['platform-super-admin']->institution_id);
        $this->assertSame(AccessScope::Platform, $roles['platform-super-admin']->scope);
        $this->assertEqualsCanonicalizing(
            array_keys(PermissionCatalog::platform()),
            $roles['platform-super-admin']->permissions()->pluck('key')->all(),
        );
    }
}