<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole(UserRole::Administrator->value) ? true : false;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, BlogPost $blogPost): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, BlogPost $blogPost): bool
    {
        return false;
    }

    public function delete(User $user, BlogPost $blogPost): bool
    {
        return false;
    }
}
