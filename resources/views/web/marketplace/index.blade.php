@extends('layouts.web.master')
@section('title', 'Marketplace')
@section('content')
    <section class="inner-banner about-banner">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="hd-lg">Marketplace</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Marketplace Section -->
    <section class="contractor-marketplace-sec sec-dark-bg sec-gap-y">
        <div class="container">
            <div class="row mb-40">
                <div class="col-8 text-center mx-auto">
                    <h2 class="hd-lg mb-20">
                        The Contractor Marketplace
                    </h2>
                    <p class="para">
                        Welcome to the League Marketplace — a trusted space where verified contractors can buy, sell, and
                        trade products and services within the professional community.
                    </p>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="row mb-40">
                <div class="col-12">
                    <ul class="nav nav-tabs justify-content-center" id="marketplaceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                                type="button" role="tab" aria-controls="all" aria-selected="true">
                                All
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#tools"
                                type="button" role="tab" aria-controls="tools" aria-selected="false">
                                Tools & Equipment
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="materials-tab" data-bs-toggle="tab" data-bs-target="#materials"
                                type="button" role="tab" aria-controls="materials" aria-selected="false">
                                Building Materials
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services"
                                type="button" role="tab" aria-controls="services" aria-selected="false">
                                Professional Services
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="technology-tab" data-bs-toggle="tab" data-bs-target="#technology"
                                type="button" role="tab" aria-controls="technology" aria-selected="false">
                                Technology & Software
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="marketplaceTabsContent">
                <!-- All Categories -->
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-01.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="{{ route('store') }}" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Tools & Equipment</h4>
                                    <p class="text-white mb-20 clamp-2">Power tools, safety gear, rentals, and accessories</p>
                                    <a href="{{ route('product-detail') }}" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-02.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Building Materials</h4>
                                    <p class="text-white mb-20 clamp-2">Lumber, concrete, paint, sealants, insulation</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-03.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Professional Services</h4>
                                    <p class="text-white mb-20 clamp-2">Accounting, insurance, marketing, and design</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-04.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Technology & Software</h4>
                                    <p class="text-white mb-20 clamp-2">Project management apps, invoicing tools, CRM systems</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-01.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Tools & Equipment</h4>
                                    <p class="text-white mb-20 clamp-2">Power tools, safety gear, rentals, and accessories</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-02.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Building Materials</h4>
                                    <p class="text-white mb-20 clamp-2">Lumber, concrete, paint, sealants, insulation</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-03.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Professional Services</h4>
                                    <p class="text-white mb-20 clamp-2">Accounting, insurance, marketing, and design</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-04.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Technology & Software</h4>
                                    <p class="text-white mb-20 clamp-2">Project management apps, invoicing tools, CRM systems</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tools & Equipment -->
                <div class="tab-pane fade" id="tools" role="tabpanel" aria-labelledby="tools-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-01.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Tools & Equipment</h4>
                                    <p class="text-white mb-20 clamp-2">Power tools, safety gear, rentals, and accessories</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Building Materials -->
                <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-02.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Building Materials</h4>
                                    <p class="text-white mb-20 clamp-2">Lumber, concrete, paint, sealants, insulation</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Services -->
                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-03.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Professional Services</h4>
                                    <p class="text-white mb-20 clamp-2">Accounting, insurance, marketing, and design</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technology & Software -->
                <div class="tab-pane fade" id="technology" role="tabpanel" aria-labelledby="technology-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        <div class="col-lg-3 col-md-6">
                            <div class="con-place-card-wrapper marketplace-card-wrapper">
                                <div class="img-wrapper">
                                    <img class="" src="{{ asset('images/con-place-04.png') }}"
                                        alt="Contractor Marketplace Image">
                                </div>
                                <div class="content-wrapper">
                                    <a href="#" class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                        <div class="services-profile-img position-relative">
                                            <img src="http://localhost:8000/images/resources-04.png" alt="profile picture">
                                            <div class="online-badge-wrapper position-absolute">
                                            </div>
                                        </div>
                                        <div class="services-content-top-right">
                                            <h4 class="text-white secondry-font">contractor name</h4>
                                        </div>
                                    </a>
                                    <h4 class="hd-lg hd-sm mb-20">Technology & Software</h4>
                                    <p class="text-white mb-20 clamp-2">Project management apps, invoicing tools, CRM systems</p>
                                    <a href="" class="btn btn-outline btn-sm">view store</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
