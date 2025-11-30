<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'age_min',
        'age_max',
        'height_min',
        'height_max',
        'body_type_preferences',
        'city_preferences',
        'state_preferences',
        'distance_radius',
        'willing_to_relocate',
        'education_levels',
        'occupation_types',
        'income_min',
        'income_max',
        'religion_preferences',
        'caste_preferences',
        'mother_tongue_preferences',
        'diet_preferences',
        'drinking_preferences',
        'smoking_preferences',
        'marital_status_preferences',
        'accept_children',
        'dealbreakers',
        'min_compatibility_score',
        'verified_profiles_only',
        'with_photos_only',
        'with_video_only',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'body_type_preferences' => 'array',
            'city_preferences' => 'array',
            'state_preferences' => 'array',
            'education_levels' => 'array',
            'occupation_types' => 'array',
            'religion_preferences' => 'array',
            'caste_preferences' => 'array',
            'mother_tongue_preferences' => 'array',
            'diet_preferences' => 'array',
            'drinking_preferences' => 'array',
            'smoking_preferences' => 'array',
            'marital_status_preferences' => 'array',
            'dealbreakers' => 'array',
            'willing_to_relocate' => 'boolean',
            'accept_children' => 'boolean',
            'verified_profiles_only' => 'boolean',
            'with_photos_only' => 'boolean',
            'with_video_only' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a profile matches user preferences.
     */
    public function matchesProfile(Profile $profile, User $profileUser): bool
    {
        // Age check
        $age = $profileUser->age;
        if ($age && ($age < $this->age_min || $age > $this->age_max)) {
            return false;
        }

        // Height check
        if ($this->height_min && $profile->height < $this->height_min) {
            return false;
        }
        if ($this->height_max && $profile->height > $this->height_max) {
            return false;
        }

        // Education check
        if ($this->education_levels && !in_array($profile->education, $this->education_levels)) {
            return false;
        }

        // Religion check
        if ($this->religion_preferences && !in_array($profile->religion, $this->religion_preferences)) {
            return false;
        }

        // Marital status check
        if ($this->marital_status_preferences && !in_array($profile->marital_status, $this->marital_status_preferences)) {
            return false;
        }

        // Children check
        if (!$this->accept_children && $profile->have_children) {
            return false;
        }

        // Photos check
        if ($this->with_photos_only && $profileUser->photos()->approved()->count() === 0) {
            return false;
        }

        // Video check
        if ($this->with_video_only && !$profile->video_intro_url) {
            return false;
        }

        return true;
    }
}
