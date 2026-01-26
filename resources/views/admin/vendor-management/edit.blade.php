@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('Vendor Management') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('admin.vendor') }}">{{ __('Vendors') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ $vendor->username }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('Edit Vendor') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Edit Vendor') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.vendor') }}" class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul></ul>
                </div>
                <div class="col-lg-10 mx-auto">
                    <form id="ajaxForm2" action="{{ route('admin.vendor.update') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ __('Image') }}*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ !empty($vendor->image) ? asset('assets/img/vendors/' . $vendor->image) : asset('assets/admin/noimage.jpg') }}"
                                            alt="..." class="uploaded-img">
                                    </div>

                                    <div class="mt-3 mb-2">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            <i class="fas fa-upload"></i> {{ __('Upload Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Username') }}*</label>
                                    <input type="text" placeholder="{{ __('Enter Username') }}" name="username"
                                        value="{{ $vendor->username }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Email') }}*</label>
                                    <input type="email" placeholder="{{ __('Enter Email') }}" name="email"
                                        value="{{ $vendor->email }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="number" placeholder="{{ __('Enter Phone') }}" name="phone"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text" placeholder="{{ __('Enter Zip Code') }}" name="zip_code"
                                        class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{ $vendor->id }}">
                        </div>
                        <div class="row language-div">
                            @include('admin.include.languages')
                            @foreach ($languages as $lang)
                                @php
                                    $code = $lang->code;
                                    $vendor_info = App\Models\VendorInfo::where([
                                        ['language_id', $lang->id],
                                        ['vendor_id', $vendor->id],
                                    ])->first();
                                @endphp
                                <div class="row language-content {{ $lang->id == $defaultLang->id || @$vendor_info->language_id == $lang->id ? '' : 'd-none' }}"
                                    id="language_{{ $lang->id }}">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Name') }} ({{ $code }})*</label>
                                            <input type="text" placeholder="{{ __('Enter Name ') }}"
                                                name="{{ $lang->code }}_name" value="{{ @$vendor_info->name }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Address') }} ({{ $code }})</label>
                                            <input type="text" placeholder="{{ __('Enter Address') }}"
                                                value="{{ @$vendor_info->address }}" name="{{ $lang->code }}_address"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('City') }} ({{ $code }})</label>
                                            <input type="text" placeholder="{{ __('Enter City') }}"
                                                value="{{ @$vendor_info->city }}" name="{{ $lang->code }}_city"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('State') }} ({{ $code }})</label>
                                            <input type="text" placeholder="{{ __('Enter State') }}"
                                                value="{{ @$vendor_info->state }}" name="{{ $lang->code }}_state"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Country') }} ({{ $code }})</label>
                                            <input type="text" placeholder="{{ __('Enter Country') }}"
                                                value="{{ @$vendor_info->country }}" name="{{ $lang->code }}_country"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-3 d-flex justify-content-center">
                <button class="btn btn-success" id="submitBtn2" type="button"><i
                        class="icon-save"></i>{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
