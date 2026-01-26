@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('General Settings') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row px-3">
        <form id="ajaxForm" action="{{ route('admin.website_setting.update') }}" method="post"
            enctype="multipart/form-data" class="general-settings">
            @csrf
            <div class="row">
                <!-- Website Information -->
                <div class="col-lg-12 pb-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5><i class="fas fa-info-circle"></i>{{ __('Website Information') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- logo -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">{{ __('Logo') }}*</label>
                                        <br>
                                        <div class="thumb-preview">
                                            <img src="{{ $data->website_logo ? asset('assets/front/img/' . $data->website_logo) : asset('assets/admin/noimage.jpg') }}"
                                                alt="..." class="uploaded-img">
                                        </div>
                                        <input type="file" class="img-input" name="website_logo" id="thumbnailInput">
                                        <p id="err_website_logo" class="text-danger em"></p>
                                    </div>
                                </div>
                                <!-- favicon -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="">{{ __('Favicon') }}*</label>
                                        <br>
                                        <div class="thumb-preview2">
                                            <img src="{{ $data->favicon ? asset('assets/front/img/' . $data->favicon) : asset('assets/admin/noimage.jpg') }}"
                                                alt="..." class="uploaded-img2">
                                        </div>
                                        <input type="file" class="img-input2" name="favicon" id="thumbnailInput2">
                                        <p id="err_favicon" class="text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Website Title') }}*</label>
                                        <input type="text" value="{{ @$data->website_title }}"
                                            class="form-control err_website_title" name="website_title">
                                        <p id="err_website_title" class="text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- timezone -->
                <div class="col-lg-6 pb-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5><i class="fas fa-clock"></i>{{ __('Timezone') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Timezone') }}*</label>
                                        <select name="timezone" id="" class="form-control select2 err_timezone">
                                            @foreach ($timeZones as $timeZone)
                                                <option value="{{ $timeZone['timezone'] }}">{{ $timeZone['timezone'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p id="err_timezone" class="text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Website Appearance -->
                <div class="col-lg-6 pb-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5><i class="fas fa-palette"></i>{{ __('Website Appearance') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Websit Color') }}*</label>
                                        <input type="text" name="website_color" class="form-control err_website_color"
                                            value="#FF0000FF" data-jscolor="{}">
                                        <p id="err_website_color" class="text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currency information -->
                <div class="col-lg-6 pb-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5><i class="fas fa-dollar-sign"></i>{{ __('Currency Information') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Currency Symbol') }}*</label>
                                        <input type="text" name="currency_symbol"
                                            class="form-control err_currency_symbol"
                                            value="{{ @$data->currency_symbol }}">
                                        <p id="err_currency_symbol" class="text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Currency Symbol Position') }}*</label>
                                        <select name="currency_symbol_position"
                                            class="form-select err_currency_symbol_position">
                                            <option selected disabled>{{ __('Select Currency Position') }}</option>
                                            <option @if (@$data->currency_symbol_position === 'left') selected @endif value="left">
                                                {{ __('Left') }}</option>
                                            <option @if (@$data->currency_symbol_position === 'right') selected @endif value="right">
                                                {{ __('Right') }}</option>
                                        </select>
                                        <p id="err_currency_symbol_position" class="text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Currency Text') }}*</label>
                                        <input type="text" name="currency_text" class="form-control err_currency_text"
                                            value="{{ @$data->currency_text }}">
                                        <p id="err_currency_text" class="text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Currency Text Position') }}*</label>
                                        <select name="currency_text_position"
                                            class="form-select err_currency_text_position">
                                            <option selected disabled>{{ __('Select Currency Position') }}</option>
                                            <option @if (@$data->currency_text_position === 'left') selected @endif value="left">
                                                {{ __('Left') }}</option>
                                            <option @if (@$data->currency_text_position === 'left') selected @endif value="right">
                                                {{ __('Right') }}</option>
                                        </select>
                                        <p id="err_currency_text_position" class="text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Currency Rate') }}*</label>
                                        <div class="input-group">
                                            <span class="input-group-text">1 USD =</span>
                                            <input type="text" name="currency_rate"
                                                class="form-control err_currency_rate"
                                                value="{{ @$data->currency_rate }}"
                                                aria-label="Amount (to the nearest dollar)"
                                                placeholder="{{ __('Enter amount') }}" pattern="^\d+(\.\d{1,2})?$"
                                                required title="Please enter a valid amount, e.g., 10 or 10.50">
                                            <span class="input-group-text">{{ @$data->currency_text }}</span>
                                        </div>
                                        <p id="err_currency_rate" class="text-danger em"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end py-3">
                <button class="btn btn-success" type="button" id="submitBtn">{{ __('Save Changes') }}</button>
            </div>
        </form>
    </div>
@endsection
{{-- @section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    moment().format();
</script>
@endsection --}}
