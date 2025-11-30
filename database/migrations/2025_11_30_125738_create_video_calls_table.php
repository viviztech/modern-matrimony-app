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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->nullable()->constrained('user_matches')->cascadeOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('caller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['initiated', 'ringing', 'active', 'ended', 'missed', 'declined', 'failed'])->default('initiated');
            $table->enum('call_type', ['video', 'audio'])->default('video');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in seconds');
            $table->string('room_id')->nullable()->unique();
            $table->string('recording_url')->nullable();
            $table->integer('quality_rating')->nullable()->comment('1-5 stars');
            $table->text('end_reason')->nullable();
            $table->boolean('reported')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['caller_id', 'created_at']);
            $table->index(['receiver_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
