<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FrontendSectionController;
use App\Http\Controllers\Admin\GymBookingController;
use App\Http\Controllers\Admin\GymListingController;
use App\Http\Controllers\Admin\HostApplicationController as AdminHostApplicationController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Host\GymListingController as HostGymListingController;
use App\Http\Controllers\Host\HostApplicationController as GuestHostApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicGymBookingCancellationController;
use App\Http\Controllers\PublicGymBookingController;
use App\Http\Controllers\PublicGymController;
use App\Http\Controllers\PublicGymReviewController;
use App\Http\Controllers\SubscriberGymBookingController;
use App\Http\Controllers\WebContactController;
use App\Models\ApplicationSetting;
use App\Models\GymListing;
use App\Support\GymListingSearch;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('host/apply', [GuestHostApplicationController::class, 'intro'])->name('host.apply');
Route::post('host/apply/begin', [GuestHostApplicationController::class, 'begin'])
    ->middleware('throttle:20,1')
    ->name('host.apply.begin');
Route::get('host/apply/create', [GuestHostApplicationController::class, 'create'])->name('host.apply.create');
Route::get('host/apply/submitted', [GuestHostApplicationController::class, 'submitted'])->name('host.apply.submitted');
Route::post('host/apply', [GuestHostApplicationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('host.apply.store');

require __DIR__.'/auth.php';

Route::redirect('/', '/login', 302)->name('home');
Route::redirect('/site', '/login', 302)->name('site.home');

// Web Routes
Route::get(
    '/',
    fn () => view('web.home.index', [
        'isHome' => true,
        'settings' => ApplicationSetting::instance(),
    ])
)->name('home');

Route::get(
    '/find-a-gym',
    function (Request $request) {
        $searchBy = trim((string) $request->query('searchby', ''));
        $city = trim((string) $request->query('city', ''));
        $selectedService = trim((string) $request->query('service', ''));

        if ($searchBy === '' && $city === '' && $selectedService === '') {
            return view('web.find-a-gym.location-directory');
        }

        if (! Schema::hasTable((new GymListing)->getTable())) {
            $page = max(1, (int) $request->query('page', 1));

            return view('web.find-a-gym.gym-list', [
                'state' => '',
                'stateLabel' => __('All States'),
                'selectedService' => $selectedService,
                'searchBy' => $searchBy,
                'searchCity' => $city,
                'listings' => new LengthAwarePaginator([], 0, 12, $page, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
            ]);
        }

        $serviceAliases = [
            'boxing' => ['boxing'],
            'yoga' => ['yoga'],
            'crossfit' => ['crossfit', 'fitness', 'fitness_class'],
            'personal_training' => ['personal_training', 'personal-training'],
            'cardio' => ['cardio'],
            'group_classes' => ['group_classes', 'group_class'],
        ];

        $redirectState = GymListingSearch::redirectStateCodeIfAbbrev($searchBy, $city);
        if ($redirectState !== null) {
            return redirect()->route('find-a-gym.state', array_filter([
                'state' => $redirectState,
                'service' => $selectedService !== '' ? $selectedService : null,
            ]));
        }

        $query = GymListing::query()->where('is_published', true);

        GymListingSearch::applyLocationFilters($query, $searchBy, $city);

        if ($selectedService !== '') {
            $aliases = $serviceAliases[$selectedService] ?? [$selectedService];
            $query->where(function ($sub) use ($aliases): void {
                foreach ($aliases as $alias) {
                    $sub->orWhereJsonContains('service_options', $alias)
                        ->orWhere('service_options', 'like', '%"'.addcslashes($alias, '%_\\').'"%');
                }
            });
        }

        $locationQuery = trim($searchBy) !== '' ? trim($searchBy) : trim($city);

        return view('web.find-a-gym.gym-list', [
            'state' => '',
            'stateLabel' => $locationQuery !== '' ? __('Search results') : __('All States'),
            'locationQuery' => $locationQuery,
            'selectedService' => $selectedService,
            'searchBy' => $searchBy,
            'searchCity' => $city,
            'listings' => $query
                ->withReviewAggregates()
                ->orderByDesc('id')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }
)->name('find-a-gym');

Route::get(
    '/find-a-gym/state/{state}',
    function (Request $request, string $state) {
        $selectedState = strtoupper($state);
        $searchBy = trim((string) $request->query('searchby', ''));
        $city = trim((string) $request->query('city', ''));
        $selectedService = trim((string) $request->query('service', ''));

        if ($searchBy !== '' || $city !== '') {
            return redirect()->route('find-a-gym', array_filter([
                'searchby' => $searchBy !== '' ? $searchBy : null,
                'city' => $searchBy === '' && $city !== '' ? $city : null,
                'service' => $selectedService !== '' ? $selectedService : null,
            ]));
        }

        if (! Schema::hasTable((new GymListing)->getTable())) {
            $page = max(1, (int) $request->query('page', 1));

            return view('web.find-a-gym.gym-list', [
                'state' => $selectedState,
                'selectedService' => $selectedService,
                'searchBy' => $searchBy,
                'searchCity' => $city,
                'listings' => new LengthAwarePaginator([], 0, 12, $page, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]),
            ]);
        }

        $serviceAliases = [
            'boxing' => ['boxing'],
            'yoga' => ['yoga'],
            'crossfit' => ['crossfit', 'fitness', 'fitness_class'],
            'personal_training' => ['personal_training', 'personal-training'],
            'cardio' => ['cardio'],
            'group_classes' => ['group_classes', 'group_class'],
        ];

        $query = GymListing::query()
            ->where('is_published', true)
            ->whereRaw('UPPER(state) = ?', [$selectedState]);

        if ($selectedService !== '') {
            $aliases = $serviceAliases[$selectedService] ?? [$selectedService];
            $query->where(function ($sub) use ($aliases): void {
                foreach ($aliases as $alias) {
                    $sub->orWhereJsonContains('service_options', $alias)
                        ->orWhere('service_options', 'like', '%"'.addcslashes($alias, '%_\\').'"%');
                }
            });
        }

        return view('web.find-a-gym.gym-list', [
            'state' => $selectedState,
            'stateLabel' => config('gym_listing.states.'.$selectedState, $selectedState),
            'locationQuery' => '',
            'selectedService' => $selectedService,
            'searchBy' => $searchBy,
            'searchCity' => $city,
            'listings' => $query
                ->withReviewAggregates()
                ->orderByDesc('id')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }
)->where('state', '[A-Za-z]{2}')->name('find-a-gym.state');

Route::get('/gyms/{slug}', [PublicGymController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.show');

Route::post('/gyms/{slug}/reviews', [PublicGymReviewController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1'])
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.reviews.store');

Route::get('/gyms/{slug}/bookings/blocked', [PublicGymBookingController::class, 'blockedTimes'])
    ->middleware('throttle:120,1')
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.bookings.blocked');

Route::post('/gyms/{slug}/bookings/quote', [PublicGymBookingController::class, 'quote'])
    ->middleware('throttle:90,1')
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.bookings.quote');

Route::post('/gyms/{slug}/bookings', [PublicGymBookingController::class, 'store'])
    ->middleware('throttle:15,1')
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.bookings.store');

Route::post('/gyms/{slug}/bookings/payment-intent', [PublicGymBookingController::class, 'createPaymentIntent'])
    ->middleware('throttle:20,1')
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.bookings.payment-intent');

Route::post('/gyms/{slug}/bookings/confirm-payment', [PublicGymBookingController::class, 'confirmPayment'])
    ->middleware('throttle:20,1')
    ->where('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*')
    ->name('gym.bookings.confirm-payment');

Route::get('/bookings/{booking}/cancel', PublicGymBookingCancellationController::class)
    ->middleware(['signed', 'throttle:30,1'])
    ->name('public.gym-bookings.cancel');

Route::get(
    '/about',
    fn () => view('web.about.index')
)->name('about');

Route::get(
    '/become-a-host',
    fn () => view('web.become-a-host.index')
)->name('become-a-host');

Route::get(
    '/how-it-works',
    fn () => view('web.how-it-works.index')
)->name('how-it-works');

Route::get('/contact', [WebContactController::class, 'index'])->name('contact');
Route::post('/contact', [WebContactController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('contact.store');

Route::get(
    '/faq',
    fn () => view('web.faq.index')
)->name('faq');

Route::get(
    '/waiver-of-liability-host',
    fn () => view('web.legal.waiver-host')
)->name('legal.waiver-host');

Route::get(
    '/waiver-of-liability-user',
    fn () => view('web.legal.waiver-user')
)->name('legal.waiver-user');

Route::get(
    '/cancellation-policy',
    fn () => view('web.legal.cancellation-policy')
)->name('legal.cancellation-policy');

Route::get(
    '/book-now',
    fn () => view('web.book-now.index')
)->name('book-now');

Route::get(
    '/cart',
    fn () => view('web.cart.index')
)->name('cart');

Route::middleware('auth')->group(function (): void {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:'.UserRole::Subscriber->value])
        ->post('account/gym-bookings/{booking}/cancel', [SubscriberGymBookingController::class, 'cancel'])
        ->name('subscriber.gym-bookings.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.read');

    Route::middleware(['role:'.UserRole::Host->value])
        ->prefix('host')
        ->name('host.')
        ->group(function (): void {
            Route::resource('gym-listings', HostGymListingController::class);
        });

    Route::middleware(['role:'.UserRole::Administrator->value])
        ->prefix('admin')
        ->name('admin.')
        ->group(function (): void {
            Route::get('frontend/home', [FrontendSectionController::class, 'home'])->name('frontend.home');
            Route::put('frontend/home', [FrontendSectionController::class, 'updateHome'])->name('frontend.home.update');
            Route::get('frontend/how-it-works', [FrontendSectionController::class, 'howItWorks'])->name('frontend.how-it-works');
            Route::put('frontend/how-it-works', [FrontendSectionController::class, 'updateHowItWorks'])->name('frontend.how-it-works.update');
            Route::get('frontend/find-a-gym', [FrontendSectionController::class, 'findAGym'])->name('frontend.find-a-gym');
            Route::put('frontend/find-a-gym', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.find-a-gym.update');
            Route::get('frontend/become-a-host', [FrontendSectionController::class, 'becomeAHost'])->name('frontend.become-a-host');
            Route::put('frontend/become-a-host', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.become-a-host.update');
            Route::get('frontend/faq', [FrontendSectionController::class, 'faq'])->name('frontend.faq');
            Route::put('frontend/faq', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.faq.update');
            Route::get('frontend/contact', [FrontendSectionController::class, 'contact'])->name('frontend.contact');
            Route::put('frontend/contact', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.contact.update');
            Route::get('frontend/waiver-of-liability-host', [FrontendSectionController::class, 'waiverOfLiabilityHost'])->name('frontend.waiver-of-liability-host');
            Route::put('frontend/waiver-of-liability-host', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.waiver-of-liability-host.update');
            Route::get('frontend/waiver-of-liability-user', [FrontendSectionController::class, 'waiverOfLiabilityUser'])->name('frontend.waiver-of-liability-user');
            Route::put('frontend/waiver-of-liability-user', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.waiver-of-liability-user.update');
            Route::get('frontend/cancellation-policy', [FrontendSectionController::class, 'cancellationPolicy'])->name('frontend.cancellation-policy');
            Route::put('frontend/cancellation-policy', [FrontendSectionController::class, 'updateFrontendPageHero'])->name('frontend.cancellation-policy.update');
            Route::get('frontend/footer', [FrontendSectionController::class, 'footer'])->name('frontend.footer');
            Route::put('frontend/footer', [FrontendSectionController::class, 'updateFooter'])->name('frontend.footer.update');

            Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

            Route::resource('users', UserController::class)->except(['show']);

            Route::get('gym-listings/settings', [GymListingController::class, 'settingsEdit'])
                ->name('gym-listings.settings.edit');
            Route::put('gym-listings/settings', [GymListingController::class, 'settingsUpdate'])
                ->name('gym-listings.settings.update');
            Route::get('gym-listings/{gym_listing}/approve', [GymListingController::class, 'approveRedirect']);
            Route::post('gym-listings/{gym_listing}/approve', [GymListingController::class, 'approve'])
                ->name('gym-listings.approve');
            Route::get('gym-listings/{gym_listing}/unapprove', [GymListingController::class, 'unapproveRedirect']);
            Route::post('gym-listings/{gym_listing}/unapprove', [GymListingController::class, 'unapprove'])
                ->name('gym-listings.unapprove');
            Route::get('gym-listings/{gym_listing}/reject', [GymListingController::class, 'rejectRedirect']);
            Route::post('gym-listings/{gym_listing}/reject', [GymListingController::class, 'reject'])
                ->name('gym-listings.reject');
            Route::patch('gym-listings/{gym_listing}/host-tier', [GymListingController::class, 'updateHostTier'])
                ->name('gym-listings.host-tier.update');
            Route::patch('gym-listings/{gym_listing}/pt-pricing-tier', [GymListingController::class, 'updatePtPricingTier'])
                ->name('gym-listings.pt-pricing-tier.update');
            Route::patch('gym-listings/{gym_listing}/person-limit', [GymListingController::class, 'updatePersonLimit'])
                ->name('gym-listings.person-limit.update');
            Route::resource('gym-listings', GymListingController::class);

            Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])
                ->middleware('throttle:60,1')
                ->name('coupons.generate-code');
            Route::resource('coupons', CouponController::class)->except(['show']);
            Route::patch('coupons/{coupon}/toggle-active', [CouponController::class, 'toggleActive'])
                ->name('coupons.toggle-active');

            Route::get('gym-bookings', [GymBookingController::class, 'index'])
                ->name('gym-bookings.index');
            Route::get('notifications', [AdminNotificationController::class, 'index'])
                ->name('notifications.index');
            Route::delete('notifications/{notification}', [AdminNotificationController::class, 'destroy'])
                ->name('notifications.destroy');

            Route::get('media/picker-images', [MediaLibraryController::class, 'pickerImages'])
                ->middleware('throttle:60,1')
                ->name('media.picker-images');
            Route::get('media/picker-hero-assets', [MediaLibraryController::class, 'pickerHeroAssets'])
                ->middleware('throttle:60,1')
                ->name('media.picker-hero-assets');
            Route::get('media', [MediaLibraryController::class, 'index'])->name('media.index');
            Route::post('media', [MediaLibraryController::class, 'store'])
                ->middleware('throttle:20,1')
                ->name('media.store');
            Route::get('media/{media}/stream', [MediaLibraryController::class, 'stream'])
                ->name('media.stream');
            Route::delete('media/{media}', [MediaLibraryController::class, 'destroy'])
                ->name('media.destroy');

            Route::get('host-applications', [AdminHostApplicationController::class, 'index'])
                ->name('host-applications.index');
            Route::get('host-applications/{host_application}', [AdminHostApplicationController::class, 'show'])
                ->name('host-applications.show');
            Route::post('host-applications/{host_application}/approve', [AdminHostApplicationController::class, 'approve'])
                ->name('host-applications.approve');
            Route::post('host-applications/{host_application}/reject', [AdminHostApplicationController::class, 'reject'])
                ->name('host-applications.reject');
        });
});
