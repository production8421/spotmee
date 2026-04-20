<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\GymListing;
use App\Models\User;
use App\Notifications\GymListingPendingApproval;
use Spatie\Permission\Models\Role;

class GymListingAdminNotifier
{
    public function notifyPendingApproval(GymListing $listing): void
    {
        if (! Role::query()->where('name', UserRole::Administrator->value)->where('guard_name', 'web')->exists()) {
            return;
        }

        User::query()
            ->role(UserRole::Administrator->value)
            ->get()
            ->each(fn (User $user) => $user->notify(new GymListingPendingApproval($listing)));
    }
}
