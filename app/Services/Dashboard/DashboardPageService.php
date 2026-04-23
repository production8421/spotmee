<?php

namespace App\Services\Dashboard;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\GymListing;
use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

final class DashboardPageService
{
    /**
     * @return array{
     *     pageTitle: string,
     *     breadcrumbCurrent: string,
     *     adminStats?: array<string, mixed>,
     *     hostStats?: array<string, mixed>,
     *     subscriberBookings?: array{upcoming: Collection<int, GymBooking>, history: Collection<int, GymBooking>}
     * }
     */
    public function indexPage(): array
    {
        $user = Auth::user();
        $base = [
            'pageTitle' => __('Dashboard'),
            'breadcrumbCurrent' => __('Dashboard'),
        ];

        $subscriberBlock = [];
        if ($user?->hasRole(UserRole::Subscriber->value)) {
            $subscriberBlock['subscriberBookings'] = $this->subscriberBookings($user);
        }

        if ($user instanceof User && $user->hasRole(UserRole::Administrator->value)) {
            return array_merge($base, $subscriberBlock, [
                'adminStats' => $this->adminDashboardStats($user),
            ]);
        }

        if ($user?->hasRole(UserRole::Host->value)) {
            return array_merge($base, $subscriberBlock, [
                'hostStats' => $this->hostDashboardStats($user),
            ]);
        }

        return array_merge($base, $subscriberBlock);
    }

    /**
     * @return array{upcoming: Collection<int, GymBooking>, history: Collection<int, GymBooking>}
     */
    private function subscriberBookings(User $user): array
    {
        if (! Schema::hasTable('gym_bookings')) {
            return [
                'upcoming' => collect(),
                'history' => collect(),
            ];
        }

        $bookings = GymBooking::query()
            ->where('user_id', $user->id)
            ->with(['gymListing:id,name,slug,city,state'])
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->limit(100)
            ->get();

        $upcoming = $bookings
            ->filter(fn (GymBooking $b) => ($b->status ?? '') === 'confirmed' && $b->bookingStartAt()->isFuture())
            ->sortBy(fn (GymBooking $b) => $b->bookingStartAt())
            ->values();

        $history = $bookings
            ->reject(fn (GymBooking $b) => ($b->status ?? '') === 'confirmed' && $b->bookingStartAt()->isFuture())
            ->sortByDesc(fn (GymBooking $b) => $b->bookingStartAt())
            ->values();

        return [
            'upcoming' => $upcoming,
            'history' => $history,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return Collection<int, DatabaseNotification>
     */
    private function recentAdminNotifications(User $user): Collection
    {
        if (! Schema::hasTable('notifications')) {
            return collect();
        }

        return $user->notifications()->latest()->limit(10)->get();
    }

    private function adminDashboardStats(User $user): array
    {
        $settings = ApplicationSetting::instance();

        $pendingHostApps = 0;
        if (Schema::hasTable('host_applications') && Schema::hasColumn('host_applications', 'status')) {
            $pendingHostApps = HostApplication::query()
                ->where('status', HostApplicationStatus::Pending)
                ->count();
        }

        $gymTotal = 0;
        $gymPublished = 0;
        $gymPendingApproval = 0;
        $gymDeclined = 0;
        if (Schema::hasTable('gym_listings')) {
            $gymTotal = GymListing::query()->count();
            if (Schema::hasColumn('gym_listings', 'is_published') && Schema::hasColumn('gym_listings', 'approved_at')) {
                $gymPublished = GymListing::query()
                    ->where('is_published', true)
                    ->whereNotNull('approved_at')
                    ->count();
            }
            if (Schema::hasColumn('gym_listings', 'user_id')
                && Schema::hasColumn('gym_listings', 'approved_at')
                && Schema::hasColumn('gym_listings', 'rejected_at')) {
                $gymPendingApproval = GymListing::query()
                    ->whereNotNull('user_id')
                    ->whereNull('approved_at')
                    ->whereNull('rejected_at')
                    ->count();
            }
            if (Schema::hasColumn('gym_listings', 'rejected_at') && Schema::hasColumn('gym_listings', 'approved_at')) {
                $gymDeclined = GymListing::query()
                    ->whereNotNull('rejected_at')
                    ->whereNull('approved_at')
                    ->count();
            }
        }

        $bookingsCount = 0;
        if (Schema::hasTable('gym_bookings')) {
            $bookingsCount = GymBooking::query()->count();
        }

        return [
            'users_count' => User::query()->count(),
            'pending_host_applications' => $pendingHostApps,
            'gym_listings_total' => $gymTotal,
            'gym_listings_published' => $gymPublished,
            'gym_listings_pending_approval' => $gymPendingApproval,
            'gym_listings_declined' => $gymDeclined,
            'gym_bookings_count' => $bookingsCount,
            'settings_snapshot' => $this->settingsSnapshot($settings),
            'recent_notifications' => $this->recentAdminNotifications($user),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function settingsSnapshot(ApplicationSetting $s): array
    {
        $mode = $s->stripe_mode ?? 'test';
        $stripeReady = $mode === 'live'
            ? ($s->hasStripeLiveSecret() && filled($s->stripe_live_publishable_key))
            : ($s->hasStripeTestSecret() && filled($s->stripe_test_publishable_key));

        return [
            'stripe_mode' => $mode,
            'stripe_keys_ready' => $stripeReady,
            'webhooks_configured' => filled($s->webhook_booking_completed_url) || filled($s->webhook_booking_cancelled_url),
            'legal_urls_filled' => collect([
                $s->legal_booking_terms_url,
                $s->legal_booking_privacy_url,
                $s->legal_host_terms_url,
                $s->legal_host_privacy_url,
                $s->booking_cancel_result_url,
                $s->legal_host_registration_url,
            ])->filter()->count(),
            'branding_configured' => filled($s->header_logo_path) || filled($s->footer_logo_path),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function hostDashboardStats(User $user): array
    {
        if (! Schema::hasTable('gym_listings')) {
            return [
                'listings_total' => 0,
                'listings_published' => 0,
                'listings_pending' => 0,
                'listings_declined' => 0,
            ];
        }

        $base = GymListing::query()->where('user_id', $user->id);

        $published = 0;
        if (Schema::hasColumn('gym_listings', 'is_published') && Schema::hasColumn('gym_listings', 'approved_at')) {
            $published = (clone $base)
                ->where('is_published', true)
                ->whereNotNull('approved_at')
                ->count();
        }

        $pending = 0;
        if (Schema::hasColumn('gym_listings', 'approved_at') && Schema::hasColumn('gym_listings', 'rejected_at')) {
            $pending = (clone $base)
                ->whereNull('approved_at')
                ->whereNull('rejected_at')
                ->count();
        }

        $declined = 0;
        if (Schema::hasColumn('gym_listings', 'rejected_at') && Schema::hasColumn('gym_listings', 'approved_at')) {
            $declined = (clone $base)
                ->whereNotNull('rejected_at')
                ->whereNull('approved_at')
                ->count();
        }

        return [
            'listings_total' => (clone $base)->count(),
            'listings_published' => $published,
            'listings_pending' => $pending,
            'listings_declined' => $declined,
        ];
    }
}
