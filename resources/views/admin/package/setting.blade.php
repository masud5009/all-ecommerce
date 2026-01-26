@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Package Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <form action="{{ route('admin.package.setting_update') }}" method="post">
            @csrf
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col-lg-4">
                            <div class="card-title">
                                <h5>{{ __('Updatge Setings') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="col-lg-8 mx-auto">
                        <div class="form-group">
                            <label>{{ __('Remind Before(Days)') }}*</label>
                            <input type="text" placeholder="{{ __('Enter number of days') }}" name="package_expire_day"
                                class="form-control {{ customValid('package_expire_day', $errors) }}"
                                value="{{ $websiteInfo->package_expire_day }}">
                            @if ($errors->has('package_expire_day'))
                                <p class="mb-0 text-danger">{{ $errors->first('package_expire_day') }}</p>
                            @endif
                            <p class="text-warning">
                                {{ __('Specify how many days before you want to remind your customers about subscription expiration. (via mail)') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
