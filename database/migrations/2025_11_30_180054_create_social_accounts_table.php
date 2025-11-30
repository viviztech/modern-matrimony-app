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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // linkedin, instagram, facebook
            $table->string('provider_id')->unique(); // Social platform user ID
            $table->string('provider_username')->nullable();
            $table->string('provider_email')->nullable();
            $table->string('provider_avatar')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('provider_data')->nullable(); // Store additional data
            $table->boolean('is_verified')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique(['user_id', 'provider']); // One account per provider per user
            $table->index('provider');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
