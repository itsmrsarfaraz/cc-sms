# Decision Log

Each entry records a decision, the reasoning, and the consequences. Entries are never deleted. A reversed decision gets a new entry that supersedes the old one.

## ADR-001: Shared database multi-tenancy with `institution_id`

- **Status:** Accepted
- **Decision:** All institutions share one MySQL database. Every institution-owned row carries `institution_id`.
- **Why:** Simplest to operate (one migration run, one backup), supports platform-wide reporting, and matches the product spec.
- **Risk:** Isolation depends on code discipline, so it is enforced in layers (see ARCHITECTURE.md).
- **Rejected:** Database per institution. Stronger isolation, but much higher operational cost and complexity for this stage.

## ADR-002: Global identity with institution memberships

- **Status:** Accepted
- **Decision:** One `users` row per person. An `institution_memberships` table links a user to an institution (and optionally a campus). Roles attach to the membership, not the user. Platform staff have no membership.
- **Why:** One person can own several institutions, teach at two campuses, or be a parent in two schools. Deactivation can be per institution.

## ADR-003: Custom role and permission system

- **Status:** Accepted
- **Decision:** Own tables (roles, permissions, role_permission, membership_role) checked through Gates and Policies. `spatie/laravel-permission` is not used.
- **Why:** Roles must attach to memberships with optional campus scope, and permission changes must write to our own audit log.
- **Note:** spatie/laravel-permission 8.5.0 resolves with this project. The rejection is a design decision, not a compatibility one.

## ADR-004: Hand-written authentication

- **Status:** Accepted
- **Decision:** Login, logout, password reset, throttling and deactivation checks are written by hand with Form Requests and Blade. Laravel Breeze is not used.
- **Why:** The login flow must understand memberships and active-institution selection, and the spec forbids Livewire and Inertia.

## ADR-005: Tests run on MySQL

- **Status:** Accepted
- **Decision:** PHPUnit uses MySQL database `cc_sms_test`, never SQLite and never the development database. A guard test enforces this.
- **Why:** Row locking, foreign keys and decimal behaviour must match production.

## ADR-006: The institution is the billing entity

- **Status:** Accepted (default, revisable)
- **Decision:** Subscriptions attach to an institution. The owner user is the billing contact. Plans define feature entitlements and limits. Coupons are redeemable per institution.
- **Why:** One owner with several institutions can have different plans, coupons and suspension states per institution.

## ADR-007: Public site resolves its institution by slug

- **Status:** Accepted
- **Decision:** Each institution has a unique `slug`. The 3-page public website resolves its tenant from the slug (path-based first), with optional custom domains later. The logged-in application resolves its tenant from the user's active membership.
- **Why:** Public visitors have no login and no membership.


## ADR-008: Campus scope lives on the role assignment, not the membership

- **Status:** Accepted (refines ADR-002)
- **Decision:** `institution_memberships` has exactly one row per user per institution (unique on `user_id`, `institution_id`). Campus scope is stored on each role assignment (`campus_id` nullable; null means institution-wide).
- **Why:** Status and deactivation belong to the person-in-institution, not to a campus. A teacher at two campuses is one membership with two role assignments, instead of two memberships that could drift apart.
- **Consequence:** Role assignment tables carry `institution_id` and use composite foreign keys to both `institution_memberships (institution_id, id)` and `campuses (institution_id, id)`.