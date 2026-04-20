<?php

namespace App\Services\Admin;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Mail\HostApprovedMail;
use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class HostApplicationApprovalService
{
    public function approve(HostApplication $application, User $admin): void
    {
        if ($application->isApproved()) {
            return;
        }

        $plainPassword = Str::password(20);

        $hostUser = DB::transaction(function () use ($application, $admin, $plainPassword) {
            $user = $this->ensureHostUser($application, $plainPassword);

            $user->assignRole(UserRole::Host->value);

            $application->forceFill([
                'user_id' => $user->id,
                'status' => HostApplicationStatus::Approved,
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ])->save();

            return $user->fresh();
        });

        Mail::to($hostUser->email)->send(new HostApprovedMail($hostUser, $application->fresh(), $plainPassword, $admin));
    }

    private function ensureHostUser(HostApplication $application, string $plainPassword): User
    {
        if ($application->user_id !== null) {
            $user = User::query()->findOrFail($application->user_id);
            $user->update([
                'name' => $application->full_name,
                'password' => $plainPassword,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);

            return $user->fresh();
        }

        $existing = User::query()->where('email', $application->email)->first();
        if ($existing !== null) {
            $existing->update([
                'name' => $application->full_name,
                'password' => $plainPassword,
                'email_verified_at' => $existing->email_verified_at ?? now(),
            ]);

            return $existing->fresh();
        }

        return User::query()->create([
            'name' => $application->full_name,
            'email' => $application->email,
            'password' => $plainPassword,
            'email_verified_at' => now(),
        ]);
    }
}
