<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\UpdateHostProfileRequest;
use App\Services\Users\UserProfilePhotoStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HostProfileController extends Controller
{
    public function edit(): View
    {
        return view('host.profile.edit', [
            'user' => auth()->user(),
            'tableMissing' => ! Schema::hasColumn(auth()->user()->getTable(), 'profile_photo_path'),
        ]);
    }

    public function update(UpdateHostProfileRequest $request, UserProfilePhotoStorage $photoStorage): RedirectResponse
    {
        if (! Schema::hasColumn($request->user()->getTable(), 'profile_photo_path')) {
            return redirect()
                ->route('host.profile.edit')
                ->with('status', __('Profile photo could not be saved. Run database migrations on this server.'));
        }

        $user = $request->user();
        $user->name = $request->validated('name');

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

        $user->save();

        return redirect()
            ->route('host.profile.edit')
            ->with('status', __('Profile updated.'));
    }
}
