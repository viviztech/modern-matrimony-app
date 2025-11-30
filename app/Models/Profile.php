<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'video_intro_url',
        'video_thumbnail_url',
        'voice_intro_url',
        'bio',
        'looking_for',
        'height',
        'body_type',
        'complexion',
        'education',
        'field_of_study',
        'occupation',
        'company',
        'annual_income_range',
        'diet',
        'drinking',
        'smoking',
        'religion',
        'religion_importance',
        'caste',
        'show_caste',
        'mother_tongue',
        'languages_known',
        'family_type',
        'family_values',
        'family_location',
        'fathers_occupation',
        'mothers_occupation',
        'siblings_count',
        'marital_status',
        'have_children',
        'children_count',
        'interests',
        'hobbies',
        'personality_type',
        'personality_traits',
        'dealbreakers',
        'prompts',
        'is_visible',
        'show_online_status',
        'allow_messages_from_non_matches',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'languages_known' => 'array',
            'interests' => 'array',
            'hobbies' => 'array',
            'personality_traits' => 'array',
            'dealbreakers' => 'array',
            'prompts' => 'array',
            'show_caste' => 'boolean',
            'is_visible' => 'boolean',
            'show_online_status' => 'boolean',
            'allow_messages_from_non_matches' => 'boolean',
            'have_children' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get height in feet and inches.
     */
    public function getHeightInFeetAttribute(): ?string
    {
        if (!$this->height) {
            return null;
        }

        $inches = $this->height / 2.54;
        $feet = floor($inches / 12);
        $remainingInches = round($inches % 12);

        return "{$feet}'{$remainingInches}\"";
    }

    /**
     * Scope to get visible profiles.
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to get profiles with video intro.
     */
    public function scopeWithVideo($query)
    {
        return $query->whereNotNull('video_intro_url');
    }

    /**
     * Check if profile is complete.
     */
    public function isComplete(): bool
    {
        $requiredFields = [
            'bio',
            'height',
            'education',
            'occupation',
            'religion',
            'marital_status',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate profile completion percentage.
     */
    public function calculateCompletion(): int
    {
        $fields = [
            'video_intro_url' => 15,
            'voice_intro_url' => 5,
            'bio' => 10,
            'looking_for' => 10,
            'height' => 5,
            'education' => 10,
            'occupation' => 10,
            'religion' => 5,
            'interests' => 10,
            'prompts' => 10,
        ];

        $score = 0;
        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                $score += $weight;
            }
        }

        // Photos are handled separately (add 10 if user has 3+ photos)
        return min($score, 90); // Max 90, photos add the remaining 10
    }
}
