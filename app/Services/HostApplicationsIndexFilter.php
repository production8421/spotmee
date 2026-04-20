<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

final class HostApplicationsIndexFilter
{
    /**
     * @param  Builder<\App\Models\HostApplication>  $query
     * @param  array<string, mixed>  $filters
     */
    public static function apply(Builder $query, array $filters): Builder
    {
        if (! empty($filters['q']) && is_string($filters['q'])) {
            $like = '%'.addcslashes(trim($filters['q']), '%_\\').'%';
            $query->where(function (Builder $sub) use ($like): void {
                $sub->where('full_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('city', 'like', $like);
            });
        }

        if (! empty($filters['status']) && is_string($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['city']) && is_string($filters['city'])) {
            $like = '%'.addcslashes(trim($filters['city']), '%_\\').'%';
            $query->where('city', 'like', $like);
        }

        if (! empty($filters['state']) && is_string($filters['state'])) {
            $like = '%'.addcslashes(trim($filters['state']), '%_\\').'%';
            $query->where('state', 'like', $like);
        }

        if (! empty($filters['submitted_from'])) {
            $query->whereDate('created_at', '>=', $filters['submitted_from']);
        }

        if (! empty($filters['submitted_to'])) {
            $query->whereDate('created_at', '<=', $filters['submitted_to']);
        }

        if (! empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        return $query;
    }
}
