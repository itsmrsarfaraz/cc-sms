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
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')
                ->constrained('institutions')
                ->restrictOnDelete();
            $table->string('name', 150);
            $table->string('code', 30);
            $table->string('status', 20);
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->timestamps();

            // A campus code is unique within its institution, not globally.
            $table->unique(['institution_id', 'code']);

            // Target for composite foreign keys from tenant-owned child tables,
            // e.g. students (institution_id, campus_id) -> campuses (institution_id, id).
            // This lets MySQL itself reject a campus from another institution.
            $table->unique(['institution_id', 'id']);

            $table->index(['institution_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};