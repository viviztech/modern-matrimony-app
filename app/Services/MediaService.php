<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaService
{
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ];

    protected int $maxFileSize = 10 * 1024 * 1024; // 10MB

    protected array $imageSizes = [
        'thumbnail' => [150, 150],
        'small' => [400, 400],
        'medium' => [800, 800],
        'large' => [1200, 1200],
    ];

    /**
     * Upload and process a photo.
     */
    public function uploadPhoto(User $user, UploadedFile $file, bool $isPrimary = false): Photo
    {
        // Validate file
        $this->validateFile($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Process and store image in multiple sizes
        $paths = $this->processAndStoreImage($file, $filename);

        // Create photo record
        $photo = Photo::create([
            'user_id' => $user->id,
            'filename' => $filename,
            'original_path' => $paths['original'],
            'thumbnail_path' => $paths['thumbnail'],
            'small_path' => $paths['small'],
            'medium_path' => $paths['medium'],
            'large_path' => $paths['large'],
            'is_primary' => $isPrimary,
            'is_verified' => false,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        // If this is primary, unset other primary photos
        if ($isPrimary) {
            Photo::where('user_id', $user->id)
                ->where('id', '!=', $photo->id)
                ->update(['is_primary' => false]);
        }

        return $photo;
    }

    /**
     * Validate uploaded file.
     */
    protected function validateFile(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, and WebP images are allowed.');
        }

        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException('File size exceeds 10MB limit.');
        }

        // Check if file is actually an image
        try {
            $image = Image::make($file);
            $image->destroy();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid image file.');
        }
    }

    /**
     * Generate unique filename.
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Process and store image in multiple sizes.
     */
    protected function processAndStoreImage(UploadedFile $file, string $filename): array
    {
        $paths = [];

        // Store original
        $originalPath = $file->store('photos/original', 'public');
        $paths['original'] = $originalPath;

        // Create and store different sizes
        foreach ($this->imageSizes as $size => [$width, $height]) {
            $image = Image::make($file);

            // Strip EXIF data for privacy
            $image->orientate();

            // Resize image
            $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Optimize quality
            $quality = $size === 'thumbnail' ? 80 : 90;

            // Save to temporary path
            $tempPath = storage_path("app/temp/{$size}_{$filename}");
            $image->save($tempPath, $quality);

            // Store in storage
            $storagePath = "photos/{$size}/{$filename}";
            Storage::disk('public')->put($storagePath, file_get_contents($tempPath));

            $paths[$size] = $storagePath;

            // Clean up temp file
            @unlink($tempPath);

            $image->destroy();
        }

        return $paths;
    }

    /**
     * Delete a photo and all its variants.
     */
    public function deletePhoto(Photo $photo): void
    {
        // Delete all stored files
        $paths = [
            $photo->original_path,
            $photo->thumbnail_path,
            $photo->small_path,
            $photo->medium_path,
            $photo->large_path,
        ];

        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Delete database record
        $photo->delete();
    }

    /**
     * Set photo as primary.
     */
    public function setPrimaryPhoto(User $user, Photo $photo): void
    {
        if ($photo->user_id !== $user->id) {
            throw new \UnauthorizedAccessException('You can only set your own photos as primary.');
        }

        // Unset all other primary photos
        Photo::where('user_id', $user->id)->update(['is_primary' => false]);

        // Set this photo as primary
        $photo->update(['is_primary' => true]);
    }

    /**
     * Get photo URL with proper size.
     */
    public function getPhotoUrl(Photo $photo, string $size = 'medium'): string
    {
        $pathProperty = "{$size}_path";

        if (!property_exists($photo, $pathProperty)) {
            $pathProperty = 'medium_path';
        }

        $path = $photo->$pathProperty;

        if (!$path) {
            return $this->getDefaultPhotoUrl();
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Get default photo URL.
     */
    protected function getDefaultPhotoUrl(): string
    {
        return asset('images/default-profile.png');
    }

    /**
     * Generate responsive srcset for photo.
     */
    public function getResponsiveSrcset(Photo $photo): string
    {
        $srcset = [];

        foreach ($this->imageSizes as $size => $dimensions) {
            $url = $this->getPhotoUrl($photo, $size);
            $width = $dimensions[0];
            $srcset[] = "{$url} {$width}w";
        }

        return implode(', ', $srcset);
    }

    /**
     * Convert image to WebP format.
     */
    public function convertToWebP(Photo $photo): void
    {
        foreach ($this->imageSizes as $size => $dimensions) {
            $pathProperty = "{$size}_path";
            $path = $photo->$pathProperty;

            if (!$path || !Storage::disk('public')->exists($path)) {
                continue;
            }

            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);

            // Save as WebP
            $webpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $path);
            $webpFullPath = Storage::disk('public')->path($webpPath);

            $image->encode('webp', 90)->save($webpFullPath);

            // Update photo record
            $photo->update([$pathProperty => $webpPath]);

            // Delete old file
            Storage::disk('public')->delete($path);

            $image->destroy();
        }
    }

    /**
     * Optimize all user photos.
     */
    public function optimizeUserPhotos(User $user): void
    {
        $photos = $user->photos;

        foreach ($photos as $photo) {
            try {
                $this->convertToWebP($photo);
            } catch (\Exception $e) {
                \Log::error('Failed to optimize photo', [
                    'photo_id' => $photo->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Get storage usage for user.
     */
    public function getUserStorageUsage(User $user): array
    {
        $totalSize = $user->photos()->sum('file_size');

        return [
            'total_photos' => $user->photos()->count(),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / (1024 * 1024), 2),
            'average_size_mb' => $user->photos()->count() > 0
                ? round($totalSize / $user->photos()->count() / (1024 * 1024), 2)
                : 0,
        ];
    }

    /**
     * Bulk delete user photos.
     */
    public function bulkDeletePhotos(User $user, array $photoIds): int
    {
        $photos = Photo::where('user_id', $user->id)
            ->whereIn('id', $photoIds)
            ->get();

        $count = 0;

        foreach ($photos as $photo) {
            try {
                $this->deletePhoto($photo);
                $count++;
            } catch (\Exception $e) {
                \Log::error('Failed to delete photo', [
                    'photo_id' => $photo->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    /**
     * Reorder user photos.
     */
    public function reorderPhotos(User $user, array $photoIdOrder): void
    {
        foreach ($photoIdOrder as $order => $photoId) {
            Photo::where('user_id', $user->id)
                ->where('id', $photoId)
                ->update(['display_order' => $order + 1]);
        }
    }
}
