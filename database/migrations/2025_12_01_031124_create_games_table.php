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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['compatibility_quiz', 'would_you_rather', 'twenty_one_questions', 'two_truths_lie']);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->integer('compatibility_score')->nullable();
            $table->json('results')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user1_id', 'user2_id']);
            $table->index('type');
            $table->index('status');
        });

        Schema::create('game_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('question_key');
            $table->text('answer');
            $table->timestamps();

            $table->unique(['game_id', 'user_id', 'question_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_answers');
        Schema::dropIfExists('games');
    }
};
