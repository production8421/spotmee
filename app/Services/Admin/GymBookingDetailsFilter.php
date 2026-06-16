<?php

namespace App\Services\Admin;

use App\Models\GymBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

final class GymBookingDetailsFilter
{
    /**
     * @return array<string, string>
     */
    public static function filtersFromRequest(Request $request): array
    {
        $read = static fn (string $key): string => trim((string) $request->input($key, $request->query($key, '')));

        return [
            'q' => $read('q'),
            'date_from' => $read('date_from'),
            'date_to' => $read('date_to'),
            'status' => $read('status'),
            'host_id' => $read('host_id'),
            'payout_status' => $read('payout_status'),
        ];
    }

    /**
     * @param  Builder<GymBooking>  $query
     * @param  array<string, string>  $filters
     * @return Builder<GymBooking>
     */
    public static function apply(Builder $query, array $filters): Builder
    {
        if ($filters['q'] !== '') {
            $like = '%'.addcslashes($filters['q'], '%_\\').'%';
            $query->where(function (Builder $sub) use ($like): void {
                $sub->where('confirmation_code', 'like', $like)
                    ->orWhere('guest_name', 'like', $like)
                    ->orWhere('guest_email', 'like', $like)
                    ->orWhereHas('gymListing', fn (Builder $gym) => $gym->where('name', 'like', $like));
            });
        }

        if ($filters['date_from'] !== '') {
            $query->whereDate('booking_date', '>=', $filters['date_from']);
        }

        if ($filters['date_to'] !== '') {
            $query->whereDate('booking_date', '<=', $filters['date_to']);
        }

        if ($filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if ($filters['host_id'] !== '') {
            $hostId = (int) $filters['host_id'];
            if ($hostId > 0) {
                $query->whereHas('gymListing', fn (Builder $gym) => $gym->where('user_id', $hostId));
            }
        }

        if ($filters['payout_status'] !== '' && Schema::hasColumn((new GymBooking)->getTable(), 'host_payout_status')) {
            $query->where('host_payout_status', $filters['payout_status']);
        }

        return $query;
    }

    /**
     * @param  array<string, string>  $filters
     * @return array<string, string>
     */
    public static function queryParams(array $filters): array
    {
        return array_filter($filters, fn (string $value): bool => $value !== '');
    }
}
