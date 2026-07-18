<?php

namespace Tests\Feature\Profile;

use App\Models\Company;
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

    public function test_user_can_update_avatar(): void
    {
        $user = $this->createUser();
        $file = UploadedFile::fake()->image('avatar.png', 800, 600);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.avatar.update'), [
                'avatar' => $file,
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'profile-avatar-updated');
        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
        $this->assertNotNull($user->fresh()->avatar);
    }

    public function test_avatar_upload_fails_with_invalid_file(): void
    {
        $user = $this->createUser();
        $file = UploadedFile::fake()->create('document.txt', 1000);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.avatar.update'), [
                'avatar' => $file,
            ]);

        $response->assertSessionHasErrors(['avatar']);
        Storage::disk('public')->assertDirectoryEmpty('avatars');
    }

    public function test_old_avatar_is_deleted_when_new_one_is_uploaded(): void
    {
        $oldPath = 'avatars/old_avatar.jpg';
        Storage::disk('public')->put($oldPath, 'fake content');
        $user = $this->createUser(['avatar' => $oldPath]);

        $newFile = UploadedFile::fake()->image('new_avatar.png');

        $this->actingAs($user)
            ->put(route('profile.avatar.update'), [
                'avatar' => $newFile,
            ]);

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists('avatars/' . $newFile->hashName());
    }

    public function test_avatar_is_deleted_when_user_deletes_account(): void
    {
        $avatarPath = 'avatars/user_to_delete.png';
        Storage::disk('public')->put($avatarPath, 'fake content');
        $user = $this->createUser([
            'avatar' => $avatarPath,
            'password' => bcrypt('password')
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        Storage::disk('public')->assertMissing($avatarPath);
    }

    private function createUser(array $attributes = []): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(array_merge(['company_id' => $company->id], $attributes));
        return $user;
    }
}
