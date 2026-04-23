<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Web\StoreContactMessageRequest;
use App\Models\User;
use App\Notifications\ContactMessageSubmitted;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class WebContactController extends Controller
{
    public function index(): View
    {
        return view('web.contact.index');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if (! Role::query()->where('name', UserRole::Administrator->value)->where('guard_name', 'web')->exists()) {
            return back()->withErrors([
                'contact' => __('No administrator account is available to receive contact messages right now.'),
            ])->withInput();
        }

        User::query()
            ->role(UserRole::Administrator->value)
            ->get()
            ->each(fn (User $user) => $user->notify(new ContactMessageSubmitted($payload)));

        return back()->with('status', __('Thanks for reaching out. Our team will contact you soon.'));
    }
}
