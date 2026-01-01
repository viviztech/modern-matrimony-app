<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ImageOptimizationService
{
    // Image size presets
    const SIZES = [
        'thumb' => ['width' => 150, 'height' => 150],
        'small' => ['width' => 400, 'height' => 400],
        'medium' => ['width' => 800, 'height' => 800],
        'large' => ['width' => 1200, 'height' => 1200],
    ];

    // Quality settings
    const WEBP_QUALITY = 85;
    const JPEG_QUALITY = 90;

    // Max file size (in bytes)
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

    /**
     * Process and optimize uploaded image
     */
    public function processImage(UploadedFile $file, string $path = 'photos'): array
    {
        try {
            // Validate file
            $this->validateImage($file);

            // Generate unique filename
            $filename = $this->generateFilename($file);

            // Create all size variations
            $processedImages = [];

            foreach (self::SIZES as $size => $dimensions) {
                $processedImages[$size] = $this->resizeAndOptimize(
                    $file,
                    $path,
                    $filename,
                    $size,
                    $dimensions
                );
            }

            // Store original (optimized)
            $processedImages['original'] = $this->storeOptimizedOriginal($file, $path, $filename);

            return [
                'success' => true,
                'images' => $processedImages,
                'filename' => $filename,
            ];
        } catch (\Exception $e) {
            Log::error('Image processing failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validate uploaded image
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds maximum limit of 10MB');
        }

        // Check mime type
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid file type. Only JPEG, PNG, and WebP are allowed');
        }

        // Check image dimensions
        $imageSize = getimagesize($file->getRealPath());
        if (!$imageSize) {
            throw new \Exception('Invalid image file');
        }

        [$width, $height] = $imageSize;

        // Minimum dimensions
        if ($width < 200 || $height < 200) {
            throw new \Exception('Image dimensions must be at least 200x200 pixels');
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
    }

    /**
     * Resize and optimize image
     */
    protected function resizeAndOptimize(
        UploadedFile $file,
        string $path,
        string $filename,
        string $size,
        array $dimensions
    ): array {
        // Load image
        $img = Image::make($file);

        // Strip EXIF data for privacy
        $img->orientate(); // Auto-rotate based on EXIF

        // Resize while maintaining aspect ratio
        $img->fit(
            $dimensions['width'],
            $dimensions['height'],
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upsizing
            }
        );

        // Generate paths
        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = "{$path}/{$size}/{$baseName}_{$size}.webp";
        $jpegPath = "{$path}/{$size}/{$baseName}_{$size}.jpg";

        // Save as WebP
        $webpEncoded = $img->encode('webp', self::WEBP_QUALITY);
        Storage::disk('public')->put($webpPath, $webpEncoded);

        // Save as JPEG (fallback)
        $jpegEncoded = $img->encode('jpg', self::JPEG_QUALITY);
        Storage::disk('public')->put($jpegPath, $jpegEncoded);

        return [
            'webp' => Storage::url($webpPath),
            'jpeg' => Storage::url($jpegPath),
            'width' => $img->width(),
            'height' => $img->height(),
            'size' => $size,
        ];
    }

    /**
     * Store optimized original
     */
    protected function storeOptimizedOriginal(UploadedFile $file, string $path, string $filename): array
    {
        $img = Image::make($file);

        // Strip EXIF data
        $img->orientate();

        // Don't resize original, just optimize
        $maxDimension = 2000;
        if ($img->width() > $maxDimension || $img->height() > $maxDimension) {
            $img->resize($maxDimension, $maxDimension, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $baseName = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = "{$path}/original/{$baseName}_original.webp";
        $jpegPath = "{$path}/original/{$baseName}_original.jpg";

        // Save as WebP
        $webpEncoded = $img->encode('webp', self::WEBP_QUALITY);
        Storage::disk('public')->put($webpPath, $webpEncoded);

        // Save as JPEG
        $jpegEncoded = $img->encode('jpg', self::JPEG_QUALITY);
        Storage::disk('public')->put($jpegPath, $jpegEncoded);

        return [
            'webp' => Storage::url($webpPath),
            'jpeg' => Storage::url($jpegPath),
            'width' => $img->width(),
            'height' => $img->height(),
            'size' => 'original',
        ];
    }

    /**
     * Generate responsive srcset attribute
     */
    public function generateSrcset(array $images): string
    {
        $srcset = [];

        foreach ($images as $size => $image) {
            if ($size === 'original') {
                continue;
            }

            $srcset[] = "{$image['webp']} {$image['width']}w";
        }

        return implode(', ', $srcset);
    }

    /**
     * Generate picture element HTML
     */
    public function generatePictureElement(array $images, string $alt = '', string $class = ''): string
    {
        $html = '<picture>';

        // WebP sources
        foreach (array_reverse(self::SIZES) as $size => $dimensions) {
            if (!isset($images[$size])) {
                continue;
            }

            $html .= sprintf(
                '<source media="(min-width: %dpx)" srcset="%s" type="image/webp">',
                $dimensions['width'],
                $images[$size]['webp']
            );
        }

        // JPEG sources (fallback)
        foreach (array_reverse(self::SIZES) as $size => $dimensions) {
            if (!isset($images[$size])) {
                continue;
            }

            $html .= sprintf(
                '<source media="(min-width: %dpx)" srcset="%s" type="image/jpeg">',
                $dimensions['width'],
                $images[$size]['jpeg']
            );
        }

        // Fallback img tag
        $defaultImage = $images['medium'] ?? $images['small'] ?? reset($images);
        $html .= sprintf(
            '<img src="%s" alt="%s" class="%s" loading="lazy">',
            $defaultImage['jpeg'],
            htmlspecialchars($alt),
            htmlspecialchars($class)
        );

        $html .= '</picture>';

        return $html;
    }

    /**
     * Delete all variations of an image
     */
    public function deleteImage(string $filename, string $path = 'photos'): bool
    {
        try {
            $baseName = pathinfo($filename, PATHINFO_FILENAME);

            foreach (array_keys(self::SIZES) as $size) {
                Storage::disk('public')->delete("{$path}/{$size}/{$baseName}_{$size}.webp");
                Storage::disk('public')->delete("{$path}/{$size}/{$baseName}_{$size}.jpg");
            }

            // Delete original
            Storage::disk('public')->delete("{$path}/original/{$baseName}_original.webp");
            Storage::disk('public')->delete("{$path}/original/{$baseName}_original.jpg");

            return true;
        } catch (\Exception $e) {
            Log::error('Image deletion failed', [
                'error' => $e->getMessage(),
                'filename' => $filename,
            ]);

            return false;
        }
    }

    /**
     * Get lazy loading attributes
     */
    public function getLazyLoadAttributes(): array
    {
        return [
            'loading' => 'lazy',
            'decoding' => 'async',
        ];
    }

    /**
     * Optimize existing image
     */
    public function optimizeExistingImage(string $filePath): bool
    {
        try {
            $img = Image::make(Storage::disk('public')->path($filePath));

            // Strip EXIF
            $img->orientate();

            // Re-encode with optimization
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (strtolower($extension) === 'webp') {
                $encoded = $img->encode('webp', self::WEBP_QUALITY);
            } else {
                $encoded = $img->encode('jpg', self::JPEG_QUALITY);
            }

            Storage::disk('public')->put($filePath, $encoded);

            return true;
        } catch (\Exception $e) {
            Log::error('Image optimization failed', [
                'error' => $e->getMessage(),
                'file' => $filePath,
            ]);

            return false;
        }
    }

    /**
     * Batch optimize images
     */
    public function batchOptimize(array $filePaths): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($filePaths as $filePath) {
            if ($this->optimizeExistingImage($filePath)) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['errors'][] = $filePath;
            }
        }

        return $results;
    }

    /**
     * Get image info
     */
    public function getImageInfo(string $filePath): ?array
    {
        try {
            $fullPath = Storage::disk('public')->path($filePath);

            if (!file_exists($fullPath)) {
                return null;
            }

            $img = Image::make($fullPath);

            return [
                'width' => $img->width(),
                'height' => $img->height(),
                'mime' => $img->mime(),
                'size' => filesize($fullPath),
                'size_formatted' => $this->formatBytes(filesize($fullPath)),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get image info', [
                'error' => $e->getMessage(),
                'file' => $filePath,
            ]);

            return null;
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Create placeholder/blur hash
     */
    public function createPlaceholder(string $filePath, int $width = 20, int $height = 20): string
    {
        try {
            $img = Image::make(Storage::disk('public')->path($filePath));
            $img->resize($width, $height);
            $img->blur(5);

            return 'data:image/jpeg;base64,' . base64_encode($img->encode('jpg', 50));
        } catch (\Exception $e) {
            Log::error('Placeholder creation failed', [
                'error' => $e->getMessage(),
                'file' => $filePath,
            ]);

            return '';
        }
    }
}
