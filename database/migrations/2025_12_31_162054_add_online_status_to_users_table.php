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
            // Add online_at timestamp for tracking when user was last seen online
            $table->timestamp('online_at')->nullable()->after('last_active_at');

            // Add is_online boolean for quick status checks (can be computed from cache)
            // This is a denormalized field for performance
            $table->boolean('is_online')->default(false)->after('online_at');

            // Index for efficient online user queries
            $table->index(['is_online', 'online_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_online', 'online_at']);
            $table->dropColumn(['online_at', 'is_online']);
        });
    }
};
