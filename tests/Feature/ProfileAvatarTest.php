<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_update_avatar()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.png', 800, 600);

        $response = $this
            ->actingAs($user)
            ->put('/profile', [
                'avatar' => $file,
            ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('status', 'profile-avatar-updated');

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());

        $this->assertNotNull($user->fresh()->avatar);
    }

    public function test_avatar_upload_fails_with_invalid_file()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.txt', 1000);

        $response = $this
            ->actingAs($user)
            ->put('/profile', [
                'avatar' => $file,
            ]);

        $response->assertSessionHasErrors(['avatar']);

        Storage::disk('public')->assertMissing('avatars/*');
    }

    public function test_old_avatar_is_deleted_when_new_one_is_uploaded()
    {
        $oldPath = 'avatars/old_avatar.jpg';
        Storage::disk('public')->put($oldPath, 'fake content');

        $user = User::factory()->create(['avatar' => $oldPath]);

        $newFile = UploadedFile::fake()->image('new_avatar.png');

        $this->actingAs($user)
            ->put('/profile', [
                'avatar' => $newFile,
            ]);

        Storage::disk('public')->assertMissing($oldPath);

        Storage::disk('public')->assertExists('avatars/' . $newFile->hashName());
    }

    public function test_avatar_is_deleted_when_user_deletes_account()
    {
        $avatarPath = 'avatars/user_to_delete.png';
        Storage::disk('public')->put($avatarPath, 'fake content');

        $user = User::factory()->create(['avatar' => $avatarPath]);

        $this->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        Storage::disk('public')->assertMissing($avatarPath);
    }
}
