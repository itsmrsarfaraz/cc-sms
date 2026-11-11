<?php

namespace App\Support\Access;

use App\Enums\AccessScope;

/**
 * The single source of truth for permission keys.
 * Add new permissions here, then run SyncPermissionCatalog.
 */
final class PermissionCatalog
{
    /**
     * @return array<string, string> key => description
     */
    public static function institution(): array
    {
        return [
            'institution.settings.manage' => 'Manage institution settings and branding',
            'campuses.manage' => 'Create and edit campuses',
            'roles.manage' => 'Create and edit roles and their permissions',
            'roles.assign' => 'Assign roles to members',
            'audit.view' => 'View the audit log',
            'students.view' => 'View student records',
            'students.create' => 'Create student records',
            'students.update' => 'Edit student records',
            'students.delete' => 'Delete student records',
            'students.export' => 'Export student data',
            'attendance.mark' => 'Mark attendance',
            'attendance.edit' => 'Edit recorded attendance',
            'attendance.approve' => 'Approve attendance corrections',
            'fees.collect' => 'Collect fee payments',
            'fees.refund' => 'Refund fee payments',
            'fees.discount.approve' => 'Approve fee discounts',
            'fees.waive' => 'Waive fees and fines',
            'examinations.create' => 'Create examinations and assessments',
            'examinations.publish' => 'Publish examinations',
            'results.publish' => 'Publish results',
            'payroll.process' => 'Process payroll',
            'payroll.approve' => 'Approve payroll',
            'reports.financial.view' => 'View financial reports',
        ];
    }

    /**
     * @return array<string, string> key => description
     */
    public static function platform(): array
    {
        return [
            'platform.institutions.view' => 'View all institutions',
            'platform.institutions.manage' => 'Create, suspend and archive institutions',
            'platform.users.manage' => 'Manage platform user accounts',
            'platform.plans.manage' => 'Manage subscription plans',
            'platform.coupons.manage' => 'Manage coupon codes',
            'platform.subscriptions.manage' => 'Manage institution subscriptions',
            'platform.support.view' => 'Access support tools',
        ];
    }

    /**
     * @return array<string, array{scope: AccessScope, description: string}>
     */
    public static function all(): array
    {
        $all = [];

        foreach (self::institution() as $key => $description) {
            $all[$key] = ['scope' => AccessScope::Institution, 'description' => $description];
        }

        foreach (self::platform() as $key => $description) {
            $all[$key] = ['scope' => AccessScope::Platform, 'description' => $description];
        }

        return $all;
    }
}