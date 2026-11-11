<?php

namespace App\Actions\Access;

use App\Enums\AccessScope;
use App\Models\Institution;
use App\Models\Permission;
use App\Models\Role;
use App\Support\Access\RoleTemplates;
use Illuminate\Support\Collection;

class ProvisionInstitutionRoles
{
    public function __construct(private SyncPermissionCatalog $catalog) {}

    /**
     * Give an institution its own copy of every standard role.
     * Roles that already exist are left untouched, so a school's own
     * customisations are never overwritten by re-running this action.
     *
     * @return Collection<string, Role> keyed by role slug
     */
    public function handle(Institution $institution): Collection
    {
        $this->catalog->ensureSynced();

        $permissionIds = Permission::query()->pluck('id', 'key');
        $roles = collect();

        foreach (RoleTemplates::institutionRoles() as $slug => $template) {
            $role = Role::query()
                ->where('institution_id', $institution->id)
                ->where('slug', $slug)
                ->first();

            if ($role === null) {
                $role = Role::unguarded(fn () => Role::create([
                    'institution_id' => $institution->id,
                    'scope' => AccessScope::Institution,
                    'slug' => $slug,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'is_system' => true,
                ]));

                $role->permissions()->attach(
                    collect(RoleTemplates::resolveKeys($template['permissions'], AccessScope::Institution))
                        ->map(fn (string $key) => $permissionIds[$key])
                        ->all()
                );
            }

            $roles->put($slug, $role);
        }

        return $roles;
    }
}