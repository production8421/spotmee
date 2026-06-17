@extends('layouts.cuba.app')

@section('title', $pageTitle.' — '.config('app.name'))

@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $pageTitle }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-home"></use>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ $breadcrumbCurrent }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    @isset($adminStats)
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card o-hidden border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Users') }}</p>
                                <h4 class="mb-0 f-w-700">{{ number_format($adminStats['users_count']) }}</h4>
                            </div>
                            <div class="bg-light-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <svg class="stroke-icon text-primary" style="width: 1.25rem; height: 1.25rem; stroke: currentColor;">
                                    <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-user"></use>
                                </svg>
                            </div>
                        </div>
                        <a class="btn btn-light btn-sm mt-3" href="{{ route('admin.users.index') }}">{{ __('Manage users') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card o-hidden border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Pending host requests') }}</p>
                                <h4 class="mb-0 f-w-700">{{ number_format($adminStats['pending_host_applications']) }}</h4>
                            </div>
                            <div class="bg-light-warning rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <svg class="stroke-icon text-warning" style="width: 1.25rem; height: 1.25rem; stroke: currentColor;">
                                    <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-contact"></use>
                                </svg>
                            </div>
                        </div>
                        <a class="btn btn-light btn-sm mt-3" href="{{ route('admin.host-applications.index') }}">{{ __('Review applications') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card o-hidden border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Gym listings') }}</p>
                                <h4 class="mb-0 f-w-700">{{ number_format($adminStats['gym_listings_total']) }}</h4>
                            </div>
                            <div class="bg-light-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <svg class="stroke-icon text-secondary" style="width: 1.25rem; height: 1.25rem; stroke: currentColor;">
                                    <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-ecommerce"></use>
                                </svg>
                            </div>
                        </div>
                        <a class="btn btn-light btn-sm mt-3" href="{{ route('admin.gym-listings.index') }}">{{ __('View listings') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card o-hidden border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Gym bookings') }}</p>
                                <h4 class="mb-0 f-w-700">{{ number_format($adminStats['gym_bookings_count']) }}</h4>
                            </div>
                            <div class="bg-light-primary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <svg class="stroke-icon text-primary" style="width: 1.25rem; height: 1.25rem; stroke: currentColor;">
                                    <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-calendar"></use>
                                </svg>
                            </div>
                        </div>
                        <a class="btn btn-light btn-sm mt-3" href="{{ route('admin.gym-bookings.index') }}">{{ __('Booking listing') }}</a>
                    </div>
                </div>
            </div>
        </div>

        @php
            $analytics = $adminStats['analytics'] ?? null;
            $chartTotals = is_array($analytics) ? ($analytics['totals'] ?? []) : [];
            $chartCurrency = strtoupper((string) ($chartTotals['currency'] ?? 'USD'));
        @endphp
        @if ($analytics)
            <div class="row g-3 mb-4" id="dashboard-analytics">
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="mb-0">{{ __('Analytics') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Booking trends, revenue, and platform profit overview.') }}</p>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.payment-management.booking-details.index') }}">{{ __('View profit details') }}</a>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase">{{ __('Total revenue') }}</div>
                            <div class="fs-5 fw-semibold mb-0">{{ $chartCurrency }} {{ number_format((float) ($chartTotals['revenue'] ?? 0), 2) }}</div>
                            <div class="text-muted small mt-1">{{ __('All confirmed bookings') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase">{{ __('Total platform profit') }}</div>
                            <div class="fs-5 fw-semibold text-success mb-0">{{ $chartCurrency }} {{ number_format((float) ($chartTotals['platform_profit'] ?? 0), 2) }}</div>
                            <div class="text-muted small mt-1">{{ __('Margin') }}: {{ number_format((float) ($chartTotals['profit_margin_pct'] ?? 0), 2) }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase">{{ __('Total host payout') }}</div>
                            <div class="fs-5 fw-semibold mb-0">{{ $chartCurrency }} {{ number_format((float) ($chartTotals['host_payout'] ?? 0), 2) }}</div>
                            <div class="text-muted small mt-1">{{ __('Estimated from tier settings') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase">{{ __('Total bookings') }}</div>
                            <div class="fs-5 fw-semibold mb-0">{{ number_format($adminStats['gym_bookings_count']) }}</div>
                            <div class="text-muted small mt-1">{{ __('All statuses') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header border-bottom pb-3">
                            <h5 class="mb-0">{{ __('Bookings & revenue trend') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Last 6 months by booking date.') }}</p>
                        </div>
                        <div class="card-body">
                            <div id="dashboard-bookings-revenue-chart" style="min-height: 320px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header border-bottom pb-3">
                            <h5 class="mb-0">{{ __('Booking status') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Confirmed vs cancelled and other statuses.') }}</p>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div id="dashboard-booking-status-chart" style="min-height: 320px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card h-100">
                        <div class="card-header border-bottom pb-3">
                            <h5 class="mb-0">{{ __('Profit split trend') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Platform profit vs host payout (confirmed bookings).') }}</p>
                        </div>
                        <div class="card-body">
                            <div id="dashboard-profit-split-chart" style="min-height: 320px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header border-bottom pb-3">
                            <h5 class="mb-0">{{ __('Gym listings') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Approval and publication breakdown.') }}</p>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div id="dashboard-gym-listings-chart" style="min-height: 320px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                @if (! empty($analytics['payout_status']['values'] ?? []))
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header border-bottom pb-3">
                                <h5 class="mb-0">{{ __('Host payout status') }}</h5>
                                <p class="text-muted small mb-0 mt-1">{{ __('Stripe split status across bookings.') }}</p>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div id="dashboard-payout-status-chart" style="min-height: 320px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @push('scripts')
                <script src="{{ asset(config('cuba.assets_path').'/js/chart/apex-chart/apex-chart.js') }}"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (typeof ApexCharts === 'undefined') {
                            return;
                        }

                        const analytics = @json($analytics);
                        const currency = @json($chartCurrency);
                        const chartFont = 'Rubik, sans-serif';
                        const mutedColor = '#8D8D8D';

                        const bookingsTrend = analytics.bookings_trend || {};
                        const trendLabels = bookingsTrend.labels || [];

                        new ApexCharts(document.querySelector('#dashboard-bookings-revenue-chart'), {
                            chart: {
                                height: 320,
                                type: 'line',
                                toolbar: { show: false },
                                fontFamily: chartFont,
                            },
                            series: [
                                {
                                    name: @json(__('Bookings')),
                                    type: 'column',
                                    data: bookingsTrend.counts || [],
                                },
                                {
                                    name: @json(__('Revenue')),
                                    type: 'line',
                                    data: bookingsTrend.revenue || [],
                                },
                            ],
                            colors: ['#7366FF', '#48A3D7'],
                            stroke: { width: [0, 3], curve: 'smooth' },
                            plotOptions: {
                                bar: { columnWidth: '45%', borderRadius: 4 },
                            },
                            dataLabels: { enabled: false },
                            xaxis: {
                                categories: trendLabels,
                                labels: { style: { colors: mutedColor } },
                            },
                            yaxis: [
                                {
                                    title: { text: @json(__('Bookings')), style: { color: mutedColor } },
                                    labels: { style: { colors: mutedColor } },
                                },
                                {
                                    opposite: true,
                                    title: { text: currency, style: { color: mutedColor } },
                                    labels: {
                                        style: { colors: mutedColor },
                                        formatter: function (value) {
                                            return value.toFixed(0);
                                        },
                                    },
                                },
                            ],
                            legend: { position: 'top' },
                            grid: {
                                borderColor: '#f2f2f2',
                                strokeDashArray: 4,
                            },
                            tooltip: {
                                shared: true,
                                intersect: false,
                                y: {
                                    formatter: function (value, opts) {
                                        if (opts.seriesIndex === 1) {
                                            return currency + ' ' + Number(value).toFixed(2);
                                        }
                                        return value;
                                    },
                                },
                            },
                        }).render();

                        const bookingStatus = analytics.booking_status || {};
                        if ((bookingStatus.values || []).length > 0) {
                            new ApexCharts(document.querySelector('#dashboard-booking-status-chart'), {
                                chart: {
                                    type: 'donut',
                                    height: 320,
                                    fontFamily: chartFont,
                                },
                                series: bookingStatus.values || [],
                                labels: bookingStatus.labels || [],
                                colors: ['#7366FF', '#FFAE1A', '#FC4438', '#8D8D8D'],
                                legend: { position: 'bottom' },
                                dataLabels: { enabled: true },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '62%',
                                            labels: {
                                                show: true,
                                                total: {
                                                    show: true,
                                                    label: @json(__('Total')),
                                                },
                                            },
                                        },
                                    },
                                },
                            }).render();
                        }

                        new ApexCharts(document.querySelector('#dashboard-profit-split-chart'), {
                            chart: {
                                height: 320,
                                type: 'area',
                                toolbar: { show: false },
                                fontFamily: chartFont,
                            },
                            series: [
                                {
                                    name: @json(__('Platform profit')),
                                    data: bookingsTrend.platform_profit || [],
                                },
                                {
                                    name: @json(__('Host payout')),
                                    data: bookingsTrend.host_payout || [],
                                },
                            ],
                            colors: ['#54BA4A', '#7366FF'],
                            stroke: { curve: 'smooth', width: 2 },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 0.4,
                                    opacityFrom: 0.45,
                                    opacityTo: 0.05,
                                },
                            },
                            dataLabels: { enabled: false },
                            xaxis: {
                                categories: trendLabels,
                                labels: { style: { colors: mutedColor } },
                            },
                            yaxis: {
                                labels: {
                                    style: { colors: mutedColor },
                                    formatter: function (value) {
                                        return currency + ' ' + Number(value).toFixed(0);
                                    },
                                },
                            },
                            legend: { position: 'top' },
                            grid: {
                                borderColor: '#f2f2f2',
                                strokeDashArray: 4,
                            },
                            tooltip: {
                                y: {
                                    formatter: function (value) {
                                        return currency + ' ' + Number(value).toFixed(2);
                                    },
                                },
                            },
                        }).render();

                        const gymListings = analytics.gym_listings || {};
                        if ((gymListings.values || []).some(function (value) { return value > 0; })) {
                            new ApexCharts(document.querySelector('#dashboard-gym-listings-chart'), {
                                chart: {
                                    type: 'donut',
                                    height: 320,
                                    fontFamily: chartFont,
                                },
                                series: gymListings.values || [],
                                labels: gymListings.labels || [],
                                colors: ['#54BA4A', '#FFAE1A', '#FC4438', '#8D8D8D'],
                                legend: { position: 'bottom' },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '62%',
                                            labels: {
                                                show: true,
                                                total: {
                                                    show: true,
                                                    label: @json(__('Total')),
                                                },
                                            },
                                        },
                                    },
                                },
                            }).render();
                        }

                        const payoutStatus = analytics.payout_status || {};
                        const payoutEl = document.querySelector('#dashboard-payout-status-chart');
                        if (payoutEl && (payoutStatus.values || []).length > 0) {
                            new ApexCharts(payoutEl, {
                                chart: {
                                    type: 'donut',
                                    height: 320,
                                    fontFamily: chartFont,
                                },
                                series: payoutStatus.values || [],
                                labels: payoutStatus.labels || [],
                                colors: ['#7366FF', '#54BA4A', '#FFAE1A', '#FC4438', '#48A3D7', '#8D8D8D'],
                                legend: { position: 'bottom' },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '62%',
                                            labels: {
                                                show: true,
                                                total: {
                                                    show: true,
                                                    label: @json(__('Total')),
                                                },
                                            },
                                        },
                                    },
                                },
                            }).render();
                        }
                    });
                </script>
            @endpush
        @endif

        @php
            $recentNotes = $adminStats['recent_notifications'] ?? collect();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header border-bottom pb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h5 class="mb-0">{{ __('Recent notifications') }}</h5>
                            <p class="text-muted small mb-0 mt-1">{{ __('Includes new host registrations and other alerts for your administrator account.') }}</p>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.notifications.index') }}">{{ __('View all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @if ($recentNotes->isEmpty())
                            <p class="text-muted mb-0 px-3 py-4">{{ __('No notifications yet.') }}</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($recentNotes as $notification)
                                    @php
                                        $ndata = is_array($notification->data) ? $notification->data : [];
                                        $nTitle = (string) ($ndata['title'] ?? __('Notification'));
                                        $nBody = (string) ($ndata['body'] ?? '');
                                        $nUrl = isset($ndata['url']) && is_string($ndata['url']) && $ndata['url'] !== '' ? $ndata['url'] : null;
                                        $unread = $notification->read_at === null;
                                    @endphp
                                    <li class="list-group-item d-flex flex-wrap align-items-start justify-content-between gap-2 px-3 py-3">
                                        <div class="flex-grow-1" style="min-width: 12rem;">
                                            @if ($unread)
                                                <span class="badge badge-light-primary rounded-pill me-1">{{ __('New') }}</span>
                                            @endif
                                            <span class="fw-semibold">{{ $nTitle }}</span>
                                            @if ($nBody !== '')
                                                <div class="text-muted small mt-1">{{ $nBody }}</div>
                                            @endif
                                            <div class="text-muted small mt-1">{{ $notification->created_at?->diffForHumans() }}</div>
                                        </div>
                                        @if ($nUrl)
                                            <a class="btn btn-light btn-sm flex-shrink-0" href="{{ $nUrl }}">{{ __('Open') }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header border-bottom pb-3">
                        <h5 class="mb-0">{{ __('Gym listings overview') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('Breakdown by publication and approval state.') }}</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Published & approved') }}</span>
                                <span class="badge badge-light-success rounded-pill">{{ number_format($adminStats['gym_listings_published']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Pending administrator approval') }}</span>
                                <span class="badge badge-light-warning rounded-pill">{{ number_format($adminStats['gym_listings_pending_approval']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Declined') }}</span>
                                <span class="badge badge-light-danger rounded-pill">{{ number_format($adminStats['gym_listings_declined']) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header border-bottom pb-3">
                        <h5 class="mb-0">{{ __('Settings snapshot') }}</h5>
                        <p class="text-muted small mb-0 mt-1">{{ __('High-level status from branding, gym listing settings, and integrations.') }}</p>
                    </div>
                    <div class="card-body">
                        @php
                            $snap = $adminStats['settings_snapshot'];
                        @endphp
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Stripe') }}</span>
                                <span>
                                    @if ($snap['stripe_mode'] === 'live')
                                        <span class="badge badge-light-primary">{{ __('Live mode') }}</span>
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('Test mode') }}</span>
                                    @endif
                                    @if ($snap['stripe_keys_ready'])
                                        <span class="badge badge-light-success ms-1">{{ __('Keys OK') }}</span>
                                    @else
                                        <span class="badge badge-light-warning ms-1">{{ __('Keys incomplete') }}</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Webhooks') }}</span>
                                @if ($snap['webhooks_configured'])
                                    <span class="badge badge-light-success">{{ __('Configured') }}</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ __('Not set') }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Legal / host URLs') }}</span>
                                <span class="badge badge-light-primary rounded-pill">{{ $snap['legal_urls_filled'] }} / 6</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <span class="text-muted">{{ __('Branding logos') }}</span>
                                @if ($snap['branding_configured'])
                                    <span class="badge badge-light-success">{{ __('Custom') }}</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ __('Default') }}</span>
                                @endif
                            </li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.settings.edit') }}">{{ __('Branding settings') }}</a>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.gym-listings.settings.edit') }}">{{ __('Gym listing settings') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    @isset($hostStats)
        @php
            $hostUser = auth()->user();
        @endphp
        <div class="card mb-4">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                @if ($hostUser?->profilePhotoUrl())
                    <img
                        src="{{ $hostUser->profilePhotoUrl() }}"
                        alt="{{ $hostUser->name }}"
                        class="rounded-circle border object-fit-cover"
                        width="72"
                        height="72"
                    >
                @else
                    <div
                        class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted fw-semibold"
                        style="width: 72px; height: 72px;"
                    >
                        {{ $hostUser?->profileInitials() ?? '?' }}
                    </div>
                @endif
                <div class="flex-grow-1">
                    <h5 class="mb-1">{{ $hostUser?->name }}</h5>
                    <p class="text-muted small mb-0">{{ __('Manage your host profile photo and display name.') }}</p>
                </div>
                <a class="btn btn-outline-primary btn-sm" href="{{ route('host.profile.edit') }}">{{ __('Edit profile') }}</a>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('My listings') }}</p>
                        <h4 class="mb-0 f-w-700">{{ number_format($hostStats['listings_total']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Published') }}</p>
                        <h4 class="mb-0 f-w-700 text-success">{{ number_format($hostStats['listings_published']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Pending approval') }}</p>
                        <h4 class="mb-0 f-w-700 text-warning">{{ number_format($hostStats['listings_pending']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase f-w-500 f-12 mb-1">{{ __('Declined') }}</p>
                        <h4 class="mb-0 f-w-700 text-danger">{{ number_format($hostStats['listings_declined']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                <a class="btn btn-primary btn-sm" href="{{ route('host.gym-listings.index') }}">{{ __('Manage my gym listings') }}</a>
            </div>
        </div>
    @endisset

    @isset($subscriberBookings)
        <div id="subscriber-gym-bookings" class="mb-4">
            <div class="card mb-4">
                <div class="card-header border-bottom pb-3">
                    <h5 class="mb-0">{{ __('My gym bookings') }}</h5>
                    <p class="text-muted small mb-0 mt-1">{{ __('Upcoming sessions and your booking history. You can cancel future bookings here when allowed.') }}</p>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase f-12 mb-3">{{ __('Upcoming & current') }}</h6>
                    @if ($subscriberBookings['upcoming']->isEmpty())
                        <p class="text-muted mb-4">{{ __('You have no upcoming gym bookings.') }}</p>
                    @else
                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Gym') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Guests') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Code') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscriberBookings['upcoming'] as $booking)
                                        @php
                                            $listing = $booking->gymListing;
                                            $start = \Illuminate\Support\Carbon::parse($booking->start_time)->format('g:i A');
                                            $end = \Illuminate\Support\Carbon::parse($booking->end_time)->format('g:i A');
                                        @endphp
                                        <tr>
                                            <td>
                                                @if ($listing)
                                                    <a href="{{ route('gym.show', $listing->slug) }}">{{ $listing->name }}</a>
                                                    <div class="small text-muted">{{ $listing->city }}, {{ $listing->state }}</div>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $booking->booking_date?->format('M j, Y') }}</td>
                                            <td>{{ $start }} – {{ $end }}</td>
                                            <td>{{ $booking->number_of_persons }}</td>
                                            <td>${{ number_format((float) $booking->total_price, 2) }}</td>
                                            <td><code class="small">{{ $booking->confirmation_code }}</code></td>
                                            <td class="text-end">
                                                @if ($booking->isCancellable())
                                                    <form method="post" action="{{ route('subscriber.gym-bookings.cancel', $booking) }}" class="d-inline" onsubmit="return confirm(@json(__('Cancel this booking? Refunds follow your payment method and gym policy.')));">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('Cancel booking') }}</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">{{ __('Cancellation closed') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <h6 class="text-muted text-uppercase f-12 mb-3">{{ __('History') }}</h6>
                    @if ($subscriberBookings['history']->isEmpty())
                        <p class="text-muted mb-0">{{ __('No past or cancelled bookings yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Gym') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Time') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Code') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscriberBookings['history'] as $booking)
                                        @php
                                            $listing = $booking->gymListing;
                                            $start = \Illuminate\Support\Carbon::parse($booking->start_time)->format('g:i A');
                                            $end = \Illuminate\Support\Carbon::parse($booking->end_time)->format('g:i A');
                                        @endphp
                                        <tr>
                                            <td>
                                                @if ($listing)
                                                    <a href="{{ route('gym.show', $listing->slug) }}">{{ $listing->name }}</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $booking->booking_date?->format('M j, Y') }}</td>
                                            <td>{{ $start }} – {{ $end }}</td>
                                            <td>
                                                @if (($booking->status ?? '') === 'cancelled')
                                                    <span class="badge badge-light-secondary">{{ __('Cancelled') }}</span>
                                                @else
                                                    <span class="badge badge-light-primary">{{ __('Past') }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format((float) $booking->total_price, 2) }}</td>
                                            <td><code class="small">{{ $booking->confirmation_code }}</code></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endisset

    @if (! isset($adminStats) && ! isset($hostStats) && ! isset($subscriberBookings))
        <div class="row starter-main">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Welcome to :app', ['app' => config('app.name')]) }}</h5>
                        <p class="f-m-light mt-1 mb-0">{{ __('Use the sidebar to navigate your account.') }}</p>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0">{{ __('Your dashboard will show more here as features are enabled for your role.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
