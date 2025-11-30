<?php

namespace App\Http\Controllers;

use App\Models\VideoVerification;
use App\Services\FaceVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoVerificationController extends Controller
{
    protected FaceVerificationService $faceService;

    public function __construct(FaceVerificationService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Show video verification page.
     */
    public function showVideoVerification()
    {
        $user = Auth::user();

        // If already verified, redirect
        if ($user->hasVerifiedVideo()) {
            return redirect()->route('dashboard')->with('info', 'Video already verified');
        }

        // Get latest verification attempt if any
        $latestVerification = VideoVerification::getLatestForUser($user);

        return view('verification.video', compact('latestVerification'));
    }

    /**
     * Upload and process video selfie.
     */
    public function uploadVideo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,mov|max:10240', // Max 10MB
        ]);

        try {
            $videoFile = $request->file('video');

            // Store video
            $videoPath = $videoFile->store('verifications/videos', 'private');
            $fullPath = Storage::disk('private')->path($videoPath);

            // Validate video
            $validation = $this->faceService->validateVideo($fullPath);

            if (!$validation['valid']) {
                Storage::disk('private')->delete($videoPath);
                return response()->json([
                    'success' => false,
                    'message' => $validation['error'],
                ], 422);
            }

            // Create verification record
            $verification = VideoVerification::createForUser(
                $user,
                $videoPath,
                $request->ip()
            );

            // Process in background (or immediately for testing)
            $this->processVerification($verification);

            return response()->json([
                'success' => true,
                'message' => 'Video uploaded successfully. Processing...',
                'verification_id' => $verification->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Video upload exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video. Please try again.',
            ], 500);
        }
    }

    /**
     * Check verification status.
     */
    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        $verification = VideoVerification::getLatestForUser($user);

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'No verification found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $verification->verification_status,
            'passed' => $verification->passed,
            'verified' => $verification->verified,
            'liveness_score' => $verification->liveness_score,
            'face_match_score' => $verification->face_match_score,
            'failure_reason' => $verification->failure_reason,
        ]);
    }

    /**
     * Process video verification.
     */
    protected function processVerification(VideoVerification $verification)
    {
        try {
            $verification->markAsProcessing();

            $videoPath = Storage::disk('private')->path($verification->video_path);

            // Step 1: Verify liveness
            $livenessResult = $this->faceService->verifyLiveness($videoPath);

            if (!$livenessResult['success']) {
                $verification->markAsFailed('Liveness detection failed');
                return;
            }

            $livenessScore = $livenessResult['liveness_score'];

            // Step 2: Extract face from video
            $framePath = $this->faceService->extractFaceFromVideo($videoPath);

            if (!$framePath) {
                $verification->markAsFailed('Could not extract face from video');
                return;
            }

            // Save frame path
            $verification->update(['frame_path' => $framePath]);

            // Step 3: Compare with profile photo
            $user = $verification->user;
            $primaryPhoto = $user->primaryPhoto;

            if (!$primaryPhoto) {
                $verification->markForManualReview('No profile photo to compare');
                return;
            }

            $profilePhotoPath = Storage::disk('public')->path($primaryPhoto->file_path);
            $compareResult = $this->faceService->compareFaces($framePath, $profilePhotoPath);

            if (!$compareResult['success']) {
                $verification->markAsFailed('Face comparison failed');
                return;
            }

            $faceMatchScore = $compareResult['similarity'];

            // Step 4: Determine if verification passed
            // Thresholds: liveness > 0.80, face_match > 0.75
            if ($livenessScore >= 0.80 && $faceMatchScore >= 0.75) {
                $verification->markAsPassed($livenessScore, $faceMatchScore);
            } elseif ($livenessScore >= 0.70 && $faceMatchScore >= 0.65) {
                // Borderline case - needs manual review
                $verification->markForManualReview('Scores are borderline - requires manual review');
            } else {
                $reasons = [];
                if ($livenessScore < 0.70) {
                    $reasons[] = 'Low liveness score';
                }
                if ($faceMatchScore < 0.65) {
                    $reasons[] = 'Face does not match profile photo';
                }
                $verification->markAsFailed(implode(', ', $reasons));
            }

            // Store metadata
            $verification->update([
                'metadata' => [
                    'liveness_result' => $livenessResult,
                    'compare_result' => $compareResult,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Video verification processing exception', [
                'verification_id' => $verification->id,
                'error' => $e->getMessage(),
            ]);

            $verification->markAsFailed('Processing error occurred');
        }
    }

    /**
     * Retry verification.
     */
    public function retry()
    {
        $user = Auth::user();

        // Check if user has recent failed attempts
        $recentAttempt = VideoVerification::getLatestForUser($user);

        if ($recentAttempt && $recentAttempt->isPassed()) {
            return redirect()->route('verification.video')
                ->with('error', 'You are already verified');
        }

        return redirect()->route('verification.video')
            ->with('info', 'Please upload a new verification video');
    }

    /**
     * Skip verification (dev only).
     */
    public function skipVerification()
    {
        if (!app()->environment('local')) {
            abort(403, 'This action is only available in local environment');
        }

        $user = Auth::user();

        // Create a fake passed verification
        $verification = VideoVerification::create([
            'user_id' => $user->id,
            'video_path' => 'fake/path.mp4',
            'verification_status' => 'passed',
            'passed' => true,
            'verified' => true,
            'verified_at' => now(),
            'liveness_score' => 0.95,
            'face_match_score' => 0.90,
        ]);

        $user->update([
            'video_verified_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Video verification skipped (development only)');
    }
}
