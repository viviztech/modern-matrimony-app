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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reported_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('reportable_type')->nullable(); // Photo, Message, Call, etc.
            $table->unsignedBigInteger('reportable_id')->nullable();
            $table->string('reason'); // fake_profile, inappropriate_content, harassment, scam, other
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, reviewing, resolved, dismissed
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['reportable_type', 'reportable_id']);
            $table->index('status');
            $table->index('reporter_id');
            $table->index('reported_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
