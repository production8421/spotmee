<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(Request $request): View
    {
        if (! Schema::hasTable('notifications')) {
            $notifications = new LengthAwarePaginator(
                items: [],
                total: 0,
                perPage: 30,
                currentPage: 1,
                options: ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.notifications.index', [
                'notifications' => $notifications,
            ]);
        }

        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function destroy(Request $request, string $notification): RedirectResponse
    {
        if (! Schema::hasTable('notifications')) {
            return back()->with('status', __('Notification deleted.'));
        }

        $model = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $model->delete();

        return back()->with('status', __('Notification deleted.'));
    }
}
