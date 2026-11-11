<?php

namespace App\Actions\Access;

use App\Enums\AccessScope;
use App\Models\Permission;
use App\Models\Role;
use App\Support\Access\RoleTemplates;
use Illuminate\Support\Collection;

class ProvisionPlatformRoles
{
    public function __construct(private SyncPermissionCatalog $catalog) {}

    /**
     * Create the platform-level roles (no institution). Existing roles are left untouched.
     *
     * @return Collection<string, Role> keyed by role slug
     */
    public function handle(): Collection
    {
        $this->catalog->ensureSynced();

        $permissionIds = Permission::query()->pluck('id', 'key');
        $roles = collect();

        foreach (RoleTemplates::platformRoles() as $slug => $template) {
            $role = Role::query()
                ->whereNull('institution_id')
                ->where('slug', $slug)
                ->first();

            if ($role === null) {
                $role = Role::unguarded(fn () => Role::create([
                    'institution_id' => null,
                    'scope' => AccessScope::Platform,
                    'slug' => $slug,
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'is_system' => true,
                ]));

                $role->permissions()->attach(
                    collect(RoleTemplates::resolveKeys($template['permissions'], AccessScope::Platform))
                        ->map(fn (string $key) => $permissionIds[$key])
                        ->all()
                );
            }

            $roles->put($slug, $role);
        }

        return $roles;
    }
}