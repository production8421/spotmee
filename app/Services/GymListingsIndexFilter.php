<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

final class GymListingsIndexFilter
{
    /**
     * @param  Builder<\App\Models\GymListing>  $query
     * @param  array<string, mixed>  $filters
     */
    public static function apply(Builder $query, array $filters, bool $restrictToAuthHost): Builder
    {
        if ($restrictToAuthHost) {
            $query->where('user_id', auth()->id());
        } elseif (! empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (! empty($filters['q']) && is_string($filters['q'])) {
            $like = '%'.addcslashes(trim($filters['q']), '%_\\').'%';
            $query->where(function (Builder $sub) use ($like): void {
                $sub->where('name', 'like', $like)
                    ->orWhere('city', 'like', $like)
                    ->orWhere('email', 'like', $like);
            });
        }

        if (! empty($filters['city']) && is_string($filters['city'])) {
            $like = '%'.addcslashes(trim($filters['city']), '%_\\').'%';
            $query->where('city', 'like', $like);
        }

        if (! empty($filters['state']) && is_string($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        if (! empty($filters['facility_type']) && is_string($filters['facility_type'])) {
            $query->where('facility_type', $filters['facility_type']);
        }

        if (! empty($filters['workflow']) && is_string($filters['workflow'])) {
            match ($filters['workflow']) {
                'pending_approval' => $query->whereNotNull('user_id')
                    ->whereNull('approved_at')
                    ->whereNull('rejected_at'),
                'declined' => $query->whereNotNull('rejected_at')
                    ->whereNull('approved_at'),
                'approved' => $query->whereNotNull('approved_at'),
                'no_host' => $query->whereNull('user_id'),
                default => null,
            };
        }

        if (isset($filters['published']) && ($filters['published'] === '1' || $filters['published'] === '0')) {
            $query->where('is_published', $filters['published'] === '1');
        }

        return $query;
    }
}
