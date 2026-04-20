<?php

namespace App\Services;

use App\Enums\HostApplicationStatus;
use App\Models\GymListing;
use App\Models\HostApplication;
use App\Models\User;
use App\Notifications\GymListingApproved;
use App\Notifications\GymListingPendingApproval;
use App\Notifications\GymListingRejected;
use App\Notifications\GymListingUnapproved;
use App\Notifications\HostApplicationSubmitted;
use Illuminate\Support\Collection;

final class HeaderNotifications
{
    /**
     * Notifications shown in the header: admin actionable items (pending applications, listings awaiting approval)
     * and host alerts (e.g. gym listing approved).
     *
     * @return Collection<int, \Illuminate\Notifications\DatabaseNotification>
     */
    public static function visibleFor(User $user, int $scanLimit = 80, int $take = 20): Collection
    {
        $pendingApplicationIds = HostApplication::query()
            ->where('status', HostApplicationStatus::Pending)
            ->pluck('id');

        $pendingGymListingIds = GymListing::query()
            ->whereNotNull('user_id')
            ->whereNull('approved_at')
            ->whereNull('rejected_at')
            ->pluck('id');

        return $user->notifications()
            ->latest()
            ->limit($scanLimit)
            ->get()
            ->filter(function ($n) use ($pendingApplicationIds, $pendingGymListingIds, $user) {
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
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
                }

                if ($n->type === GymListingUnapproved::class) {
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
                }

                if ($n->type === GymListingRejected::class) {
                    $lid = $n->data['gym_listing_id'] ?? null;
                    if ($lid === null) {
                        return false;
                    }

                    return GymListing::query()->whereKey($lid)->where('user_id', $user->id)->exists();
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
