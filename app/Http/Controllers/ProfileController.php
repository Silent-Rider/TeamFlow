<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateAvatarRequest;
use App\Http\Requests\ProfileUpdateInfoRequest;
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

    public function updateNameAndEmail(ProfileUpdateInfoRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAvatar(ProfileUpdateAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();
        $path = $request->file('avatar')->store('avatars', 'public');

        $publicFolder = Storage::disk('public');
        if ($user->avatar && $publicFolder->exists($user->avatar)) {
            $publicFolder->delete($user->avatar);
        }

        $user->avatar = $path;
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
