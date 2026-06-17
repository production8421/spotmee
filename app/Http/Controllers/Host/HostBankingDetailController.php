<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Host\UpdateHostBankingDetailRequest;
use App\Models\HostBankingDetail;
use App\Services\Payments\StripeHostConnectProvisioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HostBankingDetailController extends Controller
{
    public function edit(): View
    {
        $bankingDetail = auth()->user()?->hostBankingDetail;

        return view('host.banking.edit', [
            'bankingDetail' => $bankingDetail,
            'tableMissing' => ! Schema::hasTable((new HostBankingDetail)->getTable()),
        ]);
    }

    public function update(
        UpdateHostBankingDetailRequest $request,
        StripeHostConnectProvisioner $connectProvisioner,
    ): RedirectResponse {
        if (! Schema::hasTable((new HostBankingDetail)->getTable())) {
            return redirect()
                ->route('host.banking.edit')
                ->with('status', __('Banking details could not be saved. Run database migrations on this server.'));
        }

        $validated = $request->validated();
        $userId = (int) $request->user()->id;
        $user = $request->user();

        $banking = HostBankingDetail::query()->updateOrCreate(
            ['user_id' => $userId],
            [
                'account_holder_name' => $validated['account_holder_name'],
                'bank_name' => $validated['bank_name'],
                'account_type' => $validated['account_type'],
                'routing_number' => $validated['routing_number'],
                'account_number' => $validated['account_number'],
                'bank_country' => $validated['bank_country'],
                'notes' => $validated['notes'] ?? null,
            ],
        );

        $connectId = $user ? $connectProvisioner->syncForUser($user, $banking, $request->ip()) : null;

        return redirect()
            ->route('host.banking.edit')
            ->with('status', $connectId
                ? __('Banking details saved. Your payout bank account is linked for automatic transfers.')
                : __('Banking details saved. Automatic payouts will run once Stripe payout setup is complete.'));
    }
}
