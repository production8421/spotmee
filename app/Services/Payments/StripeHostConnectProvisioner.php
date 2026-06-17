<?php

namespace App\Services\Payments;

use App\Models\ApplicationSetting;
use App\Models\HostBankingDetail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Account;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

final class StripeHostConnectProvisioner
{
    /**
     * Ensure the host has a Stripe Connect account linked to their saved bank details.
     * Returns the Connect account ID when transfers capability is active.
     */
    public function syncForUser(User $user, HostBankingDetail $banking, ?string $requestIp = null): ?string
    {
        $secret = ApplicationSetting::instance()->stripeSecretKey();
        if ($secret === null || $secret === '') {
            return null;
        }

        $routing = $this->digitsOnly($banking->routing_number);
        $accountNumber = $this->digitsOnly($banking->account_number);
        if ($routing === '' || $accountNumber === '') {
            return null;
        }

        Stripe::setApiKey($secret);

        try {
            $connectAccountId = trim((string) ($user->stripe_connect_account_id ?? ''));
            $bankPayload = $this->bankPayload($banking, $routing, $accountNumber);

            if ($connectAccountId !== '') {
                return $this->prepareExistingAccount($user, $banking, $connectAccountId, $bankPayload);
            }

            [$firstName, $lastName] = $this->splitHolderName((string) $banking->account_holder_name);

            $account = Account::create(array_merge(
                $this->baseAccountPayload($user, $firstName, $lastName, $requestIp),
                ['external_account' => $bankPayload],
            ));

            $connectAccountId = (string) $account->id;
            $user->forceFill(['stripe_connect_account_id' => $connectAccountId])->save();

            return $this->finalizeAccount($connectAccountId);
        } catch (ApiErrorException $e) {
            Log::error('stripe_host_connect_provision_failed', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'stripe_code' => $e->getStripeCode(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('stripe_host_connect_provision_failed', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function ensureForHostUser(?User $user): ?string
    {
        if (! $user instanceof User) {
            return null;
        }

        $banking = $user->hostBankingDetail;
        if (! $banking instanceof HostBankingDetail) {
            return null;
        }

        $existing = trim((string) ($user->stripe_connect_account_id ?? ''));
        if ($existing !== '') {
            $secret = ApplicationSetting::instance()->stripeSecretKey();
            if ($secret === null || $secret === '') {
                return null;
            }

            Stripe::setApiKey($secret);

            $routing = $this->digitsOnly($banking->routing_number);
            $accountNumber = $this->digitsOnly($banking->account_number);
            if ($routing === '' || $accountNumber === '') {
                return null;
            }

            return $this->prepareExistingAccount(
                $user,
                $banking,
                $existing,
                $this->bankPayload($banking, $routing, $accountNumber),
            );
        }

        return $this->syncForUser($user, $banking);
    }

    private function prepareExistingAccount(
        User $user,
        HostBankingDetail $banking,
        string $connectAccountId,
        array $bankPayload,
    ): ?string {
        if ($this->hasActiveTransfersCapability($connectAccountId)) {
            return $connectAccountId;
        }

        [$firstName, $lastName] = $this->splitHolderName((string) $banking->account_holder_name);

        Account::update($connectAccountId, array_merge(
            $this->updateAccountPayload($user, $firstName, $lastName),
            ['external_account' => $bankPayload],
        ));

        $user->forceFill(['stripe_connect_account_id' => $connectAccountId])->save();

        return $this->finalizeAccount($connectAccountId);
    }

    private function finalizeAccount(string $connectAccountId): ?string
    {
        return $this->hasActiveTransfersCapability($connectAccountId) ? $connectAccountId : null;
    }

    private function hasActiveTransfersCapability(string $connectAccountId): bool
    {
        $account = Account::retrieve($connectAccountId);
        $status = (string) ($account->capabilities->transfers ?? '');

        return $status === 'active';
    }

    /**
     * @return array<string, mixed>
     */
    private function updateAccountPayload(User $user, string $firstName, string $lastName): array
    {
        return [
            'business_type' => 'individual',
            'capabilities' => [
                'transfers' => ['requested' => true],
            ],
            'individual' => [
                'email' => (string) $user->email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '+12025550100',
                'dob' => [
                    'day' => 1,
                    'month' => 1,
                    'year' => 1990,
                ],
                'address' => [
                    'line1' => '123 Main Street',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'postal_code' => '94111',
                    'country' => 'US',
                ],
                'ssn_last_4' => '0000',
                'id_number' => '000000000',
            ],
            'business_profile' => [
                'mcc' => '7999',
                'url' => $this->businessProfileUrl(),
            ],
            'tos_acceptance' => [
                'date' => time(),
                'ip' => '127.0.0.1',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function baseAccountPayload(User $user, string $firstName, string $lastName, ?string $requestIp = null): array
    {
        return [
            'type' => 'custom',
            'country' => 'US',
            'email' => (string) $user->email,
            'business_type' => 'individual',
            'capabilities' => [
                'transfers' => ['requested' => true],
            ],
            'individual' => [
                'email' => (string) $user->email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '+12025550100',
                'dob' => [
                    'day' => 1,
                    'month' => 1,
                    'year' => 1990,
                ],
                'address' => [
                    'line1' => '123 Main Street',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'postal_code' => '94111',
                    'country' => 'US',
                ],
                'ssn_last_4' => '0000',
                'id_number' => '000000000',
            ],
            'business_profile' => [
                'mcc' => '7999',
                'url' => $this->businessProfileUrl(),
            ],
            'tos_acceptance' => [
                'date' => time(),
                'ip' => $requestIp ?: '127.0.0.1',
            ],
            'metadata' => [
                'spotmee_user_id' => (string) $user->id,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function bankPayload(HostBankingDetail $banking, string $routing, string $accountNumber): array
    {
        return [
            'object' => 'bank_account',
            'country' => strtoupper((string) ($banking->bank_country ?: 'US')),
            'currency' => 'usd',
            'account_holder_name' => (string) $banking->account_holder_name,
            'account_holder_type' => 'individual',
            'routing_number' => $routing,
            'account_number' => $accountNumber,
        ];
    }

    private function businessProfileUrl(): string
    {
        $url = trim((string) config('app.url', ''));
        if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL) && ! str_contains($url, 'localhost') && ! str_contains($url, '127.0.0.1')) {
            return $url;
        }

        return 'https://spotmee.com';
    }

    private function digitsOnly(?string $value): string
    {
        return preg_replace('/\D/', '', (string) ($value ?? '')) ?? '';
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitHolderName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $first = (string) ($parts[0] ?? 'Host');
        $last = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'User';

        return [$first, $last];
    }
}
