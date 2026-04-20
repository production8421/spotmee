@extends('layouts.web.master')

@section('title', $listing->name.' - SPOTMEE')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/gym-main-ryj.css') }}?v=1">
@endpush

@section('content')
    @php
        $facilityKey = (string) ($listing->facility_type ?? '');
        $petsKey = (string) ($listing->pets_policy ?? '');
        $checkKey = (string) ($listing->check_in_method ?? '');
        $areaLabel = config('gym_listing.area_sizes.'.((string) $listing->area_size), (string) $listing->area_size);
        $facilityLabel = config('gym_listing.facility_types.'.$facilityKey, $facilityKey);
        $petsLabel = config('gym_listing.pets_policies.'.$petsKey, $petsKey);
        $checkLabel = config('gym_listing.check_in_methods.'.$checkKey, $checkKey);
        $services = is_array($listing->service_options) ? $listing->service_options : [];
        $amenities = is_array($listing->amenities) ? $listing->amenities : [];
        $equipment = is_array($listing->equipment) ? $listing->equipment : [];
        $mainPhoto = $photos[0] ?? null;
        $galleryOnly = array_slice($photos, 1);
        $thumbPhotos = array_slice($galleryOnly, 0, 4);
        $thumbCount = count($thumbPhotos);
        $hasGalleryThumbs = $thumbCount > 0;
        $photoCount = count(array_filter($photos));
        $stateCode = strtoupper((string) $listing->state);
        $tier = $pricing['tier'] ?? 'silver';
        $tierLabel = ucfirst((string) $tier);
        $tierIconsFallback = ['silver' => '🥈', 'gold' => '🥇', 'platinum' => '💎'];
        $tierIconUrl = \App\Support\RyjOptionIcon::publicUrl($tier);
        $showPricingSection = ($pricing['rate_40min'] ?? null) !== null || ($pricing['rate_1hr'] ?? null) !== null;
    @endphp

    <main class="spotmee-main">
        <div class="ryj-gym-main-page">
            <div class="ryj-gym-header">
                <h1>{{ $listing->name }}</h1>
            </div>

            <div class="ryj-photo-gallery">
                <div class="ryj-gallery-grid{{ $hasGalleryThumbs ? ' has-thumbnails' : '' }}">
                    <div class="ryj-main-image" onclick="openPhotoModal(0)" role="button" tabindex="0">
                        @if ($mainPhoto)
                            <img src="{{ $mainPhoto }}" alt="{{ $listing->name }}">
                        @else
                            <div class="ryj-placeholder-image"><span>🏋️</span></div>
                        @endif
                        @if ($photoCount > 1)
                            <button type="button" class="ryj-show-all-btn" onclick="event.stopPropagation(); openPhotoModal(0)">
                                <i class="fas fa-images"></i> View All {{ $photoCount }} Photos
                            </button>
                        @endif
                    </div>
                    @if ($hasGalleryThumbs)
                        <div class="ryj-thumbnail-grid count-{{ $thumbCount }}">
                            @foreach ($thumbPhotos as $idx => $thumbUrl)
                                <div class="ryj-thumbnail" onclick="openPhotoModal({{ $idx + 1 }})" role="button" tabindex="0">
                                    <img src="{{ $thumbUrl }}" alt="Gallery {{ $idx + 1 }}" loading="lazy" decoding="async">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="ryj-gym-info">
                <div class="ryj-breadcrumbs">
                    <a href="{{ route('home') }}">Home</a> &gt;
                    <a href="{{ route('find-a-gym') }}">Gyms</a> &gt;
                    <a href="{{ route('find-a-gym.state', ['state' => $stateCode]) }}">{{ $stateLabel }}</a> &gt;
                    {{ $listing->name }}
                </div>

                <div class="ryj-gym-header-row">
                    <h2 class="ryj-gym-name">{{ $listing->name }}</h2>
                    <div class="ryj-header-prices">
                        @if ($slotOffers['offers_40min'] && ($pricing['rate_40min'] ?? null) !== null)
                            <div class="ryj-inline-price">
                                <span class="ryj-price-amount">${{ number_format((float) $pricing['rate_40min'], 2) }}</span>
                                <span class="ryj-price-unit">/ 40 min</span>
                            </div>
                        @endif
                        @if ($slotOffers['offers_1hr'] && ($pricing['rate_1hr'] ?? null) !== null)
                            <div class="ryj-inline-price">
                                <span class="ryj-price-amount">${{ number_format((float) $pricing['rate_1hr'], 2) }}</span>
                                <span class="ryj-price-unit">/ hour</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ryj-gym-address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $listing->address }}, {{ $listing->city }}, {{ $stateCode }} {{ $listing->postal_code }}</span>
                </div>
            </div>

            @if (filled($listing->description))
                <div class="ryj-section">
                    <h3 class="ryj-section-title">About This Space</h3>
                    <p>{!! nl2br(e($listing->description)) !!}</p>
                </div>
            @endif

            <div class="ryj-section">
                <h3 class="ryj-section-title">Facility Details</h3>
                <div class="ryj-details-grid">
                    <div class="ryj-detail-item">
                        @if ($icon = \App\Support\RyjOptionIcon::publicUrl($facilityKey))
                            <img src="{{ $icon }}" alt="" class="ryj-detail-icon">
                        @else
                            <i class="fas fa-door-open"></i>
                        @endif
                        <span class="ryj-detail-label">Room Type</span>
                        <span class="ryj-detail-value">{{ $facilityLabel !== '' ? $facilityLabel : '—' }}</span>
                    </div>
                    <div class="ryj-detail-item">
                        @if ($icon = \App\Support\RyjOptionIcon::publicUrl('area_size'))
                            <img src="{{ $icon }}" alt="" class="ryj-detail-icon">
                        @else
                            <i class="fas fa-ruler-combined"></i>
                        @endif
                        <span class="ryj-detail-label">Area Size</span>
                        <span class="ryj-detail-value">{{ $areaLabel }} sq ft</span>
                    </div>
                    <div class="ryj-detail-item">
                        @if ($icon = \App\Support\RyjOptionIcon::publicUrl($petsKey))
                            <img src="{{ $icon }}" alt="" class="ryj-detail-icon">
                        @else
                            <i class="fas fa-paw"></i>
                        @endif
                        <span class="ryj-detail-label">Pets</span>
                        <span class="ryj-detail-value">{{ $petsLabel !== '' ? $petsLabel : '—' }}</span>
                    </div>
                    <div class="ryj-detail-item">
                        @if ($icon = \App\Support\RyjOptionIcon::publicUrl($checkKey))
                            <img src="{{ $icon }}" alt="" class="ryj-detail-icon">
                        @else
                            <i class="fas fa-key"></i>
                        @endif
                        <span class="ryj-detail-label">Check-in</span>
                        <span class="ryj-detail-value">{{ $checkLabel !== '' ? $checkLabel : '—' }}</span>
                    </div>
                </div>
            </div>

            @if ($services !== [])
                <div class="ryj-section">
                    <h3 class="ryj-section-title">Services Offered</h3>
                    <div class="ryj-tags-grid">
                        @foreach ($services as $svc)
                            @php $svcKey = is_string($svc) ? $svc : ''; @endphp
                            <span class="ryj-tag ryj-tag-with-icon">
                                @if ($icon = \App\Support\RyjOptionIcon::publicUrl($svcKey))
                                    <img src="{{ $icon }}" alt="" class="ryj-tag-icon">
                                @endif
                                {{ ucwords(str_replace(['_', '-'], ' ', $svcKey)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($equipment !== [])
                <div class="ryj-section">
                    <h3 class="ryj-section-title">Equipment Available</h3>
                    <div class="ryj-equipment-list">
                        @foreach ($equipment as $item)
                            @php
                                $eqName = is_array($item) ? (string) ($item['name'] ?? '') : (string) $item;
                                $qty = is_array($item) ? (int) ($item['quantity'] ?? $item['count'] ?? 1) : 1;
                            @endphp
                            @if ($eqName !== '')
                                <div class="ryj-equipment-item">
                                    <i class="fas fa-dumbbell"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $eqName)) }}</span>
                                    <span class="ryj-quantity">x{{ $qty }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($amenities !== [])
                <div class="ryj-section">
                    <h3 class="ryj-section-title">Amenities</h3>
                    <div class="ryj-tags-grid">
                        @foreach ($amenities as $am)
                            @php $amKey = is_string($am) ? $am : ''; @endphp
                            <span class="ryj-tag amenity">
                                @if ($icon = \App\Support\RyjOptionIcon::publicUrl($amKey))
                                    <img src="{{ $icon }}" alt="" class="ryj-tag-icon">
                                @endif
                                {{ ucwords(str_replace('_', ' ', $amKey)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="ryj-section">
                <h3 class="ryj-section-title">Location</h3>
                <div class="ryj-map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>{{ $listing->address }}, {{ $listing->city }}, {{ $stateCode }} {{ $listing->postal_code }}</p>
                </div>
            </div>

            @if ($showPricingSection)
                <div class="ryj-section ryj-pricing-section">
                    <div class="ryj-pricing-container" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                        @if (($pricing['rate_40min'] ?? null) !== null)
                            <div class="ryj-price-card" style="flex: 1; min-width: 250px; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; text-align: center; background: #f9f9f9;">
                                <div class="ryj-price-header" style="margin-bottom: 15px;">
                                    <span class="ryj-tier-badge tier-{{ $tier }}" style="background: #fff; padding: 5px 10px; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        @if ($tierIconUrl)
                                            <img src="{{ $tierIconUrl }}" alt="" class="ryj-tier-icon">
                                        @else
                                            {{ $tierIconsFallback[$tier] ?? '🥈' }}
                                        @endif
                                        {{ $tierLabel }} Host
                                    </span>
                                </div>
                                <div class="ryj-price-amount" style="color: #4682B4; font-weight: bold; font-size: 2em; margin-bottom: 10px;">
                                    <span class="ryj-currency">$</span>
                                    <span class="ryj-price-value">{{ number_format((float) $pricing['rate_40min'], 2) }}</span>
                                    <span class="ryj-price-period" style="font-size: 0.5em; color: #666;"> / 40 min</span>
                                </div>
                                <p class="ryj-price-note" style="font-size: 0.9em; color: #777;">Standard Session</p>
                            </div>
                        @endif
                        @if (($pricing['rate_1hr'] ?? null) !== null)
                            <div class="ryj-price-card" style="flex: 1; min-width: 250px; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; text-align: center; background: #f9f9f9;">
                                <div class="ryj-price-header" style="margin-bottom: 15px;">
                                    <span class="ryj-tier-badge tier-{{ $tier }}" style="background: #fff; padding: 5px 10px; border-radius: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        @if ($tierIconUrl)
                                            <img src="{{ $tierIconUrl }}" alt="" class="ryj-tier-icon">
                                        @else
                                            {{ $tierIconsFallback[$tier] ?? '🥈' }}
                                        @endif
                                        {{ $tierLabel }} Host
                                    </span>
                                </div>
                                <div class="ryj-price-amount" style="color: #4682B4; font-weight: bold; font-size: 2em; margin-bottom: 10px;">
                                    <span class="ryj-currency">$</span>
                                    <span class="ryj-price-value">{{ number_format((float) $pricing['rate_1hr'], 2) }}</span>
                                    <span class="ryj-price-period" style="font-size: 0.5em; color: #666;"> / hour</span>
                                </div>
                                <p class="ryj-price-note" style="font-size: 0.9em; color: #777;">Extended Session</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @include('web.find-a-gym.partials.gym-booking-form', ['bookingBootstrap' => $bookingBootstrap])
        </div>

        @if ($photoCount > 0)
            <div id="ryj-photo-modal" class="ryj-photo-modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="ryj-photo-modal-title">
                <div class="ryj-modal-overlay" onclick="closePhotoModal()"></div>
                <div class="ryj-modal-content">
                    <div class="ryj-modal-header">
                        <h3 id="ryj-photo-modal-title">{{ $listing->name }} - Gallery</h3>
                        <button type="button" class="ryj-modal-close" onclick="closePhotoModal()" aria-label="Close">×</button>
                    </div>
                    <div class="ryj-modal-body">
                        <button type="button" class="ryj-modal-nav ryj-modal-prev" onclick="navigatePhoto(-1)" aria-label="Previous">&lt;</button>
                        <img id="ryj-modal-main-img" src="" alt="">
                        <button type="button" class="ryj-modal-nav ryj-modal-next" onclick="navigatePhoto(1)" aria-label="Next">&gt;</button>
                        <div class="ryj-photo-counter">
                            <span id="ryj-current-photo">1</span> / <span id="ryj-total-photos">{{ $photoCount }}</span>
                        </div>
                    </div>
                    <div class="ryj-modal-thumbnails">
                        @foreach ($photos as $index => $photoUrl)
                            @if ($photoUrl)
                                <div class="ryj-modal-thumb" data-index="{{ $index }}" onclick="showModalPhoto({{ $index }})">
                                    <img src="{{ $photoUrl }}" alt="Photo {{ $index + 1 }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </main>
@endsection

{{-- Gym booking script is pushed from partial via @vite (resources/js/gym-booking-form/main.jsx) --}}

@if ($photoCount > 0)
    @push('scripts')
        <script>
            let currentPhotoIndex = 0;
            let allPhotos = [];

            function openPhotoModal(photoIndex) {
                const thumbs = document.querySelectorAll('.ryj-modal-thumb');
                allPhotos = Array.from(thumbs).map(function (thumb) {
                    return thumb.querySelector('img').src;
                });
                currentPhotoIndex = photoIndex;
                showModalPhoto(currentPhotoIndex);
                document.getElementById('ryj-photo-modal').style.display = 'flex';
            }

            function closePhotoModal() {
                document.getElementById('ryj-photo-modal').style.display = 'none';
            }

            function showModalPhoto(index) {
                if (allPhotos.length === 0) return;
                currentPhotoIndex = index;
                document.getElementById('ryj-modal-main-img').src = allPhotos[index];
                document.getElementById('ryj-current-photo').textContent = String(index + 1);
                document.querySelectorAll('.ryj-modal-thumb').forEach(function (thumb, i) {
                    thumb.classList.toggle('active', i === index);
                });
                const activeThumb = document.querySelector('.ryj-modal-thumb.active');
                if (activeThumb) {
                    activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            }

            function navigatePhoto(direction) {
                if (allPhotos.length === 0) return;
                currentPhotoIndex += direction;
                if (currentPhotoIndex < 0) {
                    currentPhotoIndex = allPhotos.length - 1;
                } else if (currentPhotoIndex >= allPhotos.length) {
                    currentPhotoIndex = 0;
                }
                showModalPhoto(currentPhotoIndex);
            }

            document.addEventListener('keydown', function (e) {
                const modal = document.getElementById('ryj-photo-modal');
                if (!modal || modal.style.display !== 'flex') return;
                if (e.key === 'Escape') {
                    closePhotoModal();
                } else if (e.key === 'ArrowLeft') {
                    navigatePhoto(-1);
                } else if (e.key === 'ArrowRight') {
                    navigatePhoto(1);
                }
            });
        </script>
    @endpush
@endif
