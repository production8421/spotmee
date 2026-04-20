@extends('layouts.web.master')
@section('title', 'Checkout')
@section('content')

<section class="inner-banner about-banner">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="hd-lg">Checkout</h1>
            </div>
        </div>
    </div>
</section>

<section class="checkout-sec sec-dark-bg sec-gap-y">
    <div class="container">
        <div class="row">
            <!-- Left Side - Checkout Form -->
            <div class="col-lg-8">
                <div class="checkout-form-wrapper">
                    <!-- Billing Information -->
                    <div class="checkout-section sec-bg-light radius-10 py-30 px-30 mb-30">
                        <h3 class="text-white secondry-font fs-24 mb-20">Billing Information</h3>
                        <form id="checkoutForm">
                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="firstName" class="text-white fs-14 mb-10 d-block">First Name</label>
                                        <input type="text" id="firstName" name="firstName" class="glass input-field" placeholder="John" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="lastName" class="text-white fs-14 mb-10 d-block">Last Name</label>
                                        <input type="text" id="lastName" name="lastName" class="glass input-field" placeholder="Doe" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="email" class="text-white fs-14 mb-10 d-block">Email Address</label>
                                        <input type="email" id="email" name="email" class="glass input-field" placeholder="john.doe@example.com" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="phone" class="text-white fs-14 mb-10 d-block">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" class="glass input-field" placeholder="+1 (555) 123-4567" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="address" class="text-white fs-14 mb-10 d-block">Street Address</label>
                                        <input type="text" id="address" name="address" class="glass input-field" placeholder="123 Main Street" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="city" class="text-white fs-14 mb-10 d-block">City</label>
                                        <input type="text" id="city" name="city" class="glass input-field" placeholder="Midland" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="state" class="text-white fs-14 mb-10 d-block">State</label>
                                        <input type="text" id="state" name="state" class="glass input-field" placeholder="Texas" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="zipCode" class="text-white fs-14 mb-10 d-block">ZIP Code</label>
                                        <input type="text" id="zipCode" name="zipCode" class="glass input-field" placeholder="79701" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="country" class="text-white fs-14 mb-10  d-block">Country</label>
                                        <select id="country" name="country" class="glass input-field selct-field form-select
                                        
                                        " required>
                                            <option value="">Select Country</option>
                                            <option value="US" selected>United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="MX">Mexico</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Shipping Information -->
                    <div class="checkout-section sec-bg-light radius-10 py-30 px-30 mb-30">
                        <div class="d-flex align-items-center justify-content-between mb-20">
                            <h3 class="text-white secondry-font fs-24">Shipping Information</h3>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sameAsBilling" checked>
                                <label class="form-check-label text-white fs-14" for="sameAsBilling">
                                    Same as billing
                                </label>
                            </div>
                        </div>
                        <form id="shippingForm">
                            <div class="row">
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingFirstName" class="text-white fs-14 mb-10 d-block">First Name</label>
                                        <input type="text" id="shippingFirstName" name="shippingFirstName" class="glass input-field" placeholder="John" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingLastName" class="text-white fs-14 mb-10 d-block">Last Name</label>
                                        <input type="text" id="shippingLastName" name="shippingLastName" class="glass input-field" placeholder="Doe" disabled>
                                    </div>
                                </div>
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingAddress" class="text-white fs-14 mb-10 d-block">Street Address</label>
                                        <input type="text" id="shippingAddress" name="shippingAddress" class="glass input-field" placeholder="123 Main Street" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingCity" class="text-white fs-14 mb-10 d-block">City</label>
                                        <input type="text" id="shippingCity" name="shippingCity" class="glass input-field" placeholder="Midland" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingState" class="text-white fs-14 mb-10 d-block">State</label>
                                        <input type="text" id="shippingState" name="shippingState" class="glass input-field" placeholder="Texas" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingZipCode" class="text-white fs-14 mb-10 d-block">ZIP Code</label>
                                        <input type="text" id="shippingZipCode" name="shippingZipCode" class="glass input-field" placeholder="79701" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="shippingCountry" class="text-white fs-14 mb-10 d-block">Country</label>
                                        <select id="shippingCountry" name="shippingCountry" class="glass input-field selct-field form-select" disabled>
                                            <option value="">Select Country</option>
                                            <option value="US" selected>United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="MX">Mexico</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-section sec-bg-light radius-10 py-30 px-30 mb-30">
                        <h3 class="text-white secondry-font fs-24 mb-20">Payment Method</h3>
                        <div class="payment-methods">
                            <div class="payment-method-item mb-15">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentCard" value="card" checked>
                                    <label class="form-check-label text-white fs-16 d-flex align-items-center gap-15" for="paymentCard">
                                        <i class="fa-solid fa-credit-card text-secondry-theme" style="font-size: 1.8rem;"></i>
                                        <span>Stripe Payment</span>
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="payment-method-item mb-15">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentPaypal" value="paypal">
                                    <label class="form-check-label text-white fs-16 d-flex align-items-center gap-15" for="paymentPaypal">
                                        <i class="fa-brands fa-paypal text-secondry-theme" style="font-size: 1.8rem;"></i>
                                        <span>PayPal</span>
                                    </label>
                                </div>
                            </div>
                            <div class="payment-method-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paymentCash" value="cash">
                                    <label class="form-check-label text-white fs-16 d-flex align-items-center gap-15" for="paymentCash">
                                        <i class="fa-solid fa-money-bill text-secondry-theme" style="font-size: 1.8rem;"></i>
                                        <span>Cash on Delivery</span>
                                    </label>
                                </div>
                            </div> --}}
                        </div>

                        <!-- Card Details (shown when card is selected) -->
                        <div id="cardDetails" class="mt-30">
                            {{-- <div class="row">
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="cardNumber" class="text-white fs-14 mb-10 d-block">Card Number</label>
                                        <input type="text" id="cardNumber" name="cardNumber" class="glass input-field" placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="cardExpiry" class="text-white fs-14 mb-10 d-block">Expiry Date</label>
                                        <input type="text" id="cardExpiry" name="cardExpiry" class="glass input-field" placeholder="MM/YY" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-20">
                                    <div class="field-wrapper">
                                        <label for="cardCVC" class="text-white fs-14 mb-10 d-block">CVC</label>
                                        <input type="text" id="cardCVC" name="cardCVC" class="glass input-field" placeholder="123" maxlength="4">
                                    </div>
                                </div>
                                <div class="col-12 mb-20">
                                    <div class="field-wrapper">
                                        <label for="cardName" class="text-white fs-14 mb-10 d-block">Cardholder Name</label>
                                        <input type="text" id="cardName" name="cardName" class="glass input-field" placeholder="John Doe">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Order Summary -->
            <div class="col-lg-4">
                <div class="checkout-summary sec-bg-light radius-10 py-30 px-20" style="position: sticky; top: 30px;">
                    <h3 class="text-white secondry-font fs-24 mb-20">Order Summary</h3>
                    
                    <!-- Order Items -->
                    <div class="order-items mb-20">
                        <div class="order-item d-flex align-items-center gap-15 mb-15">
                            <div class="order-item-image" style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden;">
                                <img src="{{ asset('images/con-place-01.png') }}" alt="Product" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h5 class="text-white fs-14 fw-500 mb-5">Power Drill Set</h5>
                                <span class="text-white fs-12 opacity-75">Qty: 1</span>
                            </div>
                            <span class="text-secondry-theme fs-16 fw-600">$299.99</span>
                        </div>
                        <div class="order-item d-flex align-items-center gap-15 mb-15">
                            <div class="order-item-image" style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden;">
                                <img src="{{ asset('images/con-place-02.png') }}" alt="Product" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h5 class="text-white fs-14 fw-500 mb-5">Safety Helmet</h5>
                                <span class="text-white fs-12 opacity-75">Qty: 2</span>
                            </div>
                            <span class="text-secondry-theme fs-16 fw-600">$99.98</span>
                        </div>
                        <div class="order-item d-flex align-items-center gap-15 mb-15">
                            <div class="order-item-image" style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden;">
                                <img src="{{ asset('images/con-place-03.png') }}" alt="Product" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h5 class="text-white fs-14 fw-500 mb-5">Measuring Tools</h5>
                                <span class="text-white fs-12 opacity-75">Qty: 1</span>
                            </div>
                            <span class="text-secondry-theme fs-16 fw-600">$89.99</span>
                        </div>
                    </div>

                    <div class="checkout-summary-divider mb-20" style="height: 1px; background-color: rgba(255, 255, 255, 0.2);"></div>

                    <!-- Order Totals -->
                    <div class="order-totals mb-20">
                        <div class="d-flex align-items-center justify-content-between mb-15">
                            <span class="text-white fs-16">Subtotal</span>
                            <span class="text-white fs-16 fw-600">$489.96</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-15">
                            <span class="text-white fs-16">Shipping</span>
                            <span class="text-white fs-16 fw-600">$15.00</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-20">
                            <span class="text-white fs-16">Tax</span>
                            <span class="text-white fs-16 fw-600">$39.20</span>
                        </div>
                        <div class="checkout-summary-divider mb-20" style="height: 1px; background-color: rgba(255, 255, 255, 0.2);"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-white fs-20 fw-600 secondry-font">Total</span>
                            <span class="text-secondry-theme fs-30 fw-700 secondry-font">$544.16</span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="checkout-actions">
                        <button type="button" class="bootstrap btn btn-primary w-100 mb-15 py-15" style="font-size: 1.8rem;" onclick="placeOrder()">
                            <i class="fa-solid fa-lock me-10"></i> Place Order
                        </button>
                        <a href="{{ route('cart') }}" class="bootstrap btn btn-outline w-100">
                            <i class="fa-solid fa-arrow-left me-10"></i> Back to Cart
                        </a>
                    </div>

                    <!-- Security Info -->
                    <div class="checkout-security-info mt-30 pt-20" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="d-flex align-items-center gap-10 mb-10">
                            <i class="fa-solid fa-shield-halved text-secondry-theme"></i>
                            <span class="text-white fs-14">Secure Payment</span>
                        </div>
                        <div class="d-flex align-items-center gap-10">
                            <i class="fa-solid fa-lock text-secondry-theme"></i>
                            <span class="text-white fs-14">Your information is safe</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Same as billing checkbox functionality
    document.getElementById('sameAsBilling').addEventListener('change', function() {
        const shippingFields = document.querySelectorAll('#shippingForm input, #shippingForm select');
        shippingFields.forEach(field => {
            field.disabled = this.checked;
            if (this.checked) {
                // Copy billing info to shipping
                const billingField = document.getElementById(field.id.replace('shipping', '').replace('shipping', ''));
                if (billingField) {
                    field.value = billingField.value;
                }
            } else {
                field.value = '';
            }
        });
    });

    // Copy billing to shipping when billing fields change
    document.getElementById('sameAsBilling').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('shippingFirstName').value = document.getElementById('firstName').value;
            document.getElementById('shippingLastName').value = document.getElementById('lastName').value;
            document.getElementById('shippingAddress').value = document.getElementById('address').value;
            document.getElementById('shippingCity').value = document.getElementById('city').value;
            document.getElementById('shippingState').value = document.getElementById('state').value;
            document.getElementById('shippingZipCode').value = document.getElementById('zipCode').value;
            document.getElementById('shippingCountry').value = document.getElementById('country').value;
        }
    });

    // Watch billing fields and update shipping if same as billing is checked
    const billingFields = ['firstName', 'lastName', 'address', 'city', 'state', 'zipCode', 'country'];
    billingFields.forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('input', function() {
            if (document.getElementById('sameAsBilling').checked) {
                const shippingFieldId = 'shipping' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1);
                const shippingField = document.getElementById(shippingFieldId);
                if (shippingField) {
                    shippingField.value = this.value;
                }
            }
        });
    });

    // Payment method change
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const cardDetails = document.getElementById('cardDetails');
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });

    // Card number formatting
    document.getElementById('cardNumber').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        if (formattedValue.length <= 19) {
            e.target.value = formattedValue;
        }
    });

    // Expiry date formatting
    document.getElementById('cardExpiry').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Place order function
    function placeOrder() {
        // Form validation
        const form = document.getElementById('checkoutForm');
        if (form.checkValidity()) {
            // Show success message (backend integration later)
            alert('Order placed successfully! Thank you for your purchase.');
            // Redirect to order confirmation page (to be created)
        } else {
            form.reportValidity();
        }
    }
</script>
@endpush

@endsection

