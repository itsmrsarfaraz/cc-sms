<?php

namespace App\Actions\Institutions;

use App\Actions\Access\ProvisionInstitutionRoles;
use App\Enums\CampusStatus;
use App\Enums\InstitutionStatus;
use App\Enums\MembershipStatus;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RegisterInstitution
{
    public function __construct(private ProvisionInstitutionRoles $provisionRoles) {}

    /**
     * Create an institution for an owner, in one transaction: either everything
     * below is saved, or nothing is.
     *
     * $data must already be validated (a Form Request does this later).
     * Status is always forced to "trial"; callers cannot choose it.
     *
     * @param  array<string, mixed>  $data
     */
    public function handle(User $owner, array $data): Institution
    {
        if (! $owner->isActive()) {
            throw new InvalidArgumentException('A deactivated account cannot own an institution.');
        }

        return DB::transaction(function () use ($owner, $data): Institution {
            $institution = Institution::create([
                ...$data,
                'status' => InstitutionStatus::Trial,
            ]);

            $institution->campuses()->create([
                'name' => 'Main Campus',
                'code' => 'MAIN',
                'status' => CampusStatus::Active,
            ]);

            $roles = $this->provisionRoles->handle($institution);

            $membership = InstitutionMembership::unguarded(fn () => InstitutionMembership::create([
                'user_id' => $owner->id,
                'institution_id' => $institution->id,
                'status' => MembershipStatus::Active,
                'joined_at' => now(),
            ]));

            RoleAssignment::unguarded(fn () => RoleAssignment::create([
                'institution_id' => $institution->id,
                'membership_id' => $membership->id,
                'role_id' => $roles['institution-owner']->id,
                'campus_id' => null,
            ]));

            return $institution;
        });
    }
}