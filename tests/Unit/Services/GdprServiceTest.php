<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\GdprService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GdprServiceTest extends TestCase
{
    use RefreshDatabase;

    protected GdprService $service;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GdprService();
        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Mock storage
        Storage::fake('local');
    }

    public function test_can_export_user_data(): void
    {
        $data = $this->service->exportUserData($this->user);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('profile', $data);
        $this->assertEquals('john@example.com', $data['user']['email']);
        $this->assertEquals('John Doe', $data['user']['name']);
    }

    public function test_export_includes_all_user_relationships(): void
    {
        $data = $this->service->exportUserData($this->user);

        // Check that all expected keys are present
        $expectedKeys = [
            'user',
            'profile',
            'photos',
            'preferences',
            'matches',
            'messages_sent',
            'messages_received',
            'likes_sent',
            'likes_received',
            'stories',
            'payments',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $data, "Missing key: {$key}");
        }
    }

    public function test_can_request_account_deletion(): void
    {
        $result = $this->service->requestAccountDeletion($this->user);

        $this->assertTrue($result);

        // Refresh user from database
        $this->user->refresh();

        $this->assertNotNull($this->user->deletion_requested_at);
        $this->assertNull($this->user->deleted_at);
    }

    public function test_deletion_sets_30_day_grace_period(): void
    {
        $this->service->requestAccountDeletion($this->user);

        $this->user->refresh();

        $expectedDeletionDate = now()->addDays(30);
        $actualDeletionDate = $this->user->deletion_requested_at->addDays(30);

        $this->assertEquals(
            $expectedDeletionDate->format('Y-m-d'),
            $actualDeletionDate->format('Y-m-d')
        );
    }

    public function test_can_cancel_account_deletion(): void
    {
        // First request deletion
        $this->service->requestAccountDeletion($this->user);
        $this->user->refresh();
        $this->assertNotNull($this->user->deletion_requested_at);

        // Then cancel
        $result = $this->service->cancelAccountDeletion($this->user);

        $this->assertTrue($result);
        $this->user->refresh();
        $this->assertNull($this->user->deletion_requested_at);
    }

    public function test_can_permanently_delete_user_account(): void
    {
        $userId = $this->user->id;

        $result = $this->service->permanentlyDeleteAccount($this->user);

        $this->assertTrue($result);

        // Check user is soft deleted
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
            'deleted_at' => null,
        ]);
    }

    public function test_permanent_deletion_anonymizes_user_data(): void
    {
        $originalEmail = $this->user->email;
        $userId = $this->user->id;

        $this->service->permanentlyDeleteAccount($this->user);

        // Get the deleted user (with trashed)
        $deletedUser = User::withTrashed()->find($userId);

        $this->assertNotNull($deletedUser->deleted_at);
        $this->assertNotEquals($originalEmail, $deletedUser->email);
        $this->assertStringContainsString('deleted_', $deletedUser->email);
    }

    public function test_export_data_as_json_format(): void
    {
        $json = $this->service->exportUserDataAsJson($this->user);

        $this->assertIsString($json);

        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('user', $decoded);
    }

    public function test_export_data_as_csv_format(): void
    {
        $csv = $this->service->exportUserDataAsCsv($this->user);

        $this->assertIsString($csv);
        $this->assertStringContainsString($this->user->email, $csv);
    }

    public function test_can_download_user_data_package(): void
    {
        $path = $this->service->createDataExportPackage($this->user);

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.zip', $path);

        Storage::disk('local')->assertExists($path);
    }

    public function test_respects_gdpr_right_to_be_forgotten(): void
    {
        // User requests deletion
        $this->service->requestAccountDeletion($this->user);

        // Simulate 30 days passing
        $this->travel(31)->days();

        // Process deletions
        $this->service->processScheduledDeletions();

        // User should be deleted
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_deletion_removes_personal_identifiable_information(): void
    {
        $userId = $this->user->id;
        $this->service->permanentlyDeleteAccount($this->user);

        $deletedUser = User::withTrashed()->find($userId);

        // Check PII is removed/anonymized
        $this->assertStringContainsString('deleted_', $deletedUser->email);
        $this->assertNull($deletedUser->phone);
        $this->assertNull($deletedUser->date_of_birth);
    }

    public function test_can_get_pending_deletions_count(): void
    {
        // Create multiple users requesting deletion
        User::factory()->count(3)->create()->each(function ($user) {
            $this->service->requestAccountDeletion($user);
        });

        $count = $this->service->getPendingDeletionsCount();

        $this->assertEquals(3, $count);
    }

    public function test_can_get_users_scheduled_for_deletion(): void
    {
        // Create users with deletion requested
        User::factory()->count(2)->create()->each(function ($user) {
            $this->service->requestAccountDeletion($user);
        });

        $users = $this->service->getUsersScheduledForDeletion();

        $this->assertCount(2, $users);
        $this->assertTrue($users->every(fn($user) => $user->deletion_requested_at !== null));
    }

    public function test_data_export_excludes_sensitive_fields(): void
    {
        $data = $this->service->exportUserData($this->user);

        // Password should never be exported
        $this->assertArrayNotHasKey('password', $data['user']);
        $this->assertArrayNotHasKey('remember_token', $data['user']);
    }

    public function test_can_verify_data_portability_compliance(): void
    {
        $isCompliant = $this->service->verifyDataPortabilityCompliance($this->user);

        $this->assertTrue($isCompliant);
    }

    public function test_handles_user_with_no_data_gracefully(): void
    {
        // Create minimal user
        $minimalUser = User::factory()->create([
            'email' => 'minimal@example.com',
        ]);

        $data = $this->service->exportUserData($minimalUser);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('user', $data);
        $this->assertEquals('minimal@example.com', $data['user']['email']);
    }
}
