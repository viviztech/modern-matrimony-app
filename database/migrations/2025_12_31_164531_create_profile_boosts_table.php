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
        Schema::create('profile_boosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('duration_type'); // '24h', '7d', '30d'
            $table->integer('duration_hours'); // Duration in hours
            $table->integer('boost_multiplier')->default(10); // How much to boost (10x visibility)
            $table->timestamp('started_at');
            $table->timestamp('expires_at');
            $table->integer('views_gained')->default(0);
            $table->integer('likes_gained')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'expires_at']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_boosts');
    }
};
