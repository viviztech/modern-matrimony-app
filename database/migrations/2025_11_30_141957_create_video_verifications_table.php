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
        Schema::create('video_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('video_path');
            $table->string('frame_path')->nullable();
            $table->decimal('liveness_score', 5, 2)->nullable();
            $table->decimal('face_match_score', 5, 2)->nullable();
            $table->boolean('motion_detected')->default(false);
            $table->boolean('passed')->default(false);
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_status')->default('pending'); // pending, processing, passed, failed, manual_review
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Store raw API responses
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'verified']);
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_verifications');
    }
};
