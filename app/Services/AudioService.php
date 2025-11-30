<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioService
{
    /**
     * Maximum voice message duration in seconds.
     */
    protected int $maxDuration = 60;

    /**
     * Maximum file size in bytes (5MB).
     */
    protected int $maxFileSize = 5 * 1024 * 1024;

    /**
     * Allowed audio MIME types.
     */
    protected array $allowedMimeTypes = [
        'audio/webm',
        'audio/ogg',
        'audio/mp4',
        'audio/mpeg',
        'audio/wav',
    ];

    /**
     * Upload a voice message.
     */
    public function uploadVoiceMessage(UploadedFile $file, int $userId): array
    {
        // Validate file
        $this->validateAudioFile($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Store file
        $path = $file->storeAs(
            'voice-messages/' . date('Y/m'),
            $filename,
            'public'
        );

        // Get file info
        $duration = $this->getAudioDuration($file);
        $size = $file->getSize();

        return [
            'url' => Storage::url($path),
            'path' => $path,
            'filename' => $filename,
            'size' => $size,
            'duration' => $duration,
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Delete a voice message file.
     */
    public function deleteVoiceMessage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Validate audio file.
     */
    protected function validateAudioFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException('Voice message file size exceeds maximum limit of 5MB');
        }

        // Check MIME type
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid audio file type. Allowed types: ' . implode(', ', $this->allowedMimeTypes));
        }

        // Check if file is valid
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('Invalid audio file');
        }
    }

    /**
     * Generate unique filename for audio file.
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            // Guess extension from MIME type
            $extension = $this->extensionFromMimeType($file->getMimeType());
        }

        return Str::random(40) . '.' . $extension;
    }

    /**
     * Get file extension from MIME type.
     */
    protected function extensionFromMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'audio/webm' => 'webm',
            'audio/ogg' => 'ogg',
            'audio/mp4' => 'mp4',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
            default => 'webm',
        };
    }

    /**
     * Get audio duration in seconds.
     * Note: This is a basic implementation. For production, consider using
     * getID3 library or FFmpeg for accurate duration extraction.
     */
    protected function getAudioDuration(UploadedFile $file): ?int
    {
        // For now, return null. In production, use getID3 or FFmpeg:
        // $getID3 = new \getID3;
        // $fileInfo = $getID3->analyze($file->getRealPath());
        // return isset($fileInfo['playtime_seconds']) ? (int) $fileInfo['playtime_seconds'] : null;

        return null;
    }

    /**
     * Compress audio file (placeholder for future implementation).
     * In production, use FFmpeg to compress audio files.
     */
    public function compressAudio(string $inputPath, string $outputPath): bool
    {
        // Placeholder for FFmpeg compression
        // Example: ffmpeg -i input.webm -b:a 64k output.webm
        // For now, just return true
        return true;
    }

    /**
     * Get waveform data from audio file (placeholder).
     * In production, use FFmpeg or similar to extract waveform data.
     */
    public function generateWaveform(string $filePath): array
    {
        // Placeholder for waveform generation
        // In production, use FFmpeg to extract audio data and generate waveform
        return [];
    }

    /**
     * Validate voice message duration.
     */
    public function validateDuration(?int $duration): bool
    {
        if ($duration === null) {
            return true; // Unknown duration, allow it
        }

        return $duration <= $this->maxDuration;
    }

    /**
     * Get max duration.
     */
    public function getMaxDuration(): int
    {
        return $this->maxDuration;
    }

    /**
     * Get max file size.
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Get allowed MIME types.
     */
    public function getAllowedMimeTypes(): array
    {
        return $this->allowedMimeTypes;
    }
}
