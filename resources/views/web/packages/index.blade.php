@extends('layouts.web.master')
@section('title', 'Membership')
@section('content')

    <!-- About Us Section -->
    <section class="inner-banner about-banner">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="hd-lg">Membership</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="membership-sec sec-dark-bg sec-gap-y">
        <div class="container">
            <div class="row row-gap-40">
                <div class="col-12 text-center mb-60">
                    <h2 class="hd-lg mb-20">Choose Your Membership Plan</h2>
                    <p class="para">Select the perfect plan that fits your needs</p>
                </div>
                <!-- Bootstrap Tabs -->
                <div class="col-12">
                    <ul class="nav nav-tabs justify-content-center mb-40" id="membershipTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly"
                                type="button" role="tab" aria-controls="monthly" aria-selected="true">
                                Monthly
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly"
                                type="button" role="tab" aria-controls="yearly" aria-selected="false">
                                Yearly
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="membershipTabsContent">
                        <!-- Monthly Plans -->
                        <div class="tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                            <div class="row row-gap-40">
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <h4 class="hd-lg hd-sm mb-20">Basic Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$20<span class="fs-30">/month</span></h4>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Create a limited profile</li>
                                            <li class="pkg-list-item">Browse verified members</li>
                                            <li class="pkg-list-item">Receive occasional public leads</li>
                                            <li class="pkg-list-item">Basic support</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Basic Member" data-price="$20"
                                            data-period="month"
                                            data-id="{{ $packages->where('name', 'Basic Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <div class="popular-badge">
                                            <span class="badge-text">Most Popular</span>
                                        </div>
                                        <h4 class="hd-lg hd-sm mb-20">Pro Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$49<span class="fs-30">/month</span></h4>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Verified contractor listing</li>
                                            <li class="pkg-list-item">Access referral & rebate system</li>
                                            <li class="pkg-list-item">Add products/services in the marketplace</li>
                                            <li class="pkg-list-item">Participate in county exclusivity</li>
                                            <li class="pkg-list-item">Priority support</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Pro Member" data-price="$49"
                                            data-period="month" data-id="{{ $packages->where('name', 'Pro Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <h4 class="hd-lg hd-sm mb-20">Elite Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$99<span class="fs-30">/month</span></h4>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Top-tier visibility</li>
                                            <li class="pkg-list-item">Featured listing in your county</li>
                                            <li class="pkg-list-item">Increased referral commission rates</li>
                                            <li class="pkg-list-item">Direct access to strategic partner perks</li>
                                            <li class="pkg-list-item">24/7 dedicated support</li>
                                            <li class="pkg-list-item">Advanced analytics</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Elite Member" data-price="$99"
                                            data-period="month" data-id="{{ $packages->where('name', 'Elite Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Yearly Plans -->
                        <div class="tab-pane fade" id="yearly" role="tabpanel" aria-labelledby="yearly-tab">
                            <div class="row row-gap-40">
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <h4 class="hd-lg hd-sm mb-20">Basic Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$0<span class="fs-30">/year</span></h4>
                                        <p class="para mb-20 text-secondry-theme">Save $0 annually</p>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Create a limited profile</li>
                                            <li class="pkg-list-item">Browse verified members</li>
                                            <li class="pkg-list-item">Receive occasional public leads</li>
                                            <li class="pkg-list-item">Basic support</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Basic Member" data-price="$0"
                                            data-period="year" data-id="{{ $packages->where('name', 'Basic Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <div class="popular-badge">
                                            <span class="badge-text">Most Popular</span>
                                        </div>
                                        <h4 class="hd-lg hd-sm mb-20">Pro Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$490<span class="fs-30">/year</span></h4>
                                        <p class="para mb-20 text-secondry-theme">Save $98 annually</p>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Verified contractor listing</li>
                                            <li class="pkg-list-item">Access referral & rebate system</li>
                                            <li class="pkg-list-item">Add products/services in the marketplace</li>
                                            <li class="pkg-list-item">Participate in county exclusivity</li>
                                            <li class="pkg-list-item">Priority support</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Pro Member" data-price="$490"
                                            data-period="year" data-id="{{ $packages->where('name', 'Pro Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="pkg-wrapper glass">
                                        <h4 class="hd-lg hd-sm mb-20">Elite Member</h4>
                                        <h4 class="hd-lg price-text mb-30">$990<span class="fs-30">/year</span></h4>
                                        <p class="para mb-20 text-secondry-theme">Save $198 annually</p>
                                        <ul class="pkg-list position-relative">
                                            <li class="pkg-list-item">Top-tier visibility</li>
                                            <li class="pkg-list-item">Featured listing in your county</li>
                                            <li class="pkg-list-item">Increased referral commission rates</li>
                                            <li class="pkg-list-item">Direct access to strategic partner perks</li>
                                            <li class="pkg-list-item">24/7 dedicated support</li>
                                            <li class="pkg-list-item">Advanced analytics</li>
                                        </ul>
                                        <button class="btn btn-secondary package-button w-100" data-bs-toggle="modal"
                                            data-bs-target="#packageModal" data-plan="Elite Member" data-price="$990"
                                            data-period="year" data-id="{{ $packages->where('name', 'Elite Member')->first()->id }}">Get
                                            Started</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Package Modal -->
    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #1a1a1a; border-color: #333; color: #fff;">
                <div class="modal-header" style="border-bottom-color: #333; padding: 20px;">
                    <h5 class="modal-title text-white" id="packageModalLabel">Confirm Membership Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="text-center mb-4">
                        <h4 class="hd-lg hd-md mb-2 text-white" id="modalPlanName"></h4>
                        <h4 class="hd-lg hd-md price-text mb-30" id="modalPrice"></h4>
                    </div>
                    <p class="para text-center" style="color: #ccc;">Are you sure you want to proceed with this membership
                        plan?</p>
                </div>
                <div class="modal-footer" style="border-top-color: #333;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('fake.payment') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var packageModal = document.getElementById('packageModal');
                
                if (packageModal) {
                    packageModal.addEventListener('show.bs.modal', function (event) {
                        var button = event.relatedTarget;
                        var plan = button.getAttribute('data-plan');
                        var price = button.getAttribute('data-price');
                        var period = button.getAttribute('data-period');
                        
                        var modalPlanName = packageModal.querySelector('#modalPlanName');
                        var modalPrice = packageModal.querySelector('#modalPrice');
                        
                        if (modalPlanName) {
                            modalPlanName.textContent = plan;
                        }
                        if (modalPrice) {
                            modalPrice.innerHTML = price + '<span class="fs-30">/' + period + '</span>';
                        }

                        // AJAX request to backend
                        var packageId = button.getAttribute('data-id');
                        if (packageId) {
                            fetch('/select-plan/' + packageId)
                                .then(r => r.json())
                                .then(data => {
                                    // Data already populated from data attributes
                                })
                                .catch(err => console.error(err));
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
