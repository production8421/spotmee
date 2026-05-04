<?php

namespace App\Http\Controllers\Host;

use App\Enums\HostApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Host\BeginHostApplicationRequest;
use App\Http\Requests\Host\StoreHostApplicationRequest;
use App\Models\ApplicationSetting;
use App\Models\HostApplication;
use App\Services\Admin\HostApplicationApprovalService;
use App\Services\Host\HostApplicationAdminNotifier;
use App\Services\Host\HostApplicationAutoApproveAdminNotifier;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function submitted(Request $request): RedirectResponse|View
    {
        if (! $request->session()->has('host_application_submitted')) {
            return redirect()->route('host.apply');
        }

        return view('host.apply-submitted');
    }

    public function store(
        StoreHostApplicationRequest $request,
        HostApplicationAdminNotifier $notifier,
        HostApplicationApprovalService $approvalService,
        HostApplicationAutoApproveAdminNotifier $autoApproveAdminNotifier,
    ): RedirectResponse {
        $data = $request->validated();
        $data['status'] = HostApplicationStatus::Pending;
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $application = HostApplication::query()->create($data);

        if (ApplicationSetting::instance()->host_registration_auto_approve) {
            try {
                $approvalService->autoApproveFromRegistration($application);
                $autoApproveAdminNotifier->notify($application->fresh());
            } catch (\Throwable $e) {
                Log::error('host_registration_auto_approve_failed', [
                    'application_id' => $application->id,
                    'message' => $e->getMessage(),
                ]);
            }
        } else {

            // add try catch to notify admin
            try {
                $notifier->notify($application);
            } catch (\Throwable $e) {
                Log::error('host_application_admin_notify_failed', [
                    'application_id' => $application->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $request->session()->forget('host_apply_terms_accepted');

        return redirect()
            ->route('host.apply.submitted')
            ->with('host_application_submitted', true);
    }
}
