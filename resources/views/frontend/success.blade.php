    @extends('frontend.layout')
    @section('pageHeading')
        {{ __('Home') }}
    @endsection
    @section('metaKeywords')
        {{ !empty($seoInfo) ? $seoInfo->meta_keyword_home : '' }}
    @endsection

    @section('metaDescription')
        {{ !empty($seoInfo) ? $seoInfo->meta_description_home : '' }}
    @endsection
    @section('content')
        @include('frontend.include.breadcrum')

        <div class="success-wrapper">
            <div class="success-card" data-aos="fade-up">

                <!-- Left Visual -->
                <div class="success-visual">
                    <div class="success-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h1>Payment Successful</h1>
                    <p>Your payment has been processed successfully.
                        A confirmation email has been sent to you.</p>
                </div>

                <!-- Right Info -->
                <div class="success-info">
                    <h6>Order Summary</h6>
                    <div class="order-line">
                        <span>Order ID:</span>
                        <strong>#INV-23457</strong>
                    </div>
                    <div class="order-line">
                        <span>Plan:</span>
                        <strong>Premium Growth Validator</strong>
                    </div>
                    <div class="order-line">
                        <span>Amount Paid:</span>
                        <strong>$49.00</strong>
                    </div>
                    <div class="order-line">
                        <span>End Date:</span>
                        <strong>Nov 10, 2025</strong>
                    </div>
                </div>
            </div>
        </div>
    @endsection
