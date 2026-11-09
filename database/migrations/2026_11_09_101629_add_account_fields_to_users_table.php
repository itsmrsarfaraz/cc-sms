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
        Schema::table('users', function (Blueprint $table) {
            // Account-level switch. Deactivating here blocks login everywhere.
            // Per-institution access is controlled by the membership status.
            $table->string('status', 20)->default('active')->after('password');
            $table->timestamp('last_login_at')->nullable()->after('status');

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'last_login_at']);
        });
    }
};