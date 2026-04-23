<?php

namespace App\Services\Host;

use App\Enums\UserRole;
use App\Models\HostApplication;
use App\Models\User;
use App\Notifications\HostRegisteredAutoApproved;
use Spatie\Permission\Models\Role;

class HostApplicationAutoApproveAdminNotifier
{
    public function notify(HostApplication $application): void
    {
        if (! Role::query()->where('name', UserRole::Administrator->value)->where('guard_name', 'web')->exists()) {
            return;
        }

        User::query()
            ->role(UserRole::Administrator->value)
            ->get()
            ->each(fn (User $user) => $user->notify(new HostRegisteredAutoApproved($application)));
    }
}
