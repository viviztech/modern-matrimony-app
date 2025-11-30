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
            $table->boolean('is_admin')->default(false)->after('is_premium');
            $table->timestamp('banned_at')->nullable()->after('last_active_at');
            $table->text('ban_reason')->nullable()->after('banned_at');
            $table->timestamp('suspended_until')->nullable()->after('ban_reason');
            $table->text('suspension_reason')->nullable()->after('suspended_until');

            $table->index('is_admin');
            $table->index('banned_at');
            $table->index('suspended_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin']);
            $table->dropIndex(['banned_at']);
            $table->dropIndex(['suspended_until']);

            $table->dropColumn([
                'is_admin',
                'banned_at',
                'ban_reason',
                'suspended_until',
                'suspension_reason',
            ]);
        });
    }
};
