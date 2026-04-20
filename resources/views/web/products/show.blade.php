@extends('layouts.web.master')
@section('title', 'Product Detail')
@section('content')

<section class="inner-banner about-banner">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="hd-lg">Product Detail</h1>
            </div>
        </div>
    </div>
</section>

<section class="product-detail-sec sec-dark-bg sec-gap-y">
    <div class="container">
        <div class="row row-gap-40">
            <!-- Left Side - Product Image -->
            <div class="col-lg-6">
                <div class="product-image-wrapper">
                    <div class="product-main-image sec-bg-light radius-10 overflow-hidden mb-20">
                        <img src="{{ asset('images/con-place-01.png') }}" alt="Product Image" class="img-fluid w-100" style="height: 500px; object-fit: cover;">
                    </div>
                    <!-- Product Thumbnail Images -->
                    <div class="product-thumbnails d-flex gap-15">
                        <div class="product-thumbnail sec-bg-light radius-10 overflow-hidden" style="width: 120px; height: 120px; cursor: pointer; border: 2px solid transparent;">
                            <img src="{{ asset('images/con-place-01.png') }}" alt="Thumbnail 1" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="product-thumbnail sec-bg-light radius-10 overflow-hidden" style="width: 120px; height: 120px; cursor: pointer; border: 2px solid transparent;">
                            <img src="{{ asset('images/con-place-02.png') }}" alt="Thumbnail 2" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="product-thumbnail sec-bg-light radius-10 overflow-hidden" style="width: 120px; height: 120px; cursor: pointer; border: 2px solid transparent;">
                            <img src="{{ asset('images/con-place-03.png') }}" alt="Thumbnail 3" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="product-thumbnail sec-bg-light radius-10 overflow-hidden" style="width: 120px; height: 120px; cursor: pointer; border: 2px solid transparent;">
                            <img src="{{ asset('images/con-place-04.png') }}" alt="Thumbnail 4" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Product Details -->
            <div class="col-lg-6">
                <div class="product-details-wrapper">
                    <h2 class="text-white secondry-font fs-40 mb-20">Power Drill Set</h2>
                    
                    <div class="product-rating mb-20 d-flex align-items-center gap-15">
                        <div class="rating-stars text-secondry-theme">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="text-white fs-14">(4.8) 24 Reviews</span>
                    </div>

                    <div class="product-price mb-30">
                        <span class="text-secondry-theme fs-50 fw-700 secondry-font">$299.99</span>
                        <span class="text-white fs-18 ms-10">USD</span>
                    </div>

                    <div class="product-description mb-30">
                        <p class="text-white fs-16 para">
                            Professional grade power drill set with multiple attachments. This comprehensive set includes everything you need for your construction and DIY projects. Features high-quality materials and durable construction for long-lasting performance.
                        </p>
                        <p class="text-white fs-16 para">
                            Perfect for contractors and professionals who demand reliability and precision in their tools. The set comes with various drill bits and accessories to handle different materials and applications.
                        </p>
                    </div>

                    <!-- Quantity Input -->
                    <div class="product-quantity mb-30">
                        <label for="quantity" class="text-white fs-16 fw-500 mb-10 d-block">Quantity</label>
                        <div class="quantity-input-wrapper d-flex align-items-center gap-15" style="max-width: 200px;">
                            <button type="button" id="quantity-minus" class="quantity-btn sec-bg-light text-white border-0 radius-10" style="width: 40px; height: 40px; cursor: pointer;">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <div class="quantity-count">1</div>
                            <button type="button" id="quantity-plus" class="quantity-btn sec-bg-light text-white border-0 radius-10" style="width: 40px; height: 40px; cursor: pointer;">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="product-actions mb-30">
                        <button type="button" class="bootstrap btn btn-primary w-100 py-15" style="font-size: 1.8rem;">
                            <i class="fa-solid fa-cart-shopping me-10"></i> Add to Cart
                        </button>
                    </div>

                    <!-- Product Features -->
                    {{-- <div class="product-features sec-bg-light radius-10 py-20 px-20">
                        <h4 class="text-white secondry-font fs-20 mb-15">Product Features</h4>
                        <ul class="product-features-list" style="list-style: none; padding: 0;">
                            <li class="text-white fs-14 mb-10 d-flex align-items-center gap-10">
                                <i class="fa-solid fa-check text-secondry-theme"></i>
                                Professional grade quality
                            </li>
                            <li class="text-white fs-14 mb-10 d-flex align-items-center gap-10">
                                <i class="fa-solid fa-check text-secondry-theme"></i>
                                Multiple attachments included
                            </li>
                            <li class="text-white fs-14 mb-10 d-flex align-items-center gap-10">
                                <i class="fa-solid fa-check text-secondry-theme"></i>
                                Durable construction
                            </li>
                            <li class="text-white fs-14 mb-10 d-flex align-items-center gap-10">
                                <i class="fa-solid fa-check text-secondry-theme"></i>
                                1 year warranty
                            </li>
                            <li class="text-white fs-14 d-flex align-items-center gap-10">
                                <i class="fa-solid fa-check text-secondry-theme"></i>
                                Free shipping available
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Quantity increase/decrease functions
    document.getElementById('quantity-plus').addEventListener('click', function() {
        const quantityCount = document.querySelector('.quantity-count');
        let currentValue = parseInt(quantityCount.textContent) || 1;
            quantityCount.textContent = currentValue + 1;
    });
    document.getElementById('quantity-minus').addEventListener('click', function() {
        const quantityCount = document.querySelector('.quantity-count');
        let currentValue = parseInt(quantityCount.textContent) || 1;
            quantityCount.textContent = currentValue - 1;
    });

    // Thumbnail click to change main image
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.product-thumbnail img');
        const mainImage = document.querySelector('.product-main-image img');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Update main image
                mainImage.src = this.src;
                
                // Update active thumbnail border
                thumbnails.forEach(t => {
                    t.closest('.product-thumbnail').style.borderColor = 'transparent';
                });
                this.closest('.product-thumbnail').style.borderColor = 'var(--secondry-theme)';
            });
        });

        // Set first thumbnail as active
        if (thumbnails.length > 0) {
            thumbnails[0].closest('.product-thumbnail').style.borderColor = 'var(--secondry-theme)';
        }
    });
</script>
@endpush

@endsection