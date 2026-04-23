<?php

namespace App\Services;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Models\GymListing;
use App\Models\HostApplication;
use App\Models\User;
use App\Notifications\ContactMessageSubmitted;
use App\Notifications\GymListingApproved;
use App\Notifications\GymListingPendingApproval;
use App\Notifications\GymListingRejected;
use App\Notifications\GymListingUnapproved;
use App\Notifications\HostApplicationSubmitted;
use App\Notifications\HostRegisteredAutoApproved;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

final class HeaderNotifications
{
    /**
     * Notifications shown in the header: admin actionable items (pending applications, listings awaiting approval)
     * and host alerts (e.g. gym listing approved).
     *
     * @return Collection<int, DatabaseNotification>
     */
    public static function visibleFor(User $user, int $scanLimit = 80, int $take = 20): Collection
    {
        if (! Schema::hasTable('notifications')) {
            return collect();
        }

        $pendingApplicationIds = collect();
        if (Schema::hasTable('host_applications') && Schema::hasColumn('host_applications', 'status')) {
            $pendingApplicationIds = HostApplication::query()
                ->where('status', HostApplicationStatus::Pending)
                ->pluck('id');
        }

        $pendingGymListingIds = collect();
        if (Schema::hasTable('gym_listings')
            && Schema::hasColumn('gym_listings', 'user_id')
            && Schema::hasColumn('gym_listings', 'approved_at')
            && Schema::hasColumn('gym_listings', 'rejected_at')) {
            $pendingGymListingIds = GymListing::query()
                ->whereNotNull('user_id')
                ->whereNull('approved_at')
                ->whereNull('rejected_at')
                ->pluck('id');
        }

        $gymListingsTableReady = Schema::hasTable('gym_listings');

        return $user->notifications()
            ->latest()
            ->limit($scanLimit)
            ->get()
            ->filter(function ($n) use ($pendingApplicationIds, $pendingGymListingIds, $gymListingsTableReady, $user) {
                if ($n->type === HostApplicationSubmitted::class) {
                    $aid = $n->data['application_id'] ?? null;
                    if ($aid === null) {
                        return false;
                    }

                    return $pendingApplicationIds->contains((int) $aid);
                }

                if ($n->type === GymListingPendingApproval::class) {
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return $pendingGymListingIds->contains((int) $lid);
                }

                if ($n->type === GymListingApproved::class) {
                    if (! $gymListingsTableReady) {
                        return false;
                    }
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
                }

                if ($n->type === GymListingUnapproved::class) {
                    if (! $gymListingsTableReady) {
                        return false;
                    }
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
                }

                if ($n->type === GymListingRejected::class) {
                    if (! $gymListingsTableReady) {
                        return false;
                    }
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
                }

                if ($n->type === ContactMessageSubmitted::class) {
                    return $user->hasRole(UserRole::Administrator->value);
                }

                if ($n->type === HostRegisteredAutoApproved::class) {
                    return $user->hasRole(UserRole::Administrator->value);
                }

                return false;
            })
            ->take($take)
            ->values();
    }

    public static function unreadVisibleCount(User $user): int
    {
        return self::visibleFor($user)->whereNull('read_at')->count();
    }
}
