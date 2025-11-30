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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Age preferences
            $table->unsignedTinyInteger('age_min')->default(22);
            $table->unsignedTinyInteger('age_max')->default(35);

            // Physical preferences
            $table->unsignedSmallInteger('height_min')->nullable()->comment('Height in cm');
            $table->unsignedSmallInteger('height_max')->nullable()->comment('Height in cm');
            $table->json('body_type_preferences')->nullable();

            // Location preferences
            $table->json('city_preferences')->nullable()->comment('Array of preferred cities');
            $table->json('state_preferences')->nullable();
            $table->unsignedSmallInteger('distance_radius')->default(50)->comment('Distance in km');
            $table->boolean('willing_to_relocate')->default(false);

            // Professional preferences
            $table->json('education_levels')->nullable();
            $table->json('occupation_types')->nullable();
            $table->string('income_min')->nullable();
            $table->string('income_max')->nullable();

            // Cultural & Religious preferences
            $table->json('religion_preferences')->nullable();
            $table->json('caste_preferences')->nullable();
            $table->json('mother_tongue_preferences')->nullable();

            // Lifestyle preferences
            $table->json('diet_preferences')->nullable();
            $table->json('drinking_preferences')->nullable();
            $table->json('smoking_preferences')->nullable();

            // Marital status preferences
            $table->json('marital_status_preferences')->nullable();
            $table->boolean('accept_children')->default(true);

            // Dealbreakers
            $table->json('dealbreakers')->nullable()->comment('Array of dealbreaker conditions');

            // Match settings
            $table->unsignedTinyInteger('min_compatibility_score')->default(70)->comment('Minimum score 0-100');
            $table->boolean('verified_profiles_only')->default(false);
            $table->boolean('with_photos_only')->default(true);
            $table->boolean('with_video_only')->default(false);

            $table->timestamps();

            // Indexes
            $table->unique('user_id');
            $table->index('age_min');
            $table->index('age_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
