@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ request()->url() }}">{{ __('Maintenance Mode') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Update Maintenance Mode') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.website_setting') }}"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-8 mx-auto">
                    <form id="ajaxForm" action="{{ route('admin.maintenance_update') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card upload-card">
                                    <div class="card-body">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="">{{ __('Featured Image') }}*</label>
                                                <br>
                                                <div class="thumb-preview">
                                                    <img src="{{ !empty($data->maintenance_image) ? asset('assets/img/' . $data->maintenance_image) : asset('assets/admin/noimage.jpg') }}"
                                                        alt="..." class="uploaded-img">
                                                </div>
                                                <input type="file" class="img-input" name="maintenance_image"
                                                    id="thumbnailInput">
                                                <p id="err_maintenance_image" class="text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Maintenance Status') }}*</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="maintenance_status" value="1"
                                                class="selectgroup-input" @if ($data->maintenance_status == 1) checked @endif>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="maintenance_status" value="0"
                                                class="selectgroup-input" @if ($data->maintenance_status == 0) checked @endif>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Maintenance Message*</label>
                                    <textarea name="maintenance_message" id="editor" rows="6" class="form-control err_maintenance_message">{{ @$data->maintenance_message }}</textarea>
                                    <p id="err_maintenance_message" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Bypass Token*</label>
                                    <input type="text" class="form-control err_bypass_token" name="bypass_token"
                                        value="{{ @$data->bypass_token }}">
                                    <p id="err_bypass_token" class="text-danger em"></p>
                                    <p class="text-info">
                                        {{ __('During maintenance, you can access the system through this token.') }}</p>
                                    <span class="text-warning">{{ route('frontend.index') }}/{bypass_token}</span>
                                    <p class="text-danger">{{ __('Don not use special character in token.') }}</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-3 d-flex justify-content-center">
                <button class="btn btn-success" id="submitBtn" type="button">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
