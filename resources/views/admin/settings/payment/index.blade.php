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
        </ol>
    </nav>

    <div class="row px-3">
        <div class="col-lg-10 mx-auto py-5">
            <div class="row gateway-container">
                <div class="col-lg-6">
                    <a href="{{ route('admin.online_gateway') }}">
                        <div class="card">
                            <div class="card-body py-5">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <i class="fas fa-wifi"></i>
                                    <h5 class="text-uppercase">{{ __('Online Gateway') }}</h5>
                                    <span>{{ __('Total Gateway') }}: {{$totalG}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('admin.online_gateway') }}">
                        <div class="card">
                            <div class="card-body py-5">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <i class="fas fa-ban"></i>
                                    <h5 class="text-uppercase">{{ __('Offline Gateway') }}</h5>
                                    <span>{{ __('Total Gateway') }}: 9</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
