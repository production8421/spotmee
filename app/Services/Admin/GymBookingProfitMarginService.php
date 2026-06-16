<?php

namespace App\Services\Admin;

use App\Models\ApplicationSetting;
use App\Models\GymBooking;
use App\Models\GymListing;

class GymBookingProfitMarginService
{
    /**
     * @return array{
     *     customer_paid: float,
     *     gym_customer_amount: float,
     *     pt_customer_amount: float,
     *     host_gym_payout: float,
     *     host_pt_payout: float,
     *     host_total_payout: float,
     *     platform_profit: float,
     *     profit_margin_pct: float,
     *     gym_commission_pct: float,
     *     pt_commission_pct: float,
     *     coupon_discount: float,
     *     slot_count: int,
     *     host_tier: string,
     *     currency: string,
     *     estimated: bool
     * }
     */
    public function forBooking(GymBooking $booking): array
    {
        $currency = strtoupper((string) ($booking->currency ?: 'USD'));
        $customerPaid = round(max(0.0, (float) ($booking->total_price ?? 0)), 2);
        $couponDiscount = round(max(0.0, (float) ($booking->coupon_discount ?? 0)), 2);
        $slotCount = count(is_array($booking->time_slots) ? $booking->time_slots : []);
        $persons = max(1, (int) $booking->number_of_persons);

        $listing = $booking->relationLoaded('gymListing')
            ? $booking->gymListing
            : $booking->gymListing()->first();

        if (! $listing instanceof GymListing) {
            return $this->emptyResult($customerPaid, $currency, $slotCount);
        }

        $settings = ApplicationSetting::instance();
        $hostTier = $listing->hostTierKey();
        $tier = match (strtolower($hostTier)) {
            'gold' => 'gold',
            'platinum' => 'platinum',
            default => 'silver',
        };

        $gymCommissionPct = (float) ($settings->{"{$tier}_tier_admin_commission_1_hour_pct"} ?? 0);
        $hostBasePerSlot = (float) ($settings->{"{$tier}_tier_price_1_hour"} ?? 0);
        $customerRatePerSlot = (float) ($settings->publicGuestTierRates($tier)['rate_1hr'] ?? 0);

        $fullGymCustomer = round($slotCount * $customerRatePerSlot * $persons, 2);
        $couponAppliedSlots = (int) ($booking->coupon_applied_slots ?? 0);

        if ($couponAppliedSlots > 0) {
            $paidSlots = max(0, $slotCount - $couponAppliedSlots);
            $gymCustomer = round($paidSlots * $customerRatePerSlot * $persons, 2);
            $hostGymPayout = round($paidSlots * $hostBasePerSlot * $persons, 2);
        } else {
            $gymCustomer = $fullGymCustomer;
            $hostGymPayout = round($slotCount * $hostBasePerSlot * $persons, 2);
        }

        $ptCustomer = $this->customerPtAmount($booking, $listing);
        $hostPtPayout = $this->hostPtBaseAmount($booking, $settings);

        if ($couponDiscount > 0 && $couponAppliedSlots === 0) {
            $subtotal = round($gymCustomer + $ptCustomer, 2);
            if ($subtotal > 0) {
                $gymShare = $gymCustomer / $subtotal;
                $ptShare = $ptCustomer / $subtotal;
                $gymCustomer = round(max(0.0, $gymCustomer - ($couponDiscount * $gymShare)), 2);
                $ptCustomer = round(max(0.0, $ptCustomer - ($couponDiscount * $ptShare)), 2);

                $fullHostGym = round($slotCount * $hostBasePerSlot * $persons, 2);
                $fullGymBefore = $fullGymCustomer;
                $hostGymPayout = $fullGymBefore > 0
                    ? round($fullHostGym * ($gymCustomer / $fullGymBefore), 2)
                    : 0.0;

                $fullHostPt = $this->hostPtBaseAmount($booking, $settings);
                $ptBefore = $this->customerPtAmount($booking, $listing);
                $hostPtPayout = $ptBefore > 0
                    ? round($fullHostPt * ($ptCustomer / $ptBefore), 2)
                    : 0.0;
            }
        }

        $hostTotalPayout = round($hostGymPayout + $hostPtPayout, 2);
        $platformProfit = round(max(0.0, $customerPaid - $hostTotalPayout), 2);
        $profitMarginPct = $customerPaid > 0
            ? round(($platformProfit / $customerPaid) * 100, 2)
            : 0.0;

        $ptCommissionPct = $this->averagePtCommissionPct($booking, $settings);

        return [
            'customer_paid' => $customerPaid,
            'gym_customer_amount' => $gymCustomer,
            'pt_customer_amount' => $ptCustomer,
            'host_gym_payout' => $hostGymPayout,
            'host_pt_payout' => $hostPtPayout,
            'host_total_payout' => $hostTotalPayout,
            'platform_profit' => $platformProfit,
            'profit_margin_pct' => $profitMarginPct,
            'gym_commission_pct' => $gymCommissionPct,
            'pt_commission_pct' => $ptCommissionPct,
            'coupon_discount' => $couponDiscount,
            'slot_count' => $slotCount,
            'host_tier' => ucfirst($tier),
            'currency' => $currency,
            'estimated' => $hostBasePerSlot <= 0 && $customerRatePerSlot <= 0,
        ];
    }

