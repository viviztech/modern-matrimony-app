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
        Schema::create('icebreakers', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->index();
            $table->text('question');
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->integer('success_rate')->default(0)->comment('Percentage 0-100');
            $table->integer('display_order')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['category', 'is_active']);
            $table->index('success_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icebreakers');
    }
};
