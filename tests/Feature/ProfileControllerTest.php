<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'onboarding_completed' => true,
        ]);

        Storage::fake('public');
    }

    public function test_user_can_view_own_profile(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
    }

    public function test_guest_cannot_view_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_profile_edit_page(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertSee('Edit Profile');
    }

    public function test_user_can_update_profile(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => 'Updated Name',
            'bio' => 'This is my updated bio',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'bio' => 'This is my updated bio',
        ]);
    }

    public function test_profile_update_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->put('/profile', [
            'name' => '', // Required field
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_user_can_upload_profile_photo(): void
    {
        $file = UploadedFile::fake()->image('profile.jpg', 800, 600);

        $response = $this->actingAs($this->user)->post('/profile/photos', [
            'photo' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('photos', [
            'user_id' => $this->user->id,
        ]);
    }

    public function test_photo_upload_validates_file_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf');

        $response = $this->actingAs($this->user)->post('/profile/photos', [
            'photo' => $file,
        ]);

        $response->assertSessionHasErrors(['photo']);
    }

    public function test_photo_upload_validates_file_size(): void
    {
        $file = UploadedFile::fake()->create('large.jpg', 11000); // 11MB

        $response = $this->actingAs($this->user)->post('/profile/photos', [
            'photo' => $file,
        ]);

        $response->assertSessionHasErrors(['photo']);
    }

    public function test_user_can_delete_own_photo(): void
    {
        $photo = Photo::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/profile/photos/{$photo->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('photos', [
            'id' => $photo->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_cannot_delete_other_users_photo(): void
    {
        $otherUser = User::factory()->create();
        $photo = Photo::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/profile/photos/{$photo->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('photos', [
            'id' => $photo->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_can_set_primary_photo(): void
    {
        $photo = Photo::factory()->create([
            'user_id' => $this->user->id,
            'is_primary' => false,
        ]);

        $response = $this->actingAs($this->user)->post("/profile/photos/{$photo->id}/set-primary");

        $response->assertRedirect();
        $this->assertDatabaseHas('photos', [
            'id' => $photo->id,
            'is_primary' => true,
        ]);
    }

    public function test_setting_primary_photo_unsets_other_primary(): void
    {
        $oldPrimary = Photo::factory()->create([
            'user_id' => $this->user->id,
            'is_primary' => true,
        ]);

        $newPrimary = Photo::factory()->create([
            'user_id' => $this->user->id,
            'is_primary' => false,
        ]);

        $this->actingAs($this->user)->post("/profile/photos/{$newPrimary->id}/set-primary");

        $this->assertDatabaseHas('photos', [
            'id' => $oldPrimary->id,
            'is_primary' => false,
        ]);

        $this->assertDatabaseHas('photos', [
            'id' => $newPrimary->id,
            'is_primary' => true,
        ]);
    }

    public function test_user_can_view_other_user_profile(): void
    {
        $otherUser = User::factory()->create([
            'name' => 'Jane Doe',
        ]);

        $response = $this->actingAs($this->user)->get("/profile/{$otherUser->id}");

        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
    }

    public function test_viewing_profile_creates_profile_view_record(): void
    {
        $otherUser = User::factory()->create();

        $this->actingAs($this->user)->get("/profile/{$otherUser->id}");

        $this->assertDatabaseHas('profile_views', [
            'viewer_id' => $this->user->id,
            'profile_id' => $otherUser->id,
        ]);
    }

    public function test_profile_completion_percentage_is_calculated(): void
    {
        $incompleteUser = User::factory()->create([
            'bio' => null,
            'occupation' => null,
        ]);

        $response = $this->actingAs($incompleteUser)->get('/profile');

        $response->assertStatus(200);
        $response->assertSeeText('%'); // Should show percentage
    }

    public function test_user_can_update_preferences(): void
    {
        $response = $this->actingAs($this->user)->put('/profile/preferences', [
            'min_age' => 24,
            'max_age' => 32,
            'religion' => 'hindu',
            'min_height' => 160,
            'max_height' => 180,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('preferences', [
            'user_id' => $this->user->id,
            'min_age' => 24,
            'max_age' => 32,
        ]);
    }

    public function test_preferences_validates_age_range(): void
    {
        $response = $this->actingAs($this->user)->put('/profile/preferences', [
            'min_age' => 35,
            'max_age' => 25, // Max less than min
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_user_can_view_profile_privacy_settings(): void
    {
        $response = $this->actingAs($this->user)->get('/profile/privacy');

        $response->assertStatus(200);
        $response->assertSee('Privacy Settings');
    }

    public function test_user_can_update_privacy_settings(): void
    {
        $response = $this->actingAs($this->user)->put('/profile/privacy', [
            'show_age' => false,
            'show_location' => true,
            'show_last_seen' => false,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'show_age' => false,
        ]);
    }

    public function test_profile_shows_verification_badges(): void
    {
        $verifiedUser = User::factory()->create([
            'phone_verified' => true,
            'video_verified' => true,
        ]);

        $response = $this->actingAs($this->user)->get("/profile/{$verifiedUser->id}");

        $response->assertStatus(200);
        $response->assertSee('Verified');
    }

    public function test_user_cannot_view_blocked_user_profile(): void
    {
        $blockedUser = User::factory()->create();

        // Block the user
        $this->user->blockedUsers()->attach($blockedUser->id);

        $response = $this->actingAs($this->user)->get("/profile/{$blockedUser->id}");

        $response->assertStatus(403);
    }
}
