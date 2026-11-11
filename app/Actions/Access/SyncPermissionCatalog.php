<?php

namespace App\Actions\Access;

use App\Models\Permission;
use App\Support\Access\PermissionCatalog;

class SyncPermissionCatalog
{
    /**
     * Create or update every catalog permission. Safe to run repeatedly.
     * Permissions removed from the catalog are NOT deleted automatically,
     * because roles may still reference them.
     *
     * @return int Number of permissions newly created.
     */
    public function handle(): int
    {
        $created = 0;

        foreach (PermissionCatalog::all() as $key => $definition) {
            $permission = Permission::updateOrCreate(
                ['key' => $key],
                ['scope' => $definition['scope'], 'description' => $definition['description']],
            );

            if ($permission->wasRecentlyCreated) {
                $created++;
            }
        }

        return $created;
    }

    /**
     * Sync only when something in the catalog is missing from the database.
     */
    public function ensureSynced(): void
    {
        $missing = array_diff(
            array_keys(PermissionCatalog::all()),
            Permission::query()->pluck('key')->all(),
        );

        if ($missing !== []) {
            $this->handle();
        }
    }
}