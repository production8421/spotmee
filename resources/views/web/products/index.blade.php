@extends('layouts.web.master')
@section('title', 'All Products')
@section('content')
    <section class="inner-banner about-banner">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="hd-lg">All Products</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="contractor-marketplace-sec sec-dark-bg sec-gap-y">
        <div class="container">
            <div class="row mb-40">
                <div class="col-8 text-center mx-auto">
                    <h2 class="hd-lg mb-20">
                        All Products
                    </h2>
                    <p class="para">
                        Browse our extensive collection of products designed to meet the needs of contractors and professionals in the construction industry. From tools and equipment to building materials and professional services, we have everything you need to get the job done right.
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
                        @php
                            // Sample products data - Replace this with actual data from controller
                            $products = [
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Power Drill Set',
                                    'description' => 'Professional grade power drill set with multiple attachments',
                                    'price' => '$299.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Safety Helmet',
                                    'description' => 'Industrial grade safety helmet with adjustable straps',
                                    'price' => '$49.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Measuring Tools',
                                    'description' => 'Precision measuring tools set for construction work',
                                    'price' => '$89.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Work Gloves',
                                    'description' => 'Heavy duty work gloves for construction safety',
                                    'price' => '$24.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Circular Saw',
                                    'description' => 'Professional circular saw with laser guide',
                                    'price' => '$199.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Tool Belt',
                                    'description' => 'Heavy duty tool belt with multiple pockets',
                                    'price' => '$39.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Level Tool',
                                    'description' => 'Digital level tool for precise measurements',
                                    'price' => '$79.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                    'cartUrl' => route('cart'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Safety Boots',
                                    'description' => 'Steel toe safety boots for construction',
                                    'price' => '$129.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                            ];
                        @endphp
                        
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @include('components.web.product-card', $product)
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tools & Equipment -->
                <div class="tab-pane fade" id="tools" role="tabpanel" aria-labelledby="tools-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        @php
                            // Sample products data - Replace this with actual data from controller
                            $products = [
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Power Drill Set',
                                    'description' => 'Professional grade power drill set with multiple attachments',
                                    'price' => '$299.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Safety Helmet',
                                    'description' => 'Industrial grade safety helmet with adjustable straps',
                                    'price' => '$49.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Measuring Tools',
                                    'description' => 'Precision measuring tools set for construction work',
                                    'price' => '$89.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Work Gloves',
                                    'description' => 'Heavy duty work gloves for construction safety',
                                    'price' => '$24.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Circular Saw',
                                    'description' => 'Professional circular saw with laser guide',
                                    'price' => '$199.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Tool Belt',
                                    'description' => 'Heavy duty tool belt with multiple pockets',
                                    'price' => '$39.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Level Tool',
                                    'description' => 'Digital level tool for precise measurements',
                                    'price' => '$79.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Safety Boots',
                                    'description' => 'Steel toe safety boots for construction',
                                    'price' => '$129.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                            ];
                        @endphp
                        
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @include('components.web.product-card', $product)
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Building Materials -->
                <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="materials-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        @php
                            // Sample products data - Replace this with actual data from controller
                            $products = [
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Power Drill Set',
                                    'description' => 'Professional grade power drill set with multiple attachments',
                                    'price' => '$299.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Safety Helmet',
                                    'description' => 'Industrial grade safety helmet with adjustable straps',
                                    'price' => '$49.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Measuring Tools',
                                    'description' => 'Precision measuring tools set for construction work',
                                    'price' => '$89.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Work Gloves',
                                    'description' => 'Heavy duty work gloves for construction safety',
                                    'price' => '$24.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Circular Saw',
                                    'description' => 'Professional circular saw with laser guide',
                                    'price' => '$199.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Tool Belt',
                                    'description' => 'Heavy duty tool belt with multiple pockets',
                                    'price' => '$39.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Level Tool',
                                    'description' => 'Digital level tool for precise measurements',
                                    'price' => '$79.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Safety Boots',
                                    'description' => 'Steel toe safety boots for construction',
                                    'price' => '$129.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                            ];
                        @endphp
                        
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @include('components.web.product-card', $product)
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Professional Services -->
                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        @php
                            // Sample products data - Replace this with actual data from controller
                            $products = [
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Power Drill Set',
                                    'description' => 'Professional grade power drill set with multiple attachments',
                                    'price' => '$299.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Safety Helmet',
                                    'description' => 'Industrial grade safety helmet with adjustable straps',
                                    'price' => '$49.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Measuring Tools',
                                    'description' => 'Precision measuring tools set for construction work',
                                    'price' => '$89.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Work Gloves',
                                    'description' => 'Heavy duty work gloves for construction safety',
                                    'price' => '$24.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Circular Saw',
                                    'description' => 'Professional circular saw with laser guide',
                                    'price' => '$199.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Tool Belt',
                                    'description' => 'Heavy duty tool belt with multiple pockets',
                                    'price' => '$39.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Level Tool',
                                    'description' => 'Digital level tool for precise measurements',
                                    'price' => '$79.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Safety Boots',
                                    'description' => 'Steel toe safety boots for construction',
                                    'price' => '$129.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                            ];
                        @endphp
                        
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @include('components.web.product-card', $product)
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Technology & Software -->
                <div class="tab-pane fade" id="technology" role="tabpanel" aria-labelledby="technology-tab">
                    <div class="row text-center text-md-start row-gap-40">
                        @php
                            // Sample products data - Replace this with actual data from controller
                            $products = [
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Power Drill Set',
                                    'description' => 'Professional grade power drill set with multiple attachments',
                                    'price' => '$299.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Safety Helmet',
                                    'description' => 'Industrial grade safety helmet with adjustable straps',
                                    'price' => '$49.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Measuring Tools',
                                    'description' => 'Precision measuring tools set for construction work',
                                    'price' => '$89.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Work Gloves',
                                    'description' => 'Heavy duty work gloves for construction safety',
                                    'price' => '$24.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-01.png'),
                                    'title' => 'Circular Saw',
                                    'description' => 'Professional circular saw with laser guide',
                                    'price' => '$199.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-02.png'),
                                    'title' => 'Tool Belt',
                                    'description' => 'Heavy duty tool belt with multiple pockets',
                                    'price' => '$39.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-03.png'),
                                    'title' => 'Level Tool',
                                    'description' => 'Digital level tool for precise measurements',
                                    'price' => '$79.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                                [
                                    'image' => asset('images/con-place-04.png'),
                                    'title' => 'Safety Boots',
                                    'description' => 'Steel toe safety boots for construction',
                                    'price' => '$129.99',
                                    'rating' => false,
                                    'detailUrl' => route('product-detail'),
                                ],
                            ];
                        @endphp
                        
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @include('components.web.product-card', $product)
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection