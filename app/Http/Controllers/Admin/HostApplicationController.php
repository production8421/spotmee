<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexHostApplicationsRequest;
use App\Http\Requests\Admin\RejectHostApplicationRequest;
use App\Models\HostApplication;
use App\Models\User;
use App\Services\Admin\HostApplicationApprovalService;
use App\Services\Admin\HostApplicationRejectionService;
use App\Services\HostApplicationsIndexFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostApplicationController extends Controller
{
    public function index(IndexHostApplicationsRequest $request): View
    {
        $query = HostApplication::query()->with(['user:id,name,email']);
        HostApplicationsIndexFilter::apply($query, $request->validated());
        $query->latest();

        $filterUsers = User::query()
            ->whereHas('hostApplications')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.host-applications.index', [
            'applications' => $query->paginate(15)->withQueryString(),
            'filters' => $request->validated(),
            'filterUsers' => $filterUsers,
        ]);
    }

    public function show(HostApplication $host_application): View
    {
        $host_application->load([
            'user:id,name,email',
            'approvedBy:id,name',
        ]);

        return view('admin.host-applications.show', [
            'application' => $host_application,
        ]);
    }

    public function approve(Request $request, HostApplication $host_application, HostApplicationApprovalService $approvalService): RedirectResponse
    {
        if ($host_application->isApproved()) {
            return back()->with('status', __('This application is already approved.'));
        }

        if ($host_application->isRejected()) {
            return back()->with('status', __('This application was already rejected.'));
        }

        $approvalService->approve($host_application, $request->user());

        return back()->with('status', __('Host application approved. Login details were sent to the host by email.'));
    }

    public function reject(
        RejectHostApplicationRequest $request,
        HostApplication $host_application,
        HostApplicationRejectionService $rejectionService,
    ): RedirectResponse {
        if ($host_application->isApproved()) {
            return back()->with('status', __('This application is already approved.'));
        }

        if ($host_application->isRejected()) {
            return back()->with('status', __('This application was already rejected.'));
        }

        /** @var array{rejection_message?: string|null} $data */
        $data = $request->validated();
        $message = isset($data['rejection_message']) && is_string($data['rejection_message'])
            ? $data['rejection_message']
            : null;
        $rejectionService->reject($host_application, $request->user(), $message);

        return back()->with('status', __('Host application rejected. The applicant was notified by email if possible.'));
    }
}
