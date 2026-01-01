<?php

namespace Tests\Unit\Services;

use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MediaService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MediaService();
        Storage::fake('public');
    }

    public function test_can_upload_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $path = $this->service->uploadImage($file, 'profiles');

        $this->assertNotNull($path);
        $this->assertStringContainsString('profiles/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_uploaded_image_is_optimized(): void
    {
        $file = UploadedFile::fake()->image('large.jpg', 3000, 2000);

        $path = $this->service->uploadImage($file, 'profiles');

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);

        // Check file size is reduced
        $uploadedSize = Storage::disk('public')->size($path);
        $this->assertLessThan($file->getSize(), $uploadedSize);
    }

    public function test_can_generate_thumbnail(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        $originalPath = $this->service->uploadImage($file, 'profiles');

        $thumbnailPath = $this->service->generateThumbnail($originalPath, 150, 150);

        $this->assertNotNull($thumbnailPath);
        Storage::disk('public')->assertExists($thumbnailPath);
        $this->assertStringContainsString('thumb', $thumbnailPath);
    }

    public function test_can_generate_multiple_sizes(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 1200, 900);

        $sizes = $this->service->uploadWithMultipleSizes($file, 'profiles', [
            'small' => [300, 300],
            'medium' => [600, 600],
            'large' => [1200, 1200],
        ]);

        $this->assertIsArray($sizes);
        $this->assertArrayHasKey('original', $sizes);
        $this->assertArrayHasKey('small', $sizes);
        $this->assertArrayHasKey('medium', $sizes);
        $this->assertArrayHasKey('large', $sizes);

        foreach ($sizes as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    public function test_can_delete_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->service->uploadImage($file, 'profiles');

        Storage::disk('public')->assertExists($path);

        $deleted = $this->service->deleteImage($path);

        $this->assertTrue($deleted);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_can_convert_to_webp(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $originalPath = $this->service->uploadImage($file, 'profiles');

        $webpPath = $this->service->convertToWebP($originalPath);

        $this->assertNotNull($webpPath);
        $this->assertStringEndsWith('.webp', $webpPath);
        Storage::disk('public')->assertExists($webpPath);
    }

    public function test_validates_image_dimensions(): void
    {
        $tooSmall = UploadedFile::fake()->image('small.jpg', 50, 50);

        $isValid = $this->service->validateImageDimensions($tooSmall, 100, 100);

        $this->assertFalse($isValid);
    }

    public function test_validates_image_file_size(): void
    {
        // Create a large file
        $largeFile = UploadedFile::fake()->create('large.jpg', 10000); // 10MB

        $isValid = $this->service->validateFileSize($largeFile, 5); // Max 5MB

        $this->assertFalse($isValid);
    }

    public function test_validates_image_mime_type(): void
    {
        $validImage = UploadedFile::fake()->image('test.jpg');
        $invalidFile = UploadedFile::fake()->create('document.pdf');

        $this->assertTrue($this->service->isValidImageType($validImage));
        $this->assertFalse($this->service->isValidImageType($invalidFile));
    }

    public function test_can_get_image_url(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->service->uploadImage($file, 'profiles');

        $url = $this->service->getImageUrl($path);

        $this->assertNotNull($url);
        $this->assertStringContainsString($path, $url);
    }

    public function test_maintains_aspect_ratio_when_resizing(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 1600, 900);

        $path = $this->service->uploadImage($file, 'profiles', 800);

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);

        // Aspect ratio should be maintained (16:9)
        $image = imagecreatefromstring(Storage::disk('public')->get($path));
        $width = imagesx($image);
        $height = imagesy($image);

        $aspectRatio = round($width / $height, 2);
        $this->assertEquals(1.78, $aspectRatio, '', 0.1); // 16:9 = 1.78
    }

    public function test_handles_invalid_file_gracefully(): void
    {
        $invalidFile = UploadedFile::fake()->create('not-an-image.txt');

        $path = $this->service->uploadImage($invalidFile, 'profiles');

        $this->assertNull($path);
    }

    public function test_can_crop_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 1000, 1000);
        $originalPath = $this->service->uploadImage($file, 'profiles');

        $croppedPath = $this->service->cropImage($originalPath, 100, 100, 400, 400);

        $this->assertNotNull($croppedPath);
        Storage::disk('public')->assertExists($croppedPath);
    }

    public function test_optimizes_image_quality(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 1200, 900);

        $path = $this->service->uploadImage($file, 'profiles', 1200, 85);

        $this->assertNotNull($path);

        $fileSize = Storage::disk('public')->size($path);
        $this->assertGreaterThan(0, $fileSize);
        $this->assertLessThan($file->getSize(), $fileSize);
    }

    public function test_can_delete_all_image_variants(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 1200, 900);

        $sizes = $this->service->uploadWithMultipleSizes($file, 'profiles', [
            'small' => [300, 300],
            'medium' => [600, 600],
        ]);

        // Delete all variants
        $deleted = $this->service->deleteAllVariants($sizes['original']);

        $this->assertTrue($deleted);

        foreach ($sizes as $path) {
            Storage::disk('public')->assertMissing($path);
        }
    }

    public function test_generates_unique_filename(): void
    {
        $file1 = UploadedFile::fake()->image('test.jpg');
        $file2 = UploadedFile::fake()->image('test.jpg');

        $path1 = $this->service->uploadImage($file1, 'profiles');
        $path2 = $this->service->uploadImage($file2, 'profiles');

        $this->assertNotEquals($path1, $path2);
    }
}
