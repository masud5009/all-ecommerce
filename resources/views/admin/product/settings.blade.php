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
    <div class="row">

        <div class="col-lg-6">
            <div class="form-group">
                <label for="physical_product">{{ __('Physical Product') }}</label>
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="physical_product" value="1" class="selectgroup-input"
                            @checked(@$product_setting->physical_product == 1)>
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="physical_product" value="0" class="selectgroup-input"
                            @checked(@$product_setting->physical_product == 0)>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label for="digital_product">{{ __('Digital Product') }}</label>
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="digital_product" value="1" class="selectgroup-input"
                            @checked(@$product_setting->digital_product == 1)>
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="digital_product" value="0" class="selectgroup-input"
                            @checked(@$product_setting->digital_product == 0)>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label for="guest_checkout">{{ __('Guest Checkout') }}</label>
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="guest_checkout" value="1" class="selectgroup-input"
                            @checked(@$product_setting->guest_checkout == 1)>
                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="guest_checkout" value="0" class="selectgroup-input"
                            @checked(@$product_setting->guest_checkout == 0)>
                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                    </label>
                </div>
                <small class="form-text text-muted">
                    {{ __('When enabled, users will see a Guest Checkout option on login page during checkout.') }}
                </small>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label for="contact_number">{{ __('Contact Number') }}</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control"
                    value="{{ @$product_setting->contact_number }}">
                <small class="form-text text-muted">
                    {{ __('This number will be displayed on the product details page.') }}
                </small>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label>{{ __('Contact Number Status') }}</label>
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="contact_number_status" value="1" class="selectgroup-input"
                            @checked(@$product_setting->contact_number_status == 1)>
                        <span class="selectgroup-button">{{ __('Show') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="contact_number_status" value="0" class="selectgroup-input"
                            @checked(@$product_setting->contact_number_status == 0)>
                        <span class="selectgroup-button">{{ __('Hide') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label for="whatsapp_number">{{ __('WhatsApp Number') }}</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control"
                    value="{{ @$product_setting->whatsapp_number }}">
                <small class="form-text text-muted">
                    {{ __('This WhatsApp number will be displayed on the product details page.') }}
                </small>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label>{{ __('WhatsApp Number Status') }}</label>
                <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                        <input type="radio" name="whatsapp_number_status" value="1" class="selectgroup-input"
                            @checked(@$product_setting->whatsapp_number_status == 1)>
                        <span class="selectgroup-button">{{ __('Show') }}</span>
                    </label>

                    <label class="selectgroup-item">
                        <input type="radio" name="whatsapp_number_status" value="0" class="selectgroup-input"
                            @checked(@$product_setting->whatsapp_number_status == 0)>
                        <span class="selectgroup-button">{{ __('Hide') }}</span>
                    </label>
                </div>
            </div>
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
