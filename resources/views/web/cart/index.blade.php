@extends('layouts.web.master')
@section('title', 'Cart')
@section('content')

<section class="inner-banner about-banner">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="hd-lg">Shopping Cart</h1>
            </div>
        </div>
    </div>
</section>

<section class="cart-sec sec-dark-bg sec-gap-y">
    <div class="container">
        <div class="row">
            <!-- Cart Items Section -->
            <div class="col-lg-8">
                <div class="cart-items-wrapper">
                    <div class="cart-header d-flex align-items-center justify-content-between mb-30">
                        <h3 class="text-white secondry-font fs-32">Cart Items</h3>
                        <span class="text-white fs-16">3 Items</span>
                    </div>

                    <!-- Cart Item 1 -->
                    <div class="cart-item sec-bg-light radius-10 py-20 px-20 mb-20">
                        <div class="row align-items-center">
                            <div class="col-md-2 col-4 mb-20 mb-md-0">
                                <div class="cart-item-image">
                                    <img src="{{ asset('images/con-place-01.png') }}" alt="Product" class="img-fluid radius-10" style="width: 100%; height: 100px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-5 col-8 mb-20 mb-md-0">
                                <div class="cart-item-details">
                                    <h4 class="text-white secondry-font fs-18 mb-10">Power Drill Set</h4>
                                    <p class="text-white fs-14 opacity-75 mb-10">Professional grade power drill set</p>
                                    <div class="d-flex align-items-center gap-15">
                                        <span class="text-secondry-theme fs-18 fw-600">$299.99</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-20 mb-md-0">
                                <div class="cart-item-quantity">
                                    <label class="text-white fs-14 mb-10 d-block">Quantity</label>
                                    <div class="quantity-controls d-flex align-items-center gap-10">
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="decreaseCartQuantity(1)">
                                            <i class="fa-solid fa-minus" style="font-size: 1.2rem;"></i>
                                        </button>
                                        <span class="quantity-value text-white fs-16 fw-500" id="quantity-1" style="min-width: 40px; text-align: center;">1</span>
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="increaseCartQuantity(1)">
                                            <i class="fa-solid fa-plus" style="font-size: 1.2rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 text-md-end">
                                <div class="cart-item-actions">
                                    <div class="cart-item-total mb-15">
                                        <span class="text-secondry-theme fs-20 fw-600" id="item-total-1">$299.99</span>
                                    </div>
                                    <button type="button" class="bootstrap btn btn-outline btn-sm" onclick="removeCartItem(1)">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Item 2 -->
                    <div class="cart-item sec-bg-light radius-10 py-20 px-20 mb-20">
                        <div class="row align-items-center">
                            <div class="col-md-2 col-4 mb-20 mb-md-0">
                                <div class="cart-item-image">
                                    <img src="{{ asset('images/con-place-02.png') }}" alt="Product" class="img-fluid radius-10" style="width: 100%; height: 100px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-5 col-8 mb-20 mb-md-0">
                                <div class="cart-item-details">
                                    <h4 class="text-white secondry-font fs-18 mb-10">Safety Helmet</h4>
                                    <p class="text-white fs-14 opacity-75 mb-10">Industrial grade safety helmet</p>
                                    <div class="d-flex align-items-center gap-15">
                                        <span class="text-secondry-theme fs-18 fw-600">$49.99</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-20 mb-md-0">
                                <div class="cart-item-quantity">
                                    <label class="text-white fs-14 mb-10 d-block">Quantity</label>
                                    <div class="quantity-controls d-flex align-items-center gap-10">
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="decreaseCartQuantity(2)">
                                            <i class="fa-solid fa-minus" style="font-size: 1.2rem;"></i>
                                        </button>
                                        <span class="quantity-value text-white fs-16 fw-500" id="quantity-2" style="min-width: 40px; text-align: center;">2</span>
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="increaseCartQuantity(2)">
                                            <i class="fa-solid fa-plus" style="font-size: 1.2rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 text-md-end">
                                <div class="cart-item-actions">
                                    <div class="cart-item-total mb-15">
                                        <span class="text-secondry-theme fs-20 fw-600" id="item-total-2">$99.98</span>
                                    </div>
                                    <button type="button" class="bootstrap btn btn-outline btn-sm" onclick="removeCartItem(2)">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Item 3 -->
                    <div class="cart-item sec-bg-light radius-10 py-20 px-20 mb-20">
                        <div class="row align-items-center">
                            <div class="col-md-2 col-4 mb-20 mb-md-0">
                                <div class="cart-item-image">
                                    <img src="{{ asset('images/con-place-03.png') }}" alt="Product" class="img-fluid radius-10" style="width: 100%; height: 100px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-5 col-8 mb-20 mb-md-0">
                                <div class="cart-item-details">
                                    <h4 class="text-white secondry-font fs-18 mb-10">Measuring Tools</h4>
                                    <p class="text-white fs-14 opacity-75 mb-10">Precision measuring tools set</p>
                                    <div class="d-flex align-items-center gap-15">
                                        <span class="text-secondry-theme fs-18 fw-600">$89.99</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-20 mb-md-0">
                                <div class="cart-item-quantity">
                                    <label class="text-white fs-14 mb-10 d-block">Quantity</label>
                                    <div class="quantity-controls d-flex align-items-center gap-10">
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="decreaseCartQuantity(3)">
                                            <i class="fa-solid fa-minus" style="font-size: 1.2rem;"></i>
                                        </button>
                                        <span class="quantity-value text-white fs-16 fw-500" id="quantity-3" style="min-width: 40px; text-align: center;">1</span>
                                        <button type="button" class="quantity-btn sec-bg-light text-white border-0 radius-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; cursor: pointer;" onclick="increaseCartQuantity(3)">
                                            <i class="fa-solid fa-plus" style="font-size: 1.2rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 text-md-end">
                                <div class="cart-item-actions">
                                    <div class="cart-item-total mb-15">
                                        <span class="text-secondry-theme fs-20 fw-600" id="item-total-3">$89.99</span>
                                    </div>
                                    <button type="button" class="bootstrap btn btn-outline btn-sm" onclick="removeCartItem(3)">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="cart-actions mt-30">
                        <a href="{{ route('products') }}" class="bootstrap btn btn-outline">
                            <i class="fa-solid fa-arrow-left me-10"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart Summary Section -->
            <div class="col-lg-4">
                <div class="cart-summary sec-bg-light radius-10 py-30 px-20" style="position: sticky; top: 30px;">
                    <h3 class="text-white secondry-font fs-24 mb-20">Order Summary</h3>
                    
                    <div class="cart-summary-details mb-20">
                        <div class="d-flex align-items-center justify-content-between mb-15">
                            <span class="text-white fs-16">Subtotal</span>
                            <span class="text-white fs-16 fw-600" id="cart-subtotal">$489.96</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-15">
                            <span class="text-white fs-16">Shipping</span>
                            <span class="text-white fs-16 fw-600">$15.00</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-20">
                            <span class="text-white fs-16">Tax</span>
                            <span class="text-white fs-16 fw-600">$39.20</span>
                        </div>
                        <div class="cart-summary-divider mb-20" style="height: 1px; background-color: rgba(255, 255, 255, 0.2);"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-white fs-20 fw-600 secondry-font">Total</span>
                            <span class="text-secondry-theme fs-30 fw-700 secondry-font" id="cart-total">$544.16</span>
                        </div>
                    </div>

                    <div class="cart-summary-actions">
                        <a href="{{ route('checkout') }}" class="bootstrap btn btn-primary w-100 mb-15 py-15" style="font-size: 1.8rem;">
                            <i class="fa-solid fa-credit-card me-10"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('products') }}" class="bootstrap btn btn-outline w-100">
                            Continue Shopping
                        </a>
                    </div>

                    <!-- Security Info -->
                    <div class="cart-security-info mt-30 pt-20" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="d-flex align-items-center gap-10 mb-10">
                            <i class="fa-solid fa-shield-halved text-secondry-theme"></i>
                            <span class="text-white fs-14">Secure Checkout</span>
                        </div>
                        <div class="d-flex align-items-center gap-10">
                            <i class="fa-solid fa-truck text-secondry-theme"></i>
                            <span class="text-white fs-14">Fast Delivery</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Cart item prices
    const cartItems = {
        1: { price: 299.99, quantity: 1 },
        2: { price: 49.99, quantity: 2 },
        3: { price: 89.99, quantity: 1 }
    };

    // Update cart totals
    function updateCartTotals() {
        let subtotal = 0;
        
        Object.keys(cartItems).forEach(itemId => {
            const item = cartItems[itemId];
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            // Update item total
            document.getElementById('item-total-' + itemId).textContent = '$' + itemTotal.toFixed(2);
        });

        const shipping = 15.00;
        const tax = subtotal * 0.08; // 8% tax
        const total = subtotal + shipping + tax;

        // Update summary
        document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
    }

    // Increase quantity
    function increaseCartQuantity(itemId) {
        if (cartItems[itemId]) {
            cartItems[itemId].quantity++;
            document.getElementById('quantity-' + itemId).textContent = cartItems[itemId].quantity;
            updateCartTotals();
        }
    }

    // Decrease quantity
    function decreaseCartQuantity(itemId) {
        if (cartItems[itemId] && cartItems[itemId].quantity > 1) {
            cartItems[itemId].quantity--;
            document.getElementById('quantity-' + itemId).textContent = cartItems[itemId].quantity;
            updateCartTotals();
        }
    }

    // Remove item
    function removeCartItem(itemId) {
        if (confirm('Are you sure you want to remove this item from cart?')) {
            const cartItem = document.querySelector(`[onclick*="removeCartItem(${itemId})"]`).closest('.cart-item');
            cartItem.remove();
            delete cartItems[itemId];
            updateCartTotals();
            
            // Update cart count
            const cartCount = document.querySelector('.cart-header span');
            const itemCount = Object.keys(cartItems).length;
            cartCount.textContent = itemCount + ' Item' + (itemCount !== 1 ? 's' : '');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartTotals();
    });
</script>
@endpush

@endsection

