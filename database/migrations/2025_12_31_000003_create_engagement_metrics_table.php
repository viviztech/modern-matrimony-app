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
        Schema::create('engagement_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('profile_views_count')->default(0);
            $table->integer('profile_viewed_by_count')->default(0);
            $table->integer('likes_sent_count')->default(0);
            $table->integer('likes_received_count')->default(0);
            $table->integer('messages_sent_count')->default(0);
            $table->integer('messages_received_count')->default(0);
            $table->integer('matches_count')->default(0);
            $table->integer('search_count')->default(0);
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('login_count')->default(0);
            $table->timestamps();

            // Unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'date']);

            // Indexes for performance
            $table->index('date');
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_metrics');
    }
};
