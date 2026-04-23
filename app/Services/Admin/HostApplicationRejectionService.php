<?php

namespace App\Services\Admin;

use App\Enums\HostApplicationStatus;
use App\Mail\HostApplicationRejectedApplicantMail;
use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

final class HostApplicationRejectionService
{
    public function reject(HostApplication $application, User $admin, ?string $message): void
    {
        if ($application->isApproved() || $application->isRejected()) {
            return;
        }

        if (! $application->isPending()) {
            return;
        }

        $message = is_string($message) ? trim($message) : '';
        if ($message === '') {
            $message = null;
        }

        DB::transaction(function () use ($application, $message): void {
            $application->forceFill([
                'status' => HostApplicationStatus::Rejected,
                'rejected_at' => now(),
                'rejection_message' => $message,
            ])->save();
        });

        try {
            Mail::to($application->email)->send(new HostApplicationRejectedApplicantMail($application->fresh(), $admin, $message));
        } catch (\Throwable) {
            // mail transport failures should not roll back rejection
        }
    }
}
