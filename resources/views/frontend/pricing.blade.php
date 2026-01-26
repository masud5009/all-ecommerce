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
        <!-- PRICING -->
        <section id="pricing" class="pricing-section mt-5 pt-5 mb-5 pb-5">
            <div class="container">

                <div class="text-center mb-4" data-aos="fade-up">
                    <nav class="pricing-tabs">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly"
                                type="button" role="tab" aria-controls="monthly" aria-selected="true">Monthly</button>
                            <button class="nav-link active" id="yearly-tab" data-bs-toggle="tab" data-bs-target="#yearly"
                                type="button" role="tab" aria-controls="yearly" aria-selected="false">Yearly</button>
                            <button class="nav-link" id="lifetime-tab" data-bs-toggle="tab" data-bs-target="#lifetime"
                                type="button" role="tab" aria-controls="lifetime"
                                aria-selected="false">Lifetime</button>
                            <button class="nav-link" id="lifetime-tab" data-bs-toggle="tab" data-bs-target="#lifetime"
                                type="button" role="tab" aria-controls="lifetime"
                                aria-selected="false">Lifetime</button>
                        </div>
                    </nav>
                </div>

                <div class="tab-content" id="nav-tabContent">

                    <!-- ================= Monthly Plans ================= -->
                    <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                        <div class="row g-4">
                            @foreach ($packages->where('term', 'monthly') as $package)
                                <x-package-card :package="$package" />
                            @endforeach
                        </div>
                    </div>

                    <!-- ================= Yearly Plans ================= -->
                    <div class="tab-pane fade show active" id="yearly" role="tabpanel" aria-labelledby="yearly-tab">
                        <div class="row g-4">

                            @foreach ($packages->where('term', 'yearly') as $package)
                                <x-package-card :package="$package" />
                            @endforeach

                        </div>
                    </div>

                    <!-- ================= Lifetime Plans ================= -->
                    <div class="tab-pane fade" id="lifetime" role="tabpanel" aria-labelledby="lifetime-tab">
                        <div class="row g-4">

                            @foreach ($packages->where('term', 'lifetime') as $package)
                                <x-package-card :package="$package" />
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endsection
