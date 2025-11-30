<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaceVerificationService
{
    /**
     * Provider for face verification (aws_rekognition, deepface, log)
     */
    protected string $provider;

    /**
     * Provider configuration
     */
    protected array $config;

    public function __construct()
    {
        $this->provider = config('services.face_verification.provider', 'log');
        $this->config = config('services.face_verification.' . $this->provider, []);
    }

    /**
     * Verify liveness from video (detect real person, not photo/video replay).
     */
    public function verifyLiveness(string $videoPath): array
    {
        return match ($this->provider) {
            'aws_rekognition' => $this->verifyLivenessAws($videoPath),
            'deepface' => $this->verifyLivenessDeepFace($videoPath),
            default => $this->verifyLivenessLog($videoPath),
        };
    }

    /**
     * Compare two faces and get similarity score.
     */
    public function compareFaces(string $sourcePath, string $targetPath): array
    {
        return match ($this->provider) {
            'aws_rekognition' => $this->compareFacesAws($sourcePath, $targetPath),
            'deepface' => $this->compareFacesDeepFace($sourcePath, $targetPath),
            default => $this->compareFacesLog($sourcePath, $targetPath),
        };
    }

    /**
     * Extract face from video (first clear frame).
     */
    public function extractFaceFromVideo(string $videoPath): ?string
    {
        try {
            // For now, we'll just extract the first frame
            // In production, you'd use FFmpeg to extract multiple frames and pick the best one

            $framePath = storage_path('app/temp/frame_' . uniqid() . '.jpg');

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Use FFmpeg to extract frame at 1 second
            $command = sprintf(
                'ffmpeg -i %s -ss 00:00:01 -vframes 1 %s 2>&1',
                escapeshellarg($videoPath),
                escapeshellarg($framePath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($framePath)) {
                return $framePath;
            }

            Log::warning('Failed to extract frame from video', [
                'video' => $videoPath,
                'output' => $output,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Face extraction exception', [
                'video' => $videoPath,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify liveness using AWS Rekognition.
     */
    protected function verifyLivenessAws(string $videoPath): array
    {
        try {
            // Placeholder for AWS Rekognition implementation
            // Requires: composer require aws/aws-sdk-php

            Log::warning('AWS Rekognition not configured, using log mode');
            return $this->verifyLivenessLog($videoPath);

            /*
            $rekognition = new \Aws\Rekognition\RekognitionClient([
                'version' => 'latest',
                'region' => $this->config['region'] ?? 'us-east-1',
                'credentials' => [
                    'key' => $this->config['key'] ?? '',
                    'secret' => $this->config['secret'] ?? '',
                ],
            ]);

            // Upload video to S3 first
            $s3Path = $this->uploadToS3($videoPath);

            $result = $rekognition->startFaceDetection([
                'Video' => [
                    'S3Object' => [
                        'Bucket' => $this->config['bucket'],
                        'Name' => $s3Path,
                    ],
                ],
            ]);

            $jobId = $result['JobId'];

            // Poll for results
            $status = 'IN_PROGRESS';
            $maxAttempts = 30;
            $attempt = 0;

            while ($status === 'IN_PROGRESS' && $attempt < $maxAttempts) {
                sleep(2);
                $result = $rekognition->getFaceDetection(['JobId' => $jobId]);
                $status = $result['JobStatus'];
                $attempt++;
            }

            if ($status === 'SUCCEEDED') {
                return [
                    'success' => true,
                    'liveness_score' => 0.95, // AWS doesn't provide this directly
                    'confidence' => $result['VideoMetadata']['Confidence'] ?? 0,
                    'faces_detected' => count($result['Faces'] ?? []),
                ];
            }

            return [
                'success' => false,
                'error' => 'Liveness detection failed',
            ];
            */
        } catch (\Exception $e) {
            Log::error('AWS Rekognition liveness exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Service unavailable',
            ];
        }
    }

    /**
     * Verify liveness using DeepFace (self-hosted).
     */
    protected function verifyLivenessDeepFace(string $videoPath): array
    {
        try {
            // Placeholder for DeepFace API call
            // Requires a DeepFace API server running

            Log::warning('DeepFace not configured, using log mode');
            return $this->verifyLivenessLog($videoPath);

            /*
            $response = Http::attach('video', file_get_contents($videoPath), 'video.mp4')
                ->post($this->config['api_url'] . '/verify-liveness');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => $data['is_live'] ?? false,
                    'liveness_score' => $data['confidence'] ?? 0,
                    'faces_detected' => $data['faces_count'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'error' => 'API request failed',
            ];
            */
        } catch (\Exception $e) {
            Log::error('DeepFace liveness exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Service unavailable',
            ];
        }
    }

    /**
     * Verify liveness using log mode (for development).
     */
    protected function verifyLivenessLog(string $videoPath): array
    {
        Log::info('Face Verification (Log Mode) - Liveness', [
            'video' => $videoPath,
            'size' => filesize($videoPath),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Simulate successful liveness detection
        return [
            'success' => true,
            'liveness_score' => 0.92,
            'confidence' => 0.88,
            'faces_detected' => 1,
            'mode' => 'log',
        ];
    }

    /**
     * Compare faces using AWS Rekognition.
     */
    protected function compareFacesAws(string $sourcePath, string $targetPath): array
    {
        try {
            Log::warning('AWS Rekognition not configured, using log mode');
            return $this->compareFacesLog($sourcePath, $targetPath);

            /*
            $rekognition = new \Aws\Rekognition\RekognitionClient([
                'version' => 'latest',
                'region' => $this->config['region'] ?? 'us-east-1',
                'credentials' => [
                    'key' => $this->config['key'] ?? '',
                    'secret' => $this->config['secret'] ?? '',
                ],
            ]);

            $result = $rekognition->compareFaces([
                'SourceImage' => [
                    'Bytes' => file_get_contents($sourcePath),
                ],
                'TargetImage' => [
                    'Bytes' => file_get_contents($targetPath),
                ],
                'SimilarityThreshold' => 70,
            ]);

            $faceMatches = $result['FaceMatches'] ?? [];

            if (count($faceMatches) > 0) {
                $similarity = $faceMatches[0]['Similarity'];
                return [
                    'success' => true,
                    'similarity' => $similarity / 100, // Convert to 0-1
                    'match' => $similarity >= 80,
                    'confidence' => $faceMatches[0]['Face']['Confidence'] / 100,
                ];
            }

            return [
                'success' => true,
                'similarity' => 0,
                'match' => false,
            ];
            */
        } catch (\Exception $e) {
            Log::error('AWS Rekognition compare exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Service unavailable',
            ];
        }
    }

    /**
     * Compare faces using DeepFace.
     */
    protected function compareFacesDeepFace(string $sourcePath, string $targetPath): array
    {
        try {
            Log::warning('DeepFace not configured, using log mode');
            return $this->compareFacesLog($sourcePath, $targetPath);

            /*
            $response = Http::attach('img1', file_get_contents($sourcePath), 'source.jpg')
                ->attach('img2', file_get_contents($targetPath), 'target.jpg')
                ->post($this->config['api_url'] . '/verify');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'similarity' => $data['similarity'] ?? 0,
                    'match' => $data['verified'] ?? false,
                    'distance' => $data['distance'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'error' => 'API request failed',
            ];
            */
        } catch (\Exception $e) {
            Log::error('DeepFace compare exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Service unavailable',
            ];
        }
    }

    /**
     * Compare faces using log mode (for development).
     */
    protected function compareFacesLog(string $sourcePath, string $targetPath): array
    {
        Log::info('Face Verification (Log Mode) - Compare', [
            'source' => $sourcePath,
            'target' => $targetPath,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Simulate face comparison with high similarity
        return [
            'success' => true,
            'similarity' => 0.87,
            'match' => true,
            'confidence' => 0.91,
            'mode' => 'log',
        ];
    }

    /**
     * Detect if video contains motion (not a static image).
     */
    public function detectMotion(string $videoPath): bool
    {
        try {
            // Use FFmpeg to detect scene changes
            $command = sprintf(
                'ffmpeg -i %s -filter:v "select=\'gt(scene,0.1)\',metadata=print:file=-" -f null - 2>&1 | grep -c "pts_time"',
                escapeshellarg($videoPath)
            );

            exec($command, $output, $returnCode);

            $sceneChanges = (int) ($output[0] ?? 0);

            // If there are scene changes, there's motion
            return $sceneChanges > 0;
        } catch (\Exception $e) {
            Log::error('Motion detection exception', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Validate video meets requirements (duration, format, size).
     */
    public function validateVideo(string $videoPath): array
    {
        try {
            // Check file exists
            if (!file_exists($videoPath)) {
                return [
                    'valid' => false,
                    'error' => 'Video file not found',
                ];
            }

            // Check file size (max 10MB)
            $sizeInMB = filesize($videoPath) / 1024 / 1024;
            if ($sizeInMB > 10) {
                return [
                    'valid' => false,
                    'error' => 'Video file too large (max 10MB)',
                ];
            }

            // Get video duration using FFmpeg
            $command = sprintf(
                'ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s 2>&1',
                escapeshellarg($videoPath)
            );

            exec($command, $output, $returnCode);

            $duration = (float) ($output[0] ?? 0);

            // Check duration (3-10 seconds)
            if ($duration < 3 || $duration > 10) {
                return [
                    'valid' => false,
                    'error' => 'Video must be between 3-10 seconds',
                ];
            }

            return [
                'valid' => true,
                'duration' => $duration,
                'size_mb' => $sizeInMB,
            ];
        } catch (\Exception $e) {
            Log::error('Video validation exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'error' => 'Failed to validate video',
            ];
        }
    }
}
