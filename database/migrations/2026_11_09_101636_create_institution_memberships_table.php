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
        Schema::create('institution_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('institution_id')
                ->constrained('institutions')
                ->restrictOnDelete();
            $table->string('status', 20);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            // One membership per person per institution (ADR-008).
            $table->unique(['user_id', 'institution_id']);

            // Target for composite foreign keys from role assignments:
            // (institution_id, membership_id) -> institution_memberships (institution_id, id).
            $table->unique(['institution_id', 'id']);

            $table->index(['institution_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_memberships');
    }
};