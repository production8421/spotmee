<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Coupon;
use App\Models\GymListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Coupon::class, 'coupon');
    }

    public function index(): View
    {
        $coupons = Coupon::query()
            ->withCount(['hosts', 'gymListings'])
            ->withSum([
                'gymBookings as redeemed_confirmed' => static fn ($q) => $q->where('status', 'confirmed'),
            ], 'coupon_applied_slots')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.coupons.index', [
            'coupons' => $coupons,
        ]);
    }

    public function generateCode(Request $request): JsonResponse
    {
        $except = $request->query('except');
        if ($except !== null && $except !== '') {
            $coupon = Coupon::query()->findOrFail((int) $except);
            $this->authorize('update', $coupon);

            return response()->json([
                'code' => Coupon::makeUniqueRandomCode(10, $coupon->id),
            ]);
        }

        $this->authorize('create', Coupon::class);

        return response()->json([
            'code' => Coupon::makeUniqueRandomCode(10, null),
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create', $this->couponFormOptions());
    }

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['applies_to'] = Coupon::APPLIES_FULL_BOOKING;
        $hostIds = $validated['host_ids'] ?? [];
        $gymListingIds = $validated['gym_listing_ids'] ?? [];
        unset($validated['host_ids'], $validated['gym_listing_ids']);

        DB::transaction(function () use ($validated, $hostIds, $gymListingIds): void {
            $coupon = Coupon::query()->create($validated);
            $coupon->hosts()->sync($hostIds);
            $coupon->gymListings()->sync($gymListingIds);
        });

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', __('Coupon created.'));
    }

    public function edit(Coupon $coupon): View
    {
        $coupon->load(['hosts:id,name', 'gymListings:id,name,city,state']);

        return view('admin.coupons.edit', array_merge(
            $this->couponFormOptions(),
            ['coupon' => $coupon]
        ));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $validated = $request->validated();
        $validated['applies_to'] = Coupon::APPLIES_FULL_BOOKING;
        $hostIds = $validated['host_ids'] ?? [];
        $gymListingIds = $validated['gym_listing_ids'] ?? [];
        unset($validated['host_ids'], $validated['gym_listing_ids']);

        DB::transaction(function () use ($coupon, $validated, $hostIds, $gymListingIds): void {
            $coupon->update($validated);
            $coupon->hosts()->sync($hostIds);
            $coupon->gymListings()->sync($gymListingIds);
        });

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', __('Coupon updated.'));
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', __('Coupon deleted.'));
    }

    public function toggleActive(Coupon $coupon): RedirectResponse
    {
        $this->authorize('update', $coupon);

        $wasActive = (bool) $coupon->is_active;
        $coupon->forceFill(['is_active' => ! $wasActive])->save();

        return redirect()
            ->route('admin.coupons.index')
            ->with('status', $wasActive ? __('Coupon deactivated.') : __('Coupon activated.'));
    }

    /**
     * @return array{hosts: Collection<int, User>, gymListings: Collection<int, GymListing>}
     */
    private function couponFormOptions(): array
    {
        $hosts = User::query()
            ->role(UserRole::Host->value)
            ->orderBy('name')
            ->get(['id', 'name']);

        $gymListings = GymListing::query()
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'state', 'user_id']);

        return [
            'hosts' => $hosts,
            'gymListings' => $gymListings,
        ];
    }
}
