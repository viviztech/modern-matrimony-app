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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('liked_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['like', 'pass', 'super_like'])->default('like');
            $table->timestamps();

            // Prevent duplicate likes
            $table->unique(['user_id', 'liked_user_id']);

            // Index for querying
            $table->index(['user_id', 'type']);
            $table->index('liked_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
