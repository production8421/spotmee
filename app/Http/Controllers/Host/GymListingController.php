<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGymListingRequest;
use App\Http\Requests\Admin\UpdateGymListingRequest;
use App\Http\Requests\GymListing\IndexGymListingsRequest;
use App\Models\GymListing;
use App\Services\GymListingAdminNotifier;
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
        GymListingsIndexFilter::apply($query, $request->validated(), restrictToAuthHost: true);
        $query->orderByDesc('id');

        return view('admin.gym-listings.index', [
            'listings' => $query->paginate(15)->withQueryString(),
            'gymRoutePrefix' => 'host.gym-listings',
            'gymListingsIndexHeading' => __('My gym listings'),
            'gymListingsShowHostFilter' => false,
            'filterHosts' => collect(),
            'filters' => $request->validated(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gym-listings.create', [
            'gymRoutePrefix' => 'host.gym-listings',
            'showGymListingPublishedToggle' => false,
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
        $data['user_id'] = $request->user()->id;
        $data['slug'] = GymListing::makeUniqueSlug($data['name']);
        $data['is_published'] = false;
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

        app(GymListingAdminNotifier::class)->notifyPendingApproval($listing);

        return redirect()
            ->route('host.gym-listings.index')
            ->with('status', __('Gym listing submitted. An administrator will review and publish it.'));
    }

    public function show(GymListing $gymListing): View
    {
        return view('admin.gym-listings.show', [
            'gymListing' => $gymListing,
            'gymRoutePrefix' => 'host.gym-listings',
        ]);
    }

    public function edit(GymListing $gymListing): View
    {
        return view('admin.gym-listings.edit', [
            'gymListing' => $gymListing,
            'gymRoutePrefix' => 'host.gym-listings',
            'showGymListingPublishedToggle' => false,
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
        unset($data['user_id']);
        unset($data['is_published'], $data['approved_at'], $data['rejected_at'], $data['rejection_message']);
        $data['is_published'] = $gymListing->is_published;

        $wasRejected = $gymListing->rejected_at !== null;
        if ($wasRejected) {
            $data['rejected_at'] = null;
            $data['rejection_message'] = null;
        }
        $data['service_options'] = array_values($validated['service_options']);
        $data['amenities'] = array_values($validated['amenities']);
        $data['gallery_paths'] = $paths;

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

        if ($wasRejected) {
            app(GymListingAdminNotifier::class)->notifyPendingApproval($gymListing->fresh());
        }

        return redirect()
            ->route('host.gym-listings.index')
            ->with('status', $wasRejected
                ? __('Gym listing updated and resubmitted for administrator review.')
                : __('Gym listing updated.'));
    }

    public function destroy(GymListing $gymListing): RedirectResponse
    {
        $gymListing->delete();

        return redirect()
            ->route('host.gym-listings.index')
            ->with('status', __('Gym listing deleted.'));
    }
}
