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
        Schema::create('photo_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained('photos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('verification_status')->default('pending'); // pending, processing, passed, failed, flagged
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->integer('face_count')->default(0);
            $table->boolean('has_inappropriate_content')->default(false);
            $table->json('flagged_categories')->nullable(); // Nudity, violence, etc
            $table->integer('estimated_age_low')->nullable();
            $table->integer('estimated_age_high')->nullable();
            $table->boolean('matches_primary_photo')->nullable(); // True if this photo matches primary
            $table->decimal('face_match_score', 5, 2)->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Store raw API responses
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'verification_status']);
            $table->index('verification_status');
            $table->index('has_inappropriate_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_verifications');
    }
};
