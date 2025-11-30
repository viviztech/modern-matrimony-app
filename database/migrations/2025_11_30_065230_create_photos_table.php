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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Photo URLs
            $table->string('url');
            $table->string('thumbnail_url')->nullable();

            // Photo details
            $table->unsignedTinyInteger('order')->default(0);
            $table->boolean('is_primary')->default(false);

            // AI Verification
            $table->unsignedTinyInteger('verification_score')->default(0)->comment('0-100, AI authenticity check');
            $table->boolean('has_face')->default(false);
            $table->boolean('is_appropriate')->default(true);

            // Moderation
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('is_primary');
            $table->index('status');
            $table->index(['user_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
