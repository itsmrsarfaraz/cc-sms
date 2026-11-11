<?php

namespace App\Support\Access;

use App\Enums\AccessScope;

/**
 * Default roles copied into each institution when it is created.
 * These are starting points; each institution can edit its own copies.
 */
final class RoleTemplates
{
    /** Marker meaning "every permission of the role's scope". */
    public const ALL = '*';

    /**
     * @return array<string, array{name: string, description: string, permissions: array<int, string>|string}>
     */
    public static function institutionRoles(): array
    {
        return [
            'institution-owner' => [
                'name' => 'Institution Owner',
                'description' => 'Owns the institution account.',
                'permissions' => self::ALL,
            ],
            'institution-administrator' => [
                'name' => 'Institution Administrator',
                'description' => 'Runs day-to-day administration.',
                'permissions' => self::ALL,
            ],
            'principal' => [
                'name' => 'Principal',
                'description' => 'Academic head of the institution.',
                'permissions' => [
                    'students.view', 'students.export', 'attendance.approve',
                    'examinations.publish', 'results.publish',
                    'reports.financial.view', 'audit.view',
                ],
            ],
            'vice-principal' => [
                'name' => 'Vice Principal',
                'description' => 'Assists the principal.',
                'permissions' => ['students.view', 'attendance.approve'],
            ],
            'academic-coordinator' => [
                'name' => 'Academic Coordinator',
                'description' => 'Coordinates academic activity.',
                'permissions' => ['students.view', 'examinations.create'],
            ],
            'accountant' => [
                'name' => 'Accountant',
                'description' => 'Manages fees and financial records.',
                'permissions' => ['fees.collect', 'fees.refund', 'reports.financial.view'],
            ],
            'cashier' => [
                'name' => 'Cashier',
                'description' => 'Collects fee payments.',
                'permissions' => ['fees.collect'],
            ],
            'hr-manager' => [
                'name' => 'HR Manager',
                'description' => 'Manages staff and payroll.',
                'permissions' => ['payroll.process'],
            ],
            'examination-controller' => [
                'name' => 'Examination Controller',
                'description' => 'Controls examinations and results.',
                'permissions' => ['examinations.create', 'examinations.publish', 'results.publish'],
            ],
            'librarian' => [
                'name' => 'Librarian',
                'description' => 'Manages the library.',
                'permissions' => [],
            ],
            'transport-manager' => [
                'name' => 'Transport Manager',
                'description' => 'Manages transport.',
                'permissions' => [],
            ],
            'inventory-manager' => [
                'name' => 'Inventory Manager',
                'description' => 'Manages inventory and assets.',
                'permissions' => [],
            ],
            'teacher' => [
                'name' => 'Teacher',
                'description' => 'Teaching staff.',
                'permissions' => ['students.view', 'attendance.mark'],
            ],
            'class-teacher' => [
                'name' => 'Class Teacher',
                'description' => 'Teacher responsible for a class.',
                'permissions' => ['students.view', 'attendance.mark', 'attendance.edit'],
            ],
            'subject-teacher' => [
                'name' => 'Subject Teacher',
                'description' => 'Teacher responsible for a subject.',
                'permissions' => ['students.view'],
            ],
            'visiting-teacher' => [
                'name' => 'Visiting Teacher',
                'description' => 'Visiting or part-time teacher.',
                'permissions' => ['attendance.mark'],
            ],
            'examination-invigilator' => [
                'name' => 'Examination Invigilator',
                'description' => 'Supervises examinations.',
                'permissions' => [],
            ],
            'student' => [
                'name' => 'Student',
                'description' => 'Student portal user.',
                'permissions' => [],
            ],
            'parent' => [
                'name' => 'Parent',
                'description' => 'Parent portal user.',
                'permissions' => [],
            ],
            'guardian' => [
                'name' => 'Guardian',
                'description' => 'Guardian portal user.',
                'permissions' => [],
            ],
        ];
    }

    /**
     * @return array<string, array{name: string, description: string, permissions: array<int, string>|string}>
     */
    public static function platformRoles(): array
    {
        return [
            'platform-super-admin' => [
                'name' => 'Platform Super Administrator',
                'description' => 'Full control of the platform.',
                'permissions' => self::ALL,
            ],
            'platform-admin' => [
                'name' => 'Platform Administrator',
                'description' => 'Manages institutions and billing.',
                'permissions' => [
                    'platform.institutions.view', 'platform.institutions.manage',
                    'platform.plans.manage', 'platform.coupons.manage',
                    'platform.subscriptions.manage',
                ],
            ],
            'support-admin' => [
                'name' => 'Support Administrator',
                'description' => 'Handles support requests.',
                'permissions' => ['platform.institutions.view', 'platform.support.view'],
            ],
        ];
    }

    /**
     * Expand the ALL marker into concrete permission keys for a scope.
     *
     * @param  array<int, string>|string  $permissions
     * @return array<int, string>
     */
    public static function resolveKeys(array|string $permissions, AccessScope $scope): array
    {
        if ($permissions === self::ALL) {
            return array_keys(
                $scope === AccessScope::Platform
                    ? PermissionCatalog::platform()
                    : PermissionCatalog::institution()
            );
        }

        return $permissions;
    }
}