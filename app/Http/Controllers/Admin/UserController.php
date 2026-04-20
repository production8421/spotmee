<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class UserController extends Controller
{
    public function __construct(
        private readonly UserAdminService $userAdminService,
    ) {}

    public function index(): View
    {
        return view('admin.users.index', [
            'users' => $this->userAdminService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['password_confirmation']);
        $this->userAdminService->createUser($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User created successfully.'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        unset($data['password_confirmation']);

        try {
            $this->userAdminService->updateUser($user, $data);
        } catch (RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['role' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User updated successfully.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        try {
            $this->userAdminService->deleteUser($request->user(), $user);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['delete' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User deleted successfully.'));
    }
}
