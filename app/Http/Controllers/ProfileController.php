<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileUpdateAvatarRequest;
use App\Http\Requests\Profile\ProfileUpdateInfoRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateInfo(ProfileUpdateInfoRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-info-updated');
    }

    public function updateAvatar(ProfileUpdateAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();
        $path = $request->file('avatar')->store('avatars', 'public');

        $this->deleteUserAvatar($user);

        $user->avatar = $path;
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-avatar-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $this->deleteUserAvatar($user);

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function deleteUserAvatar(User $user): void
    {
        $publicFolder = Storage::disk('public');
        if ($user->avatar && $publicFolder->exists($user->avatar)) {
            $publicFolder->delete($user->avatar);
        }
    }
}
