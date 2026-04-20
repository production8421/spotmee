<?php

namespace App\Services\Dashboard;

use App\Enums\HostApplicationStatus;
use App\Enums\UserRole;
use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\GymListing;
use App\Models\HostApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class DashboardPageService
{
    /**
     * @return array{
     *     pageTitle: string,
     *     breadcrumbCurrent: string,
     *     adminStats?: array<string, mixed>,
     *     hostStats?: array<string, mixed>
     * }
     */
    public function indexPage(): array
    {
        $user = Auth::user();
        $base = [
            'pageTitle' => __('Dashboard'),
            'breadcrumbCurrent' => __('Dashboard'),
        ];

        if ($user?->hasRole(UserRole::Administrator->value)) {
            return array_merge($base, [
                'adminStats' => $this->adminDashboardStats(),
            ]);
        }

        if ($user?->hasRole(UserRole::Host->value)) {
            return array_merge($base, [
                'hostStats' => $this->hostDashboardStats($user),
            ]);
        }

        return $base;
    }

    /**
     * @return array<string, mixed>
     */
    private function adminDashboardStats(): array
    {
        $settings = ApplicationSetting::instance();

        return [
            'users_count' => User::query()->count(),
            'pending_host_applications' => HostApplication::query()
                ->where('status', HostApplicationStatus::Pending)
                ->count(),
            'gym_listings_total' => GymListing::query()->count(),
            'gym_listings_published' => GymListing::query()
                ->where('is_published', true)
                ->whereNotNull('approved_at')
                ->count(),
            'gym_listings_pending_approval' => GymListing::query()
                ->whereNotNull('user_id')
                ->whereNull('approved_at')
                ->whereNull('rejected_at')
                ->count(),
            'gym_listings_declined' => GymListing::query()
                ->whereNotNull('rejected_at')
                ->whereNull('approved_at')
                ->count(),
            'gym_bookings_count' => GymBooking::query()->count(),
            'settings_snapshot' => $this->settingsSnapshot($settings),
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
        $base = GymListing::query()->where('user_id', $user->id);

        return [
            'listings_total' => (clone $base)->count(),
            'listings_published' => (clone $base)
                ->where('is_published', true)
                ->whereNotNull('approved_at')
                ->count(),
            'listings_pending' => (clone $base)
                ->whereNull('approved_at')
                ->whereNull('rejected_at')
                ->count(),
            'listings_declined' => (clone $base)
                ->whereNotNull('rejected_at')
                ->whereNull('approved_at')
                ->count(),
        ];
    }
}
