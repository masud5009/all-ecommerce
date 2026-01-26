    @extends('frontend.layout')
    @section('pageHeading')
        {{ __('Checkout') }}
    @endsection
    @section('metaKeywords')
        {{ !empty($seoInfo) ? $seoInfo->meta_keyword_home : '' }}
    @endsection

    @section('metaDescription')
        {{ !empty($seoInfo) ? $seoInfo->meta_description_home : '' }}
    @endsection
    @section('content')
        @include('frontend.include.breadcrum')

        <section class="checkout-section">
            <div class="container">
                <form id="checkoutForm" action="{{ route('frontend.checkout.submit') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $package->id }}" name="package_id">
                    <div class="row">
                        <!-- Billing Details -->
                        <div class="col-lg-8" data-aos="fade-up">
                            <div class="checkout-card">
                                <h2 class="checkout-title">Billing Details</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Full Name <span class="text-danger">**</span></label>
                                            <input type="text" class="form-control" name="fullname" required>
                                            <p id="err_fullname" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Username<span class="text-danger">**</span></label>
                                            <input type="text" name="username"
                                                value="{{ $registration_data['username'] }}" class="form-control" readonly>
                                            <p id="err_username" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address<span
                                                    class="text-danger">**</span></label>
                                            <input type="email" name="email" value="{{ $registration_data['email'] }}"
                                                class="form-control" readonly>
                                            <p id="err_email" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="company_name">
                                    <p id="err_company_name" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country">
                                    <p id="err_country" class="mb-0 text-danger em"></p>
                                </div>
                            </div>

                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="checkout-card order-summary">
                                <h2 class="checkout-title">Order Summary</h2>
                                <div class="plan-details">
                                    <h3 class="plan-name">{{ $package->title }}</h3>
                                    <div class="plan-price">
                                        ${{ $package->price }} <small>/{{ $package->term }}</small>
                                    </div>
                                    <ul class="plan-features">
                                        <li><i class="fas fa-check"></i> Advanced Idea Validation</li>
                                        <li><i class="fas fa-check"></i> Market Analysis Tools</li>
                                        <li><i class="fas fa-check"></i> Competitor Research</li>
                                        <li><i class="fas fa-check"></i> 24/7 Priority Support</li>
                                    </ul>
                                </div>

                                <div class="price-breakdown">
                                    <div class="price-row">
                                        <span>Subtotal</span>
                                        <span>$49.99</span>
                                    </div>
                                    <div class="price-row">
                                        <span>Tax</span>
                                        <span>$5.00</span>
                                    </div>
                                    <div class="price-row total">
                                        <span>Total</span>
                                        <span>$54.99</span>
                                    </div>
                                </div>

                                <select name="payment_method" id="payment_method" class="nice-select w-100">
                                    <option disabled selected>{{ __('Select Payment Method') }}</option>
                                    @foreach ($payment_methods as $payment_method)
                                        <option value="{{ $payment_method->keyword }}">{{ __($payment_method->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                  <div id="stripe-element" class="mt-5"></div>
                                <p id="err_payment_method" class="mb-0 text-danger em"></p>
                                <button type="button" class="btn btn-checkout" id="submitCheckout">
                                    <i class="fas fa-lock me-2"></i>Complete Purchase
                                </button>

                                <div class="secure-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    Secure Checkout - 256-bit SSL Encryption
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    @endsection
    @section('script')
        <script>
            const stripeKey = "{{ env('STRIPE_PUBLISKEY') }}";
        </script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="{{ asset('assets/front/js/membership-checkout.js') }}"></script>
    @endsection
