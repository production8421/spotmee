<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGymListingRequest;
use App\Http\Requests\Admin\UpdateGymListingHostTierRequest;
use App\Http\Requests\Admin\UpdateGymListingPtPricingTierRequest;
use App\Http\Requests\Admin\UpdateGymListingPersonLimitRequest;
use App\Http\Requests\Admin\UpdateGymListingRequest;
use App\Http\Requests\Admin\RejectGymListingRequest;
use App\Http\Requests\Admin\UpdateGymListingSettingsRequest;
use App\Http\Requests\GymListing\IndexGymListingsRequest;
use App\Models\ApplicationSetting;
use App\Models\GymListing;
use App\Models\User;
use App\Services\GymListingAdminNotifier;
use App\Services\GymListingHostApprovalNotifier;
use App\Services\GymListingsIndexFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GymListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(GymListing::class, 'gym_listing');
    }

    public function index(IndexGymListingsRequest $request): View
    {
        $query = GymListing::query()->with(['user.roles']);
        GymListingsIndexFilter::apply($query, $request->validated(), restrictToAuthHost: false);
        $query->orderByDesc('id');
        $settings = ApplicationSetting::instance();

        $filterHosts = User::role(UserRole::Host->value)
            ->whereHas('gymListings')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.gym-listings.index', [
            'listings' => $query->paginate(15)->withQueryString(),
            'gymRoutePrefix' => 'admin.gym-listings',
            'gymListingsIndexHeading' => __('All gym listings'),
            'gymListingsShowHostFilter' => true,
            'gymListingsShowHostTierColumn' => true,
            'ptSlotPriceByTier' => [
                'silver' => $settings->publicPtSlotCustomerPrice('silver'),
                'gold' => $settings->publicPtSlotCustomerPrice('gold'),
                'platinum' => $settings->publicPtSlotCustomerPrice('platinum'),
            ],
            'filterHosts' => $filterHosts,
            'filters' => $request->validated(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gym-listings.create', [
            'gymRoutePrefix' => 'admin.gym-listings',
        ]);
    }

    public function store(StoreGymListingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $data = collect($validated)
            ->except([
                'main_image',
                'gallery',
                'intro_video',
                'personal_training_cert',
                'personal_training_cpr_cert',
            ])
            ->all();
        $data['slug'] = GymListing::makeUniqueSlug($data['name']);
        $data['is_published'] = $request->boolean('is_published', true);
        $data['service_options'] = array_values($validated['service_options']);
        $data['amenities'] = array_values($validated['amenities']);
        $data['main_image_path'] = null;
        $data['gallery_paths'] = [];
        $data['intro_video_path'] = null;
        $data['personal_training_cert_path'] = null;
        $data['personal_training_cpr_cert_path'] = null;

        if (! $request->boolean('personal_training_available')) {
            $data['personal_training_availability'] = null;
        }

        $listing = GymListing::query()->create($data);

        $disk = Storage::disk('public');
        $listing->main_image_path = $request->file('main_image')->store("gym-listings/{$listing->id}", 'public');

        $galleryPaths = [];
        foreach ($request->file('gallery', []) ?? [] as $file) {
            $galleryPaths[] = $file->store("gym-listings/{$listing->id}/gallery", 'public');
        }
        $listing->gallery_paths = $galleryPaths;

        if ($request->hasFile('intro_video')) {
            $listing->intro_video_path = $request->file('intro_video')->store("gym-listings/{$listing->id}/video", 'public');
        }

        if ($request->boolean('personal_training_available')) {
            if ($request->hasFile('personal_training_cert')) {
                $listing->personal_training_cert_path = $request->file('personal_training_cert')
                    ->store("gym-listings/{$listing->id}/pt", 'public');
            }
            if ($request->hasFile('personal_training_cpr_cert')) {
                $listing->personal_training_cpr_cert_path = $request->file('personal_training_cpr_cert')
                    ->store("gym-listings/{$listing->id}/pt", 'public');
            }
        }

        $listing->save();

        return redirect()
            ->route('admin.gym-listings.index')
            ->with('status', __('Gym listing created.'));
    }

    public function show(GymListing $gymListing): View
    {
        return view('admin.gym-listings.show', [
            'gymListing' => $gymListing,
            'gymRoutePrefix' => 'admin.gym-listings',
        ]);
    }

    public function edit(GymListing $gymListing): View
    {
        return view('admin.gym-listings.edit', [
            'gymListing' => $gymListing,
            'gymRoutePrefix' => 'admin.gym-listings',
        ]);
    }

    public function update(UpdateGymListingRequest $request, GymListing $gymListing): RedirectResponse
    {
        $validated = $request->validated();
        $disk = Storage::disk('public');

        $remove = array_values(array_intersect(
            $gymListing->gallery_paths ?? [],
            $validated['remove_gallery'] ?? []
        ));
        foreach ($remove as $path) {
            $disk->delete($path);
        }
        $paths = array_values(array_diff($gymListing->gallery_paths ?? [], $remove));
        foreach ($request->file('gallery', []) ?? [] as $file) {
            $paths[] = $file->store("gym-listings/{$gymListing->id}/gallery", 'public');
        }

        $data = collect($validated)
            ->except([
                'main_image',
                'gallery',
                'intro_video',
                'remove_gallery',
                'remove_personal_training_cert',
                'remove_personal_training_cpr_cert',
                'personal_training_cert',
                'personal_training_cpr_cert',
            ])
            ->all();
        $data['is_published'] = $request->boolean('is_published', false);
        $data['service_options'] = array_values($validated['service_options']);
        $data['amenities'] = array_values($validated['amenities']);
        $data['gallery_paths'] = $paths;

        $notifyHostOfApproval = $gymListing->user_id !== null
            && $gymListing->approved_at === null
            && $data['is_published'];

        if ($notifyHostOfApproval) {
            $data['approved_at'] = now();
            $data['rejected_at'] = null;
            $data['rejection_message'] = null;
        }

        if ($gymListing->name !== $data['name']) {
            $data['slug'] = GymListing::makeUniqueSlug($data['name'], $gymListing->id);
        }

        if ($request->hasFile('main_image')) {
            if ($gymListing->main_image_path) {
                $disk->delete($gymListing->main_image_path);
            }
            $data['main_image_path'] = $request->file('main_image')->store("gym-listings/{$gymListing->id}", 'public');
        }

        if ($request->hasFile('intro_video')) {
            if ($gymListing->intro_video_path) {
                $disk->delete($gymListing->intro_video_path);
            }
            $data['intro_video_path'] = $request->file('intro_video')->store("gym-listings/{$gymListing->id}/video", 'public');
        }

        if (! $request->boolean('personal_training_available')) {
            $data['personal_training_available'] = false;
            $data['personal_training_availability'] = null;
            if ($gymListing->personal_training_cert_path) {
                $disk->delete($gymListing->personal_training_cert_path);
            }
            if ($gymListing->personal_training_cpr_cert_path) {
                $disk->delete($gymListing->personal_training_cpr_cert_path);
            }
            $data['personal_training_cert_path'] = null;
            $data['personal_training_cpr_cert_path'] = null;
        } else {
            if ($request->boolean('remove_personal_training_cert') && $gymListing->personal_training_cert_path) {
                $disk->delete($gymListing->personal_training_cert_path);
                $data['personal_training_cert_path'] = null;
            }
            if ($request->hasFile('personal_training_cert')) {
                if ($gymListing->personal_training_cert_path) {
                    $disk->delete($gymListing->personal_training_cert_path);
                }
                $data['personal_training_cert_path'] = $request->file('personal_training_cert')
                    ->store("gym-listings/{$gymListing->id}/pt", 'public');
            }
            if ($request->boolean('remove_personal_training_cpr_cert') && $gymListing->personal_training_cpr_cert_path) {
                $disk->delete($gymListing->personal_training_cpr_cert_path);
                $data['personal_training_cpr_cert_path'] = null;
            }
            if ($request->hasFile('personal_training_cpr_cert')) {
                if ($gymListing->personal_training_cpr_cert_path) {
                    $disk->delete($gymListing->personal_training_cpr_cert_path);
                }
                $data['personal_training_cpr_cert_path'] = $request->file('personal_training_cpr_cert')
                    ->store("gym-listings/{$gymListing->id}/pt", 'public');
            }
        }

        $gymListing->update($data);

        if ($notifyHostOfApproval) {
            app(GymListingHostApprovalNotifier::class)->notify($gymListing->fresh());
        }

        return redirect()
            ->route('admin.gym-listings.index')
            ->with('status', __('Gym listing updated.'));
    }

    public function destroy(GymListing $gymListing): RedirectResponse
    {
        $gymListing->delete();

        return redirect()
            ->route('admin.gym-listings.index')
            ->with('status', __('Gym listing deleted.'));
    }

    /**
     * Browsers and some clients request GET on /approve; approval itself must stay POST (CSRF + idempotent policy).
     */
    public function approveRedirect(GymListing $gymListing): RedirectResponse
    {
        $this->authorize('view', $gymListing);

        $redirect = redirect()->route('admin.gym-listings.show', $gymListing);

        if ($gymListing->pendingHostApproval()) {
            return $redirect->with(
                'status',
                __('Use “Approve & publish” on this page to confirm — that action uses a secure form submission.')
            );
        }

        return $redirect;
    }

    public function approve(GymListing $gymListing): RedirectResponse
    {
        $this->authorize('approve', $gymListing);

        $gymListing->update([
            'is_published' => true,
            'approved_at' => now(),
            'rejected_at' => null,
            'rejection_message' => null,
        ]);

        app(GymListingHostApprovalNotifier::class)->notify($gymListing->fresh());

        return redirect()
            ->route('admin.gym-listings.show', $gymListing)
            ->with('status', __('Gym listing approved and published.'));
    }

    public function unapproveRedirect(GymListing $gymListing): RedirectResponse
    {
        $this->authorize('view', $gymListing);

        $redirect = redirect()->route('admin.gym-listings.show', $gymListing);

        if ($gymListing->approvedForHost()) {
            return $redirect->with(
                'status',
                __('Use “Revoke approval” on this page to confirm — that action uses a secure form submission.')
            );
        }

        return $redirect;
    }

    public function unapprove(GymListing $gymListing): RedirectResponse
    {
        $this->authorize('unapprove', $gymListing);

        $gymListing->update([
            'is_published' => false,
            'approved_at' => null,
            'rejected_at' => null,
            'rejection_message' => null,
        ]);

        $fresh = $gymListing->fresh();
        app(GymListingHostApprovalNotifier::class)->notifyUnapproved($fresh);
        app(GymListingAdminNotifier::class)->notifyPendingApproval($fresh);

        return redirect()
            ->route('admin.gym-listings.show', $gymListing)
            ->with('status', __('Approval revoked. The listing is unpublished and pending review again.'));
    }

    public function rejectRedirect(GymListing $gymListing): RedirectResponse
    {
        $this->authorize('view', $gymListing);

        $redirect = redirect()->route('admin.gym-listings.show', $gymListing);

        if ($gymListing->pendingHostApproval()) {
            return $redirect->with(
                'status',
                __('Use “Decline” on this page to confirm — that action uses a secure form submission.')
            );
        }

        return $redirect;
    }

    public function reject(RejectGymListingRequest $request, GymListing $gymListing): RedirectResponse
    {
        $this->authorize('reject', $gymListing);

        $message = $request->validated()['rejection_message'] ?? null;

        $gymListing->update([
            'rejected_at' => now(),
            'is_published' => false,
            'rejection_message' => $message,
        ]);

        app(GymListingHostApprovalNotifier::class)->notifyRejected($gymListing->fresh());

        return redirect()
            ->route('admin.gym-listings.show', $gymListing)
            ->with('status', __('Listing declined. The host has been notified.'));
    }

    public function settingsEdit(): View
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Administrator->value), 403);

        return view('admin.gym-listings.settings', [
            'settings' => ApplicationSetting::instance(),
        ]);
    }

    public function updateHostTier(UpdateGymListingHostTierRequest $request, GymListing $gymListing): RedirectResponse
    {
        $this->authorize('updateHostTier', $gymListing);
        $gymListing->update([
            'host_tier' => $request->validated()['host_tier'],
        ]);

        return back()->with('status', __('Host tier updated.'));
    }

    public function updatePtPricingTier(UpdateGymListingPtPricingTierRequest $request, GymListing $gymListing): RedirectResponse
    {
        $this->authorize('updatePtPricingTier', $gymListing);
        $gymListing->update([
            'pt_pricing_tier' => $request->validated()['pt_pricing_tier'] ?? null,
        ]);

        return back()->with('status', __('Personal trainer pricing tier updated.'));
    }

    public function updatePersonLimit(UpdateGymListingPersonLimitRequest $request, GymListing $gymListing): RedirectResponse
    {
        $this->authorize('updatePersonLimit', $gymListing);
        $gymListing->update([
            'person_limit' => $request->validated()['person_limit'] ?? null,
        ]);

        return back()->with('status', __('Person limit updated.'));
    }

    public function settingsUpdate(UpdateGymListingSettingsRequest $request): RedirectResponse
    {
        $settings = ApplicationSetting::instance();
        $validated = $request->validated();

        foreach ([
            'stripe_test_secret_key',
            'stripe_live_secret_key',
            'webhook_booking_completed_secret',
            'webhook_booking_cancelled_secret',
        ] as $key) {
            $val = $validated[$key] ?? null;
            if (! is_string($val) || trim($val) === '') {
                unset($validated[$key]);
            }
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()
            ->route('admin.gym-listings.settings.edit')
            ->with('status', __('Gym listings settings saved.'));
    }
}
