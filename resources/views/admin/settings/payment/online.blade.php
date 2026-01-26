@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Payment Gateway') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Online Gateway') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row px-3 payment-container">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Payment Gateways') }}</h5>
                </div>
                <div class="card-body">
                    <!-- payment methods-->
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#stripe" type="button"
                            role="tab" aria-selected="true">
                            {{ __('Stripe') }}
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#paypal" type="button"
                            role="tab" aria-selected="false">
                            {{ __('PayPal') }}
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#paytm" type="button" role="tab"
                            aria-selected="false">
                            {{ __('Paytm') }}
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#Instamojo" type="button"
                            role="tab" aria-selected="false">
                            {{ __('Instamojo') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content Column -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Stripe Tab Content -->
                <div class="tab-pane fade show active" id="stripe" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Stripe') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.online_gateway.stripe') }}" method="post" id="stripeForm">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="stripe_status" value="1"
                                                    class="selectgroup-input" @checked($stripe->status == 1)>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>

                                            <label class="selectgroup-item">
                                                <input type="radio" name="stripe_status" value="0"
                                                    class="selectgroup-input" @checked($stripe->status == 0)>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter stripe key') }}" name="stripe_key"
                                            value="{{ @$stripe_info['key'] }}"
                                            class="form-control {{ customValid('stripe_key', $errors) }}">
                                        @if ($errors->has('stripe_key'))
                                            <p class="mb-0 text-danger">{{ $errors->first('stripe_key') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter stripe secret') }}"
                                            name="stripe_secret" value="{{ @$stripe_info['secret'] }}"
                                            class="form-control {{ customValid('stripe_secret', $errors) }}">
                                        @if ($errors->has('stripe_secret'))
                                            <p class="mb-0 text-danger">{{ $errors->first('stripe_secret') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" form="stripeForm">{{ __('Save & Changes') }}</button>
                        </div>
                    </div>
                </div>

                <!-- PayPal Tab Content -->
                <div class="tab-pane fade" id="paypal" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Paypal') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.online_gateway.paypal') }}" method="post" id="paypalForm">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="paypal_status" value="1"
                                                    class="selectgroup-input" @checked($paypal->status == 1)>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>

                                            <label class="selectgroup-item">
                                                <input type="radio" name="paypal_status" value="0"
                                                    class="selectgroup-input" @checked($paypal->status == 0)>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $options = ['0' => 'Sandbox', '1' => 'Live'];
                                @endphp
                                <x-text-input col="12" name="paypal_sandbox_status" type="custom-select"
                                    :dataInfo="$options" value="{{ @$paypal_info['sandbox_status'] }}" />
                                <x-text-input col="12" name="paypal_client_id" placeholder="Enter paypal client ID"
                                    value="{{ @$paypal_info['client_id'] }}" />
                                <x-text-input col="12" name="paypal_client_secret"
                                    placeholder="Enter paypal client secret"
                                    value="{{ @$paypal_info['client_secret'] }}" />
                            </form>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" form="paypalForm">{{ __('Save & Changes') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Paytm Tab Content -->
                <div class="tab-pane fade" id="paytm" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Paytm') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.online_gateway.paytm') }}" method="post" id="paytmForm">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="paytm_status" value="1"
                                                    class="selectgroup-input" @checked($paytm->status == 1)>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>

                                            <label class="selectgroup-item">
                                                <input type="radio" name="paytm_status" value="0"
                                                    class="selectgroup-input" @checked($paytm->status == 0)>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $options = ['local' => 'Local', 'production' => 'Production'];
                                @endphp
                                <x-text-input col="12" name="paytm_environment_mode"
                                    value="{{ @$paytm_info['environment'] }}" type="custom-select" :dataInfo="$options" />
                                <x-text-input col="12" name="paytm_merchant_key"
                                    value="{{ @$paytm_info['merchant_key'] }}" placeholder="Enter merchant key" />
                                <x-text-input col="12" name="paytm_merchant_MID"
                                    value="{{ @$paytm_info['merchant_mid'] }}" placeholder="Enter merchant MID" />
                                <x-text-input col="12" name="paytm_merchant_website"
                                    value="{{ @$paytm_info['merchant_website'] }}"
                                    placeholder="Enter merchant website" />
                                <x-text-input col="12" name="paytm_industry_type"
                                    value="{{ @$paytm_info['industry_type'] }}" placeholder="Enter industry type" />
                            </form>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" form="paytmForm">{{ __('Save & Changes') }}</button>
                        </div>
                    </div>
                </div>
                <!-- Instamojo Tab Content -->
                <div class="tab-pane fade" id="Instamojo" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Instamojo') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.online_gateway.instamojo')}}" method="post" id="instaForm">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="insta_status" value="1"
                                                    @checked($instamojo->status == 1) class="selectgroup-input">
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>

                                            <label class="selectgroup-item">
                                                <input type="radio" name="insta_status" value="0"
                                                    @checked($instamojo->status == 0) class="selectgroup-input">
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $options = ['local' => 'Local', 'production' => 'Production'];
                                @endphp
                                <x-text-input col="12" name="insta_sandbox_status"
                                    value="{{ $instamojo_info['sandbox_status'] }}" type="custom-select"
                                    :dataInfo="$options" />
                                <x-text-input col="12" name="insta_api_key" value="{{ $instamojo_info['key'] }}"
                                    placeholder="Enter instamojo api key" />
                                <x-text-input col="12" name="insta_auth_token"
                                    value="{{ $instamojo_info['token'] }}" placeholder="Enter instamojo auth token" />
                            </form>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" form="instaForm">{{ __('Save & Changes') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
