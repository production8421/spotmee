<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexHostApplicationsRequest;
use App\Models\HostApplication;
use App\Models\User;
use App\Services\Admin\HostApplicationApprovalService;
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

        $approvalService->approve($host_application, $request->user());

        return back()->with('status', __('Host application approved. Login details were sent to the host by email.'));
    }
}
