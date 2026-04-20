<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Coupon;
use App\Models\User;

class CouponPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }

    public function view(User $user, Coupon $coupon): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }

    public function update(User $user, Coupon $coupon): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }

    public function delete(User $user, Coupon $coupon): bool
    {
        return $user->hasRole(UserRole::Administrator->value);
    }
}
