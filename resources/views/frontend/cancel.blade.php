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

        <div class="cancel-wrapper">
            <div class="cancel-card" data-aos="fade-up">

                <!-- Left Visual -->
                <div class="cancel-visual">
                    <div class="cancel-icon">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <h1 class="text-danger">Payment Cancelled</h1>
                    <p class="text-danger">Your payment was not completed. You can try again or contact support if you need
                        help.</p>
                </div>
            </div>
        </div>
    @endsection
