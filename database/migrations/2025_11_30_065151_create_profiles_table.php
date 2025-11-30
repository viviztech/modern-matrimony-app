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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Media
            $table->string('video_intro_url')->nullable();
            $table->string('video_thumbnail_url')->nullable();
            $table->string('voice_intro_url')->nullable();

            // About
            $table->text('bio')->nullable();
            $table->text('looking_for')->nullable();

            // Physical attributes
            $table->unsignedSmallInteger('height')->nullable()->comment('Height in cm');
            $table->enum('body_type', ['slim', 'average', 'athletic', 'heavy'])->nullable();
            $table->enum('complexion', ['very_fair', 'fair', 'wheatish', 'dark', 'prefer_not_to_say'])->nullable();

            // Professional
            $table->string('education')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('occupation')->nullable();
            $table->string('company')->nullable();
            $table->string('annual_income_range')->nullable();

            // Lifestyle
            $table->enum('diet', ['vegetarian', 'non_vegetarian', 'vegan', 'jain', 'prefer_not_to_say'])->nullable();
            $table->enum('drinking', ['never', 'socially', 'regularly', 'prefer_not_to_say'])->nullable();
            $table->enum('smoking', ['never', 'socially', 'regularly', 'prefer_not_to_say'])->nullable();

            // Cultural & Religious
            $table->string('religion')->nullable();
            $table->tinyInteger('religion_importance')->default(5)->comment('Scale 1-10');
            $table->string('caste')->nullable();
            $table->boolean('show_caste')->default(false);
            $table->string('mother_tongue')->nullable();
            $table->json('languages_known')->nullable();

            // Family
            $table->enum('family_type', ['nuclear', 'joint', 'single_parent', 'prefer_not_to_say'])->nullable();
            $table->text('family_values')->nullable();
            $table->string('family_location')->nullable();
            $table->string('fathers_occupation')->nullable();
            $table->string('mothers_occupation')->nullable();
            $table->tinyInteger('siblings_count')->nullable();

            // Marital status
            $table->enum('marital_status', ['never_married', 'divorced', 'widowed', 'separated'])->default('never_married');
            $table->boolean('have_children')->default(false);
            $table->tinyInteger('children_count')->nullable();

            // Interests & Personality
            $table->json('interests')->nullable()->comment('Array of interest tags');
            $table->json('hobbies')->nullable();
            $table->string('personality_type', 10)->nullable()->comment('MBTI type');
            $table->json('personality_traits')->nullable();

            // Preferences & Deal breakers
            $table->json('dealbreakers')->nullable();

            // Prompts (Hinge-style questions and answers)
            $table->json('prompts')->nullable()->comment('Array of {question, answer, order}');

            // Profile settings
            $table->boolean('is_visible')->default(true);
            $table->boolean('show_online_status')->default(true);
            $table->boolean('allow_messages_from_non_matches')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('education');
            $table->index('occupation');
            $table->index('religion');
            $table->index('marital_status');
            $table->index('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
