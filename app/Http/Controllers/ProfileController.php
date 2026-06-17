<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Enums\UserRole;
use App\Services\Users\UserProfilePhotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, UserProfilePhotoStorage $photoStorage): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($request->user()->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->hasRole(UserRole::Host->value)) {
            if ($request->boolean('remove_profile_photo')) {
                $photoStorage->delete($user->profile_photo_path);
                $user->profile_photo_path = null;
            }

            if ($request->hasFile('profile_photo')) {
                $photoStorage->delete($user->profile_photo_path);
                $user->profile_photo_path = $photoStorage->storeForUser(
                    $request->file('profile_photo'),
                    (int) $user->id,
                );
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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

        return Redirect::route('login');
    }
}
