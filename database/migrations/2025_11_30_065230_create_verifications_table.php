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
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Verification type
            $table->enum('type', ['email', 'phone', 'video', 'photo', 'document', 'linkedin', 'instagram', 'facebook'])->default('email');

            // Verification status
            $table->enum('status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');

            // Verification data (JSON for flexibility)
            $table->json('data')->nullable()->comment('Verification specific data');

            // OTP for phone/email verification
            $table->string('otp', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->unsignedTinyInteger('otp_attempts')->default(0);

            // Video/Photo verification
            $table->string('verification_media_url')->nullable();
            $table->decimal('confidence_score', 5, 2)->nullable()->comment('AI confidence score 0-100');

            // Social verification tokens
            $table->string('social_token')->nullable();
            $table->string('social_id')->nullable();

            // Verification timestamps
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Admin review
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index(['user_id', 'type']);
            $table->index('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
