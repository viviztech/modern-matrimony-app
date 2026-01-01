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
        Schema::create('profile_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('profile_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->integer('duration_seconds')->default(0);
            $table->enum('source', ['discover', 'search', 'match', 'recommendation', 'direct'])->default('direct');
            $table->timestamps();

            // Indexes for performance
            $table->index(['profile_id', 'viewed_at']);
            $table->index(['viewer_id', 'viewed_at']);
            $table->index('viewed_at');
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_views');
    }
};
