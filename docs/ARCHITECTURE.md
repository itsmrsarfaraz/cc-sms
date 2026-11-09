# Architecture

## Stack

Laravel 13, PHP 8.4, MySQL 8, Blade, Alpine.js, Tailwind CSS v4, Vite, PHPUnit. No React, Vue, Livewire or Inertia.

## Shape

A modular Laravel monolith organised by business domain (see the Phase list in DEVELOPMENT_PROGRESS.md). Business logic lives in service or action classes, not in controllers or Blade. Validation uses Form Requests. Authorization uses Policies and Gates.

## Tenancy strategy

One shared database. The tenant is the **institution**. A campus is a child of an institution.

Isolation is enforced in layers, so no single mistake exposes another institution's data:

1. **Schema:** every institution-owned table has a non-null `institution_id`. Critical child tables use composite foreign keys such as `(institution_id, parent_id)`, so MySQL itself rejects references that cross institutions.
2. **Query scoping:** a `BelongsToInstitution` trait applies a global scope and stamps `institution_id` on create.
3. **Request context:** a tenant context holds the current institution. For the logged-in application it comes from the user's active membership. Route model binding is tenant-aware, so another school's record returns 404.
4. **Authorization:** Policies check the membership's permissions and campus scope.
5. **Background work:** queued jobs carry the institution id and set the tenant context before running. Exports do the same.
6. **Tests:** a dedicated cross-tenant suite attempts access by URL, binding, exports and jobs, and must fail closed.

## Identity and permissions

- `users`: one row per person (login identity).
- `institution_memberships`: one row per user per institution, with status. Campus scope is on role assignments (ADR-008).
- Roles and permissions are institution-level or platform-level. Roles attach to memberships.
- Sensitive actions require an extra permission and write an audit entry.

## Public website

Each institution has a unique `slug`. The 3-page public site resolves its institution from the slug. This is separate from the logged-in tenant context.

## Platform billing (planned)

Plans define feature entitlements and limits. Subscriptions attach to an institution. Coupons define type (percentage, fixed, free period), validity, redemption limits and plan applicability. Redemptions are recorded per institution. Feature access is checked against the institution's active subscription.

## Data rules

- Money uses DECIMAL columns, never floats.
- Financial, academic and payroll records are never deleted. Mistakes are fixed by reversal or adjustment.
- Audit records are append-only.
- Business dates use the institution's time zone.

## Testing

PHPUnit on MySQL database `cc_sms_test`. A guard test confirms the suite never runs against the development database.