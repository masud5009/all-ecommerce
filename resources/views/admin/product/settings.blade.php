@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Product Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Settings') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-6">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.settings_update') }}" method="post" id="productSettingForm">
                    @csrf
                    <div class="col-lg-8 mx-auto">

                        <div class="form-group">
                            <label for="physical_product">{{ __('Physical Product') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="physical_product" value="1" class="selectgroup-input"
                                        @checked(@$product_setting->physical_product == 1) id="trial_enable">
                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" name="physical_product" value="0" class="selectgroup-input"
                                        @checked(@$product_setting->physical_product == 0) id="trial_disable">
                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="digital_product">{{ __('Digital Product') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="digital_product" value="1" class="selectgroup-input"
                                        @checked(@$product_setting->digital_product == 1) id="trial_enable">
                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" name="digital_product" value="0" class="selectgroup-input"
                                        @checked(@$product_setting->digital_product == 0) id="trial_disable">
                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer py-2">
                <button class="btn btn-success" type="submit"
                    form="productSettingForm">{{ __('Save & Changes') }}</button>
            </div>
        </div>
    </div>
@endsection
