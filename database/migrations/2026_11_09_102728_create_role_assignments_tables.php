<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id');
            $table->unsignedBigInteger('membership_id');
            $table->unsignedBigInteger('role_id');
            // NULL means the role applies institution-wide (ADR-008).
            $table->unsignedBigInteger('campus_id')->nullable();
            // Maps NULL to 0 so one institution-wide assignment cannot be duplicated.
            $table->unsignedBigInteger('campus_key')->storedAs('COALESCE(campus_id, 0)');
            $table->timestamps();

            $table->unique(['membership_id', 'role_id', 'campus_key']);
            $table->index(['institution_id', 'role_id']);

            // Composite foreign keys: every parent must belong to the SAME institution.
            $table->foreign(['institution_id', 'membership_id'])
                ->references(['institution_id', 'id'])
                ->on('institution_memberships')
                ->restrictOnDelete();
            $table->foreign(['institution_id', 'role_id'])
                ->references(['institution_id', 'id'])
                ->on('roles')
                ->restrictOnDelete();
            // MySQL skips this check when campus_id is NULL (institution-wide).
            $table->foreign(['institution_id', 'campus_id'])
                ->references(['institution_id', 'id'])
                ->on('campuses')
                ->restrictOnDelete();
        });

        // Platform staff have no membership, so their platform roles live here.
        // The application layer must only attach roles whose scope is 'platform'.
        Schema::create('platform_role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_role_user');
        Schema::dropIfExists('role_assignments');
    }
};