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
        if ($application->isApproved() || $application->isRejected()) {
            return;
        }

        if (! $application->isPending()) {
            return;
        }

        $plainPassword = Str::password(20);

        $hostUser = DB::transaction(function () use ($application, $admin, $plainPassword) {
            return $this->provisionApprovedHost($application, $admin->id, $plainPassword);
        });

        Mail::to($hostUser->email)->send(new HostApprovedMail($hostUser, $application->fresh(), $plainPassword, $admin));
    }

    /**
     * Approve immediately after the public host application form (when site setting allows).
     *
     * @throws \RuntimeException When the application is not pending.
     */
    public function autoApproveFromRegistration(HostApplication $application): User
    {
        if (! $application->isPending()) {
            throw new \RuntimeException('Host application must be pending to auto-approve.');
        }

        $plainPassword = Str::password(20);

        $hostUser = DB::transaction(function () use ($application, $plainPassword) {
            return $this->provisionApprovedHost($application, null, $plainPassword);
        });

        Mail::to($hostUser->email)->send(new HostApprovedMail($hostUser, $application->fresh(), $plainPassword, null));

        return $hostUser;
    }

    /**
     * @return User The host user (Host role assigned).
     */
    private function provisionApprovedHost(HostApplication $application, ?int $approvedByUserId, string $plainPassword): User
    {
        $user = $this->ensureHostUser($application, $plainPassword);
        if (! $user->hasRole(UserRole::Host->value)) {
            $user->assignRole(UserRole::Host->value);
        }

        $application->forceFill([
            'user_id' => $user->id,
            'status' => HostApplicationStatus::Approved,
            'approved_at' => now(),
            'approved_by' => $approvedByUserId,
        ])->save();

        return $user->fresh();
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
