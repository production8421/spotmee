<?php

namespace App\Services\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RuntimeException;

final class UserAdminService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array{name: string, email: string, password: string, role: string}  $data
     */
    public function createUser(array $data): User
    {
        $role = $data['role'];
        unset($data['role']);

        $user = User::create($data);
        $user->syncRoles([$role]);

        return $user;
    }

    /**
     * @param  array{name: string, email: string, role: string, password?: string|null}  $data
     */
    public function updateUser(User $user, array $data): void
    {
        $role = $data['role'];
        unset($data['role']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (
            $user->hasRole(UserRole::Administrator->value)
            && $role !== UserRole::Administrator->value
            && User::role(UserRole::Administrator->value)->count() <= 1
        ) {
            throw new RuntimeException(__('Cannot demote the last administrator.'));
        }

        $user->update($data);
        $user->syncRoles([$role]);
    }

    public function deleteUser(User $actor, User $target): void
    {
        if ($actor->is($target)) {
            throw new RuntimeException(__('You cannot delete your own account.'));
        }

        if (
            $target->hasRole(UserRole::Administrator->value)
            && User::role(UserRole::Administrator->value)->count() <= 1
        ) {
            throw new RuntimeException(__('Cannot delete the last administrator.'));
        }

        $target->delete();
    }
}
