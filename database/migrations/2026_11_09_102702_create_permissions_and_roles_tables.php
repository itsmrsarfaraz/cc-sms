<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Global catalog of permission keys. Defined by the platform, never per institution.
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('scope', 20);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('scope');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // NULL for platform roles; set for every institution role (ADR-009).
            $table->foreignId('institution_id')
                ->nullable()
                ->constrained('institutions')
                ->restrictOnDelete();
            $table->string('scope', 20);
            $table->string('slug', 80);
            $table->string('name', 120);
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);
            // A UNIQUE index treats NULLs as distinct, so (institution_id, slug) alone
            // would allow duplicate platform slugs. This generated column maps NULL to 0.
            $table->unsignedBigInteger('tenant_key')->storedAs('COALESCE(institution_id, 0)');
            $table->timestamps();

            $table->unique(['tenant_key', 'slug']);
            // Target for composite foreign keys from role_assignments.
            $table->unique(['institution_id', 'id']);
            $table->index('scope');
        });

        // Platform roles must have no institution; institution roles must have one.
        DB::statement(
            "ALTER TABLE roles ADD CONSTRAINT roles_scope_institution_check CHECK ("
            ."(scope = 'platform' AND institution_id IS NULL) OR "
            ."(scope = 'institution' AND institution_id IS NOT NULL))"
        );

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->restrictOnDelete();

            $table->primary(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};