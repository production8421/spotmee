<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\GymListing;
use App\Models\User;

class GymListingPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->isAdministrator($user) || $this->isHost($user);
    }

    public function view(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) || $this->owns($user, $gymListing);
    }

    public function create(User $user): bool
    {
        return $this->isAdministrator($user) || $this->isHost($user);
    }

    public function update(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) || $this->owns($user, $gymListing);
    }

    public function delete(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) || $this->owns($user, $gymListing);
    }

    public function approve(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) && $gymListing->canBeApprovedByAdmin();
    }

    public function unapprove(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) && $gymListing->approvedForHost();
    }

    public function reject(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user) && $gymListing->pendingHostApproval();
    }

    /**
     * Assign pricing tier (Silver / Gold / Platinum) from gym listing settings — administrators only.
     */
    public function updateHostTier(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user);
    }

    /**
     * Personal trainer add-on pricing tier (Silver / Gold / Platinum) — administrators only.
     */
    public function updatePtPricingTier(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user);
    }

    /**
     * Override per-slot capacity from the admin listing table (same visibility as host tier).
     */
    public function updatePersonLimit(User $user, GymListing $gymListing): bool
    {
        return $this->isAdministrator($user);
    }

    private function isAdministrator(User $user): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }

    private function isHost(User $user): bool
    {
        return $user->hasRole(UserRole::Host->value);
    }

    private function owns(User $user, GymListing $gymListing): bool
    {
        return $gymListing->user_id !== null && (int) $gymListing->user_id === (int) $user->id;
    }
}
