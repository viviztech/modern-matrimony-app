<?php

namespace App\Observers;

use App\Models\Photo;
use App\Models\PhotoVerification;
use App\Services\PhotoVerificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotoObserver
{
    protected PhotoVerificationService $photoService;

    public function __construct(PhotoVerificationService $photoService)
    {
        $this->photoService = $photoService;
    }

    /**
     * Handle the Photo "created" event.
     */
    public function created(Photo $photo): void
    {
        // Automatically verify photo when uploaded
        $this->verifyPhoto($photo);
    }

    /**
     * Verify photo using AI.
     */
    protected function verifyPhoto(Photo $photo): void
    {
        try {
            // Create verification record
            $verification = PhotoVerification::createForPhoto($photo);
            $verification->markAsProcessing();

            // Get photo path
            $photoPath = Storage::disk('public')->path($photo->file_path);

            if (!file_exists($photoPath)) {
                $verification->markAsFailed('Photo file not found');
                return;
            }

            // Run comprehensive photo verification
            $result = $this->photoService->verifyPhoto($photoPath);

            // Store metadata
            $verification->update(['metadata' => $result]);

            // Check if photo passed
            if (!$result['verified']) {
                $verification->markAsFailed($result['reason']);
                return;
            }

            // Check for inappropriate content
            if (isset($result['moderation']) && !$result['moderation']['safe']) {
                $verification->markAsFlagged(
                    $result['moderation']['flagged_categories'] ?? [],
                    'Photo contains inappropriate content'
                );
                return;
            }

            // Get primary photo for face matching (if not primary photo)
            $isPrimaryPhoto = $photo->is_primary;
            $matchesPrimary = null;
            $faceMatchScore = null;

            if (!$isPrimaryPhoto) {
                $primaryPhoto = $photo->user->primaryPhoto;

                if ($primaryPhoto && $primaryPhoto->id !== $photo->id) {
                    $primaryPhotoPath = Storage::disk('public')->path($primaryPhoto->file_path);

                    if (file_exists($primaryPhotoPath)) {
                        $comparison = $this->photoService->compareFaces($primaryPhotoPath, $photoPath);

                        $matchesPrimary = $comparison['match'] ?? false;
                        $faceMatchScore = $comparison['similarity'] ?? null;

                        // Flag if faces don't match
                        if (!$matchesPrimary || $faceMatchScore < 0.70) {
                            $verification->markAsFailed('Photo does not match primary photo');
                            return;
                        }
                    }
                }
            }

            // Mark as passed
            $verification->markAsPassed([
                'quality_score' => $result['quality_score'] ?? null,
                'face_count' => $result['face_detection']['face_count'] ?? 0,
                'estimated_age_low' => $result['age_estimate']['age_low'] ?? null,
                'estimated_age_high' => $result['age_estimate']['age_high'] ?? null,
                'matches_primary_photo' => $matchesPrimary,
                'face_match_score' => $faceMatchScore,
            ]);

        } catch (\Exception $e) {
            Log::error('Photo verification exception', [
                'photo_id' => $photo->id,
                'error' => $e->getMessage(),
            ]);

            // Create failed verification record
            if (isset($verification)) {
                $verification->markAsFailed('Processing error occurred');
            }
        }
    }

    /**
     * Handle the Photo "updated" event.
     */
    public function updated(Photo $photo): void
    {
        //
    }

    /**
     * Handle the Photo "deleted" event.
     */
    public function deleted(Photo $photo): void
    {
        //
    }

    /**
     * Handle the Photo "restored" event.
     */
    public function restored(Photo $photo): void
    {
        //
    }

    /**
     * Handle the Photo "force deleted" event.
     */
    public function forceDeleted(Photo $photo): void
    {
        //
    }
}