    private function customerPtAmount(GymBooking $booking, GymListing $listing): float
    {
        if ($booking->pt_free_trial) {
            return 0.0;
        }

        $guestPrices = [];
        foreach ($listing->ptTrainerLevelsForGuest() as $row) {
            $guestPrices[(string) $row['key']] = (float) $row['price_per_slot'];
        }

        $trainerPerSlot = is_array($booking->trainer_per_slot) ? $booking->trainer_per_slot : [];
        $levelsPerSlot = is_array($booking->pt_trainer_levels_per_slot) ? $booking->pt_trainer_levels_per_slot : [];
        $legacyKeys = is_array($booking->pt_trainer_level_keys) ? $booking->pt_trainer_level_keys : [];

        $sum = 0.0;
        $matchedSlots = 0;

        foreach ($trainerPerSlot as $slot => $enabled) {
            if (! $enabled) {
                continue;
            }

            $levels = $levelsPerSlot[$slot] ?? $levelsPerSlot[$this->normalizeSlotKey((string) $slot)] ?? null;
            if ($levels === null && count($trainerPerSlot) === 1 && $legacyKeys !== []) {
                $levels = $legacyKeys;
            }

            if (! is_array($levels)) {
                $levels = $levels !== null && $levels !== '' ? [(string) $levels] : [];
            }

            if ($levels === []) {
                continue;
            }

            $matchedSlots++;
            foreach ($levels as $key) {
                $key = strtolower(trim((string) $key));
                $sum += $guestPrices[$key] ?? 0.0;
            }
        }

        if ($matchedSlots === 0 && (int) ($booking->trainer_slot_count ?? 0) > 0 && $legacyKeys !== []) {
            foreach ($legacyKeys as $key) {
                $key = strtolower(trim((string) $key));
                $sum += $guestPrices[$key] ?? 0.0;
            }
        }

        return round($sum, 2);
    }

    private function hostPtBaseAmount(GymBooking $booking, ApplicationSetting $settings): float
    {
        if ($booking->pt_free_trial) {
            return 0.0;
        }

        $trainerPerSlot = is_array($booking->trainer_per_slot) ? $booking->trainer_per_slot : [];
        $levelsPerSlot = is_array($booking->pt_trainer_levels_per_slot) ? $booking->pt_trainer_levels_per_slot : [];
        $legacyKeys = is_array($booking->pt_trainer_level_keys) ? $booking->pt_trainer_level_keys : [];

        $sum = 0.0;
        $matchedSlots = 0;

        foreach ($trainerPerSlot as $slot => $enabled) {
            if (! $enabled) {
                continue;
            }

            $levels = $levelsPerSlot[$slot] ?? $levelsPerSlot[$this->normalizeSlotKey((string) $slot)] ?? null;
            if ($levels === null && count($trainerPerSlot) === 1 && $legacyKeys !== []) {
                $levels = $legacyKeys;
            }

            if (! is_array($levels)) {
                $levels = $levels !== null && $levels !== '' ? [(string) $levels] : [];
            }

            if ($levels === []) {
                continue;
            }

            $matchedSlots++;
            foreach ($levels as $key) {
                $key = strtolower(trim((string) $key));
                $base = $settings->{"pt_{$key}_price_per_slot"};
                if (is_numeric($base)) {
                    $sum += (float) $base;
                }
            }
        }

        if ($matchedSlots === 0 && (int) ($booking->trainer_slot_count ?? 0) > 0 && $legacyKeys !== []) {
            foreach ($legacyKeys as $key) {
                $key = strtolower(trim((string) $key));
                $base = $settings->{"pt_{$key}_price_per_slot"};
                if (is_numeric($base)) {
                    $sum += (float) $base;
                }
            }
        }

        return round($sum, 2);
    }

    private function averagePtCommissionPct(GymBooking $booking, ApplicationSetting $settings): float
    {
        $keys = is_array($booking->pt_trainer_level_keys) ? $booking->pt_trainer_level_keys : [];
        if ($keys === []) {
            $ptTier = 'silver';

            return (float) ($settings->{"pt_{$ptTier}_admin_commission_pct"} ?? 0);
        }

        $total = 0.0;
        $count = 0;
        foreach ($keys as $key) {
            $key = strtolower(trim((string) $key));
            $pct = $settings->{"pt_{$key}_admin_commission_pct"};
            if (is_numeric($pct)) {
                $total += (float) $pct;
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : 0.0;
    }

    private function normalizeSlotKey(string $slot): string
    {
        return str_replace(' ', '|', trim($slot));
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyResult(float $customerPaid, string $currency, int $slotCount): array
    {
        return [
            'customer_paid' => $customerPaid,
            'gym_customer_amount' => 0.0,
            'pt_customer_amount' => 0.0,
            'host_gym_payout' => 0.0,
            'host_pt_payout' => 0.0,
            'host_total_payout' => 0.0,
            'platform_profit' => $customerPaid,
            'profit_margin_pct' => $customerPaid > 0 ? 100.0 : 0.0,
            'gym_commission_pct' => 0.0,
            'pt_commission_pct' => 0.0,
            'coupon_discount' => 0.0,
            'slot_count' => $slotCount,
            'host_tier' => '—',
            'currency' => $currency,
            'estimated' => true,
        ];
    }
}
