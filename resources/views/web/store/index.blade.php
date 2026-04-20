@extends('layouts.web.master')
@section('title', 'Store')
@section('content')

    <section class="inner-banner about-banner">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center gap-20">
                        <div class="store-owner-profile position-relative">
                            <div class="store-profile-img circle-lg">
                                <img src="{{ asset('images/resources-04.png') }}" alt="Store Owner" class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                        </div>
                        <div class="store-owner-info">
                            <h1 class="hd-lg text-white secondry-font mb-10">H&D MASONRY</h1>
                            <p class="text-white fs-16 mb-10">
                                <i class="fa-solid fa-location-dot text-secondry-theme me-10"></i>
                                Midland, Texas - 79701
                            </p>
                            <div class="d-flex align-items-center gap-15 mb-10">
                                <div class="rating-stars text-secondry-theme">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <span class="text-white fs-14">(4.8) 24 Reviews</span>
                            </div>
                            <p class="text-white fs-14 opacity-75">Professional Masonry Services</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="store-page-sec sec-dark-bg sec-gap-y">
        <div class="container">

            <!-- Tabs Navigation -->
            <div class="row mb-30">
                <div class="col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#overview" type="button" role="tab">
                                Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services"
                                type="button" role="tab">
                                Services
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products"
                                type="button" role="tab">
                                Products
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                type="button" role="tab">
                                Reviews
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="overview-content">
                                <h4 class="text-white secondry-font fs-24 mb-20">About H&D MASONRY</h4>
                                <p class="text-white fs-16 mb-20 para">
                                    H&D MASONRY is a professional masonry contractor with years of experience in providing
                                    high-quality masonry services. We specialize in stone, stucco, pavers, concrete,
                                    flagstone, cinderblock, brick, and fence installation.
                                </p>
                                <p class="text-white fs-16 mb-30 para">
                                    Our team of skilled professionals is committed to delivering exceptional results that
                                    exceed our clients' expectations. We take pride in our craftsmanship and attention to
                                    detail.
                                </p>
                                <div class="row row-gap-20">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="info-card sec-bg-light radius-10 py-20 px-20 text-center">
                                            <i class="fa-solid fa-calendar-check text-secondry-theme mb-10"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="text-white fs-16 fw-500 mb-5">5+ Years</h5>
                                            <p class="text-white fs-14 opacity-75">Experience</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="info-card sec-bg-light radius-10 py-20 px-20 text-center">
                                            <i class="fa-solid fa-check-circle text-secondry-theme mb-10"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="text-white fs-16 fw-500 mb-5">150+</h5>
                                            <p class="text-white fs-14 opacity-75">Projects Completed</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="info-card sec-bg-light radius-10 py-20 px-20 text-center">
                                            <i class="fa-solid fa-users text-secondry-theme mb-10"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="text-white fs-16 fw-500 mb-5">24</h5>
                                            <p class="text-white fs-14 opacity-75">Happy Clients</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="info-card sec-bg-light radius-10 py-20 px-20 text-center">
                                            <i class="fa-solid fa-award text-secondry-theme mb-10"
                                                style="font-size: 2.5rem;"></i>
                                            <h5 class="text-white fs-16 fw-500 mb-5">Verified</h5>
                                            <p class="text-white fs-14 opacity-75">Contractor</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Tab -->
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="services-list-wrapper">
                                <div class="services-card-wrapper sec-bg-light radius-10">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('images/hero-bg.png') }}" alt="service-image">
                                    </div>
                                    <div class="services-content py-20 px-20">
                                        <div class="services-content-top">
                                            <a href="#"
                                                class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                                <div class="services-profile-img position-relative">
                                                    <img src="http://localhost:8000/images/resources-04.png"
                                                        alt="profile picture">
                                                    <div class="online-badge-wrapper position-absolute">
                                                    </div>
                                                </div>
                                                <div class="services-content-top-right">
                                                    <h4 class="text-white secondry-font">contractor name</h4>
                                                </div>
                                            </a>
                                            <h4 class="fs-14 fw-300 text-secondry-theme mb-20">MASONRY</h4>
                                            <h4 class="fs-14 fw-300 text-white mb-20"><span><i
                                                        class="fa-solid fa-location-dot"></i></span> Midland,Texas-79701
                                            </h4>
                                            <div class="d-flex align-items-center gap-10">
                                                <a href="{{ route('service-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm">View Detail</a>
                                                <a href="{{ route('book-now') }}"
                                                    class="bootstrap btn btn-outline btn-sm">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="services-list-wrapper">
                                <div class="services-card-wrapper sec-bg-light radius-10">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('images/hero-bg.png') }}" alt="service-image">
                                    </div>
                                    <div class="services-content py-20 px-20">
                                        <div class="services-content-top">
                                            <a href="#"
                                                class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                                <div class="services-profile-img position-relative">
                                                    <img src="http://localhost:8000/images/resources-04.png"
                                                        alt="profile picture">
                                                    <div class="online-badge-wrapper position-absolute">
                                                    </div>
                                                </div>
                                                <div class="services-content-top-right">
                                                    <h4 class="text-white secondry-font">contractor name</h4>
                                                </div>
                                            </a>
                                            <h4 class="fs-14 fw-300 text-secondry-theme mb-20">MASONRY</h4>
                                            <h4 class="fs-14 fw-300 text-white mb-20"><span><i
                                                        class="fa-solid fa-location-dot"></i></span> Midland,Texas-79701
                                            </h4>
                                            <div class="d-flex align-items-center gap-10">
                                                <a href="{{ route('service-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm">View Detail</a>
                                                <a href="{{ route('book-now') }}"
                                                    class="bootstrap btn btn-outline btn-sm">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="services-list-wrapper">
                                <div class="services-card-wrapper sec-bg-light radius-10">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('images/hero-bg.png') }}" alt="service-image">
                                    </div>
                                    <div class="services-content py-20 px-20">
                                        <div class="services-content-top">
                                            <a href="#"
                                                class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                                <div class="services-profile-img position-relative">
                                                    <img src="http://localhost:8000/images/resources-04.png"
                                                        alt="profile picture">
                                                    <div class="online-badge-wrapper position-absolute">
                                                    </div>
                                                </div>
                                                <div class="services-content-top-right">
                                                    <h4 class="text-white secondry-font">contractor name</h4>
                                                </div>
                                            </a>
                                            <h4 class="fs-14 fw-300 text-secondry-theme mb-20">MASONRY</h4>
                                            <h4 class="fs-14 fw-300 text-white mb-20"><span><i
                                                        class="fa-solid fa-location-dot"></i></span> Midland,Texas-79701
                                            </h4>
                                            <div class="d-flex align-items-center gap-10">
                                                <a href="{{ route('service-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm">View Detail</a>
                                                <a href="{{ route('book-now') }}"
                                                    class="bootstrap btn btn-outline btn-sm">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="services-list-wrapper">
                                <div class="services-card-wrapper sec-bg-light radius-10">
                                    <div class="img-wrapper">
                                        <img src="{{ asset('images/hero-bg.png') }}" alt="service-image">
                                    </div>
                                    <div class="services-content py-20 px-20">
                                        <div class="services-content-top">
                                            <a href="#"
                                                class="d-flex align-items-center mb-20 gap-20 store-link-wrapper">
                                                <div class="services-profile-img position-relative">
                                                    <img src="http://localhost:8000/images/resources-04.png"
                                                        alt="profile picture">
                                                    <div class="online-badge-wrapper position-absolute">
                                                    </div>
                                                </div>
                                                <div class="services-content-top-right">
                                                    <h4 class="text-white secondry-font">contractor name</h4>
                                                </div>
                                            </a>
                                            <h4 class="fs-14 fw-300 text-secondry-theme mb-20">MASONRY</h4>
                                            <h4 class="fs-14 fw-300 text-white mb-20"><span><i
                                                        class="fa-solid fa-location-dot"></i></span> Midland,Texas-79701
                                            </h4>
                                            <div class="d-flex align-items-center gap-10">
                                                <a href="{{ route('service-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm">View Detail</a>
                                                <a href="{{ route('book-now') }}"
                                                    class="bootstrap btn btn-outline btn-sm">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="products-content">
                                <h4 class="text-white secondry-font fs-24 mb-20">Our Products</h4>
                                <div class="row row-gap-40">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="con-place-card-wrapper marketplace-card-wrapper">
                                            <div class="img-wrapper">
                                                <img src="{{ asset('images/con-place-01.png') }}" alt="Product Image">
                                            </div>
                                            <div class="content-wrapper">
                                                <h4 class="hd-lg hd-sm mb-10">Power Drill Set</h4>
                                                <p class="text-white mb-15 clamp-2">Professional grade power drill set with
                                                    multiple attachments</p>
                                                <div class="d-flex align-items-center justify-content-between mb-15">
                                                    <span class="text-secondry-theme fs-20 fw-600">$299.99</span>
                                                    <div class="rating-stars text-secondry-theme">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>
                                                <a href="{{ route('product-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm w-100">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="con-place-card-wrapper marketplace-card-wrapper">
                                            <div class="img-wrapper">
                                                <img src="{{ asset('images/con-place-02.png') }}" alt="Product Image">
                                            </div>
                                            <div class="content-wrapper">
                                                <h4 class="hd-lg hd-sm mb-10">Safety Helmet</h4>
                                                <p class="text-white mb-15 clamp-2">Industrial grade safety helmet with
                                                    adjustable straps</p>
                                                <div class="d-flex align-items-center justify-content-between mb-15">
                                                    <span class="text-secondry-theme fs-20 fw-600">$49.99</span>
                                                    <div class="rating-stars text-secondry-theme">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-regular fa-star"></i>
                                                    </div>
                                                </div>
                                                <a href="{{ route('product-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm w-100">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="con-place-card-wrapper marketplace-card-wrapper">
                                            <div class="img-wrapper">
                                                <img src="{{ asset('images/con-place-03.png') }}" alt="Product Image">
                                            </div>
                                            <div class="content-wrapper">
                                                <h4 class="hd-lg hd-sm mb-10">Measuring Tools</h4>
                                                <p class="text-white mb-15 clamp-2">Precision measuring tools set for
                                                    construction work</p>
                                                <div class="d-flex align-items-center justify-content-between mb-15">
                                                    <span class="text-secondry-theme fs-20 fw-600">$89.99</span>
                                                    <div class="rating-stars text-secondry-theme">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>
                                                <a href="{{ route('product-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm w-100">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="con-place-card-wrapper marketplace-card-wrapper">
                                            <div class="img-wrapper">
                                                <img src="{{ asset('images/con-place-04.png') }}" alt="Product Image">
                                            </div>
                                            <div class="content-wrapper">
                                                <h4 class="hd-lg hd-sm mb-10">Work Gloves</h4>
                                                <p class="text-white mb-15 clamp-2">Heavy duty work gloves for construction
                                                    safety</p>
                                                <div class="d-flex align-items-center justify-content-between mb-15">
                                                    <span class="text-secondry-theme fs-20 fw-600">$24.99</span>
                                                    <div class="rating-stars text-secondry-theme">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-regular fa-star"></i>
                                                    </div>
                                                </div>
                                                <a href="{{ route('product-detail') }}"
                                                    class="bootstrap btn btn-outline btn-sm w-100">View Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="reviews-content">
                                <h4 class="text-white secondry-font fs-24 mb-20">Customer Reviews</h4>
                                <div class="review-item sec-bg-light radius-10 py-20 px-20 mb-20">
                                    <div class="d-flex align-items-center gap-15 mb-15">
                                        <div
                                            class="reviewer-avatar circle-md bg-primary-theme d-flex align-items-center justify-content-center">
                                            <span class="text-white fs-18 fw-600">JD</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="text-white fs-16 fw-600 mb-5">John Doe</h5>
                                            <div class="rating-stars text-secondry-theme mb-5">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <span class="text-white fs-12 opacity-75">2 weeks ago</span>
                                        </div>
                                    </div>
                                    <p class="text-white fs-14 para">
                                        Excellent service! The team was professional and completed the work on time. Highly
                                        recommended!
                                    </p>
                                </div>
                                <div class="review-item sec-bg-light radius-10 py-20 px-20 mb-20">
                                    <div class="d-flex align-items-center gap-15 mb-15">
                                        <div
                                            class="reviewer-avatar circle-md bg-primary-theme d-flex align-items-center justify-content-center">
                                            <span class="text-white fs-18 fw-600">JS</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="text-white fs-16 fw-600 mb-5">Jane Smith</h5>
                                            <div class="rating-stars text-secondry-theme mb-5">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-regular fa-star"></i>
                                            </div>
                                            <span class="text-white fs-12 opacity-75">1 month ago</span>
                                        </div>
                                    </div>
                                    <p class="text-white fs-14 para">
                                        Great quality work and fair pricing. Will definitely use their services again.
                                    </p>
                                </div>
                                <div class="review-item sec-bg-light radius-10 py-20 px-20 mb-20">
                                    <div class="d-flex align-items-center gap-15 mb-15">
                                        <div
                                            class="reviewer-avatar circle-md bg-primary-theme d-flex align-items-center justify-content-center">
                                            <span class="text-white fs-18 fw-600">MR</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="text-white fs-16 fw-600 mb-5">Mike Roberts</h5>
                                            <div class="rating-stars text-secondry-theme mb-5">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <span class="text-white fs-12 opacity-75">3 months ago</span>
                                        </div>
                                    </div>
                                    <p class="text-white fs-14 para">
                                        Professional team, excellent craftsmanship. They delivered exactly what they
                                        promised. Very satisfied!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
