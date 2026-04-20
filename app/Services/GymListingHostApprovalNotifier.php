<?php

namespace App\Services;

use App\Models\GymListing;
use App\Notifications\GymListingApproved;
use App\Notifications\GymListingRejected;
use App\Notifications\GymListingUnapproved;

class GymListingHostApprovalNotifier
{
    public function notify(GymListing $listing): void
    {
        $listing->loadMissing('user');
        $user = $listing->user;
        if ($user === null) {
            return;
        }

        $user->notify(new GymListingApproved($listing));
    }

    public function notifyUnapproved(GymListing $listing): void
    {
        $listing->loadMissing('user');
        $user = $listing->user;
        if ($user === null) {
            return;
        }

        $user->notify(new GymListingUnapproved($listing));
    }

    public function notifyRejected(GymListing $listing): void
    {
        $listing->loadMissing('user');
        $user = $listing->user;
        if ($user === null) {
            return;
        }

        $user->notify(new GymListingRejected($listing));
    }
}
