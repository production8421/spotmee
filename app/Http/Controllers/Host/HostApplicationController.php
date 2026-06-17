<?php

namespace App\Http\Controllers\Host;

use App\Enums\HostApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Host\BeginHostApplicationRequest;
use App\Http\Requests\Host\StoreHostApplicationRequest;
use App\Http\Requests\Host\StoreHostBankingDetailRequest;
use App\Models\ApplicationSetting;
use App\Models\HostApplication;
use App\Models\HostBankingDetail;
use App\Services\Payments\StripeHostConnectProvisioner;
use App\Services\Users\UserProfilePhotoStorage;
use App\Models\User;
use App\Services\Admin\HostApplicationApprovalService;
use App\Services\Host\HostApplicationAdminNotifier;
use App\Services\Host\HostApplicationAutoApproveAdminNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HostApplicationController extends Controller
{
    public function intro(): View
    {
        return view('host.apply-intro');
    }

    public function begin(BeginHostApplicationRequest $request): RedirectResponse
    {
        $request->session()->put('host_apply_terms_accepted', true);

        return redirect()->route('host.apply.create');
    }

    public function create(Request $request): RedirectResponse|View
    {
        if (! $request->session()->get('host_apply_terms_accepted')) {
            return redirect()->route('host.apply');
        }

        return view('host.apply');
    }

    public function banking(Request $request): RedirectResponse|View
    {
        if ($this->resolvePendingApplicationFromSession($request) === null) {
            return redirect()->route('host.apply');
        }

        return view('host.apply-banking');
    }

    public function submitted(Request $request): RedirectResponse|View
    {
        if (! $request->session()->has('host_application_submitted')) {
            return redirect()->route('host.apply');
        }

        return view('host.apply-submitted');
    }

    public function store(StoreHostApplicationRequest $request, UserProfilePhotoStorage $photoStorage): RedirectResponse
    {
        $data = $request->validated();
        unset($data['profile_photo'], $data['remove_profile_photo']);
        $data['status'] = HostApplicationStatus::Pending;
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $application = HostApplication::query()->create($data);

        if ($request->hasFile('profile_photo')) {
            $application->forceFill([
                'profile_photo_path' => $photoStorage->storeForHostApplication(
                    $request->file('profile_photo'),
                    (int) $application->id,
                ),
            ])->save();

            if (Auth::check()) {
                $user = Auth::user();
                if ($user !== null) {
                    $photoStorage->delete($user->profile_photo_path);
                    $userPath = $photoStorage->copyToUser(
                        (string) $application->profile_photo_path,
                        (int) $user->id,
                    ) ?? $photoStorage->storeForUser($request->file('profile_photo'), (int) $user->id);
                    $user->forceFill(['profile_photo_path' => $userPath])->save();
                }
            }
        }

        $request->session()->put('host_application_id', $application->id);
        $request->session()->regenerate();

        return redirect()->route('host.apply.banking');
    }

    public function storeBanking(
        StoreHostBankingDetailRequest $request,
        HostApplicationAdminNotifier $notifier,
        HostApplicationApprovalService $approvalService,
        HostApplicationAutoApproveAdminNotifier $autoApproveAdminNotifier,
        StripeHostConnectProvisioner $connectProvisioner,
    ): RedirectResponse {
        $application = $this->resolvePendingApplicationFromSession($request);
        if ($application === null) {
            abort(403);
        }

        $validated = $request->validated();

        if (Schema::hasTable((new HostBankingDetail)->getTable())) {
            $banking = HostBankingDetail::query()->updateOrCreate(
                ['host_application_id' => $application->id],
                [
                    'user_id' => $application->user_id ?? Auth::id(),
                    'account_holder_name' => $validated['account_holder_name'],
                    'bank_name' => $validated['bank_name'],
                    'account_type' => $validated['account_type'],
                    'routing_number' => $validated['routing_number'],
                    'account_number' => $validated['account_number'],
                    'bank_country' => $validated['bank_country'],
                    'notes' => $validated['notes'] ?? null,
                ],
            );

            $hostUser = $application->user ?? Auth::user();
            if ($hostUser && $banking instanceof HostBankingDetail) {
                $connectProvisioner->syncForUser($hostUser, $banking, $request->ip());
            }
        }

        return $this->finalizeApplication(
            $request,
            $application,
            $notifier,
            $approvalService,
            $autoApproveAdminNotifier,
        );
    }

    private function finalizeApplication(
        Request $request,
        HostApplication $application,
        HostApplicationAdminNotifier $notifier,
        HostApplicationApprovalService $approvalService,
        HostApplicationAutoApproveAdminNotifier $autoApproveAdminNotifier,
    ): RedirectResponse {
        if (ApplicationSetting::instance()->host_registration_auto_approve) {
            try {
                $hostUser = $approvalService->autoApproveFromRegistration($application);
                $this->linkBankingDetailToUser($application, $hostUser);
                $autoApproveAdminNotifier->notify($application->fresh());
            } catch (\Throwable $e) {
                Log::error('host_registration_auto_approve_failed', [
                    'application_id' => $application->id,
                    'message' => $e->getMessage(),
                ]);
            }
        } else {
            try {
                $notifier->notify($application);
            } catch (\Throwable $e) {
                Log::error('host_application_admin_notify_failed', [
                    'application_id' => $application->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $request->session()->forget(['host_apply_terms_accepted', 'host_application_id']);

        return redirect()
            ->route('host.apply.submitted')
            ->with('host_application_submitted', true);
    }

    private function linkBankingDetailToUser(HostApplication $application, User $user): void
    {
        if (! Schema::hasTable((new HostBankingDetail)->getTable())) {
            return;
        }

        HostBankingDetail::query()
            ->where('host_application_id', $application->id)
            ->update(['user_id' => $user->id]);
    }

    private function resolvePendingApplicationFromSession(Request $request): ?HostApplication
    {
        if (! $request->session()->has('host_application_id')) {
            return null;
        }

        $application = HostApplication::query()->find($request->session()->get('host_application_id'));
        if ($application === null || ! $application->isPending()) {
            $request->session()->forget(['host_application_id', 'host_apply_terms_accepted']);

            return null;
        }

        if ($application->user_id !== null && Auth::id() !== $application->user_id) {
            return null;
        }

        return $application;
    }
}
