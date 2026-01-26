@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('User Managment') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('admin.user') }}">{{ __('Registered Users') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ $user->username }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('Edit User') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Edit User') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.user') }}" class="btn btn-primary btn-sm float-lg-end float-left">
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
                    <form id="ajaxForm2" action="{{ route('admin.user.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ __('Image') }}*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                            class="uploaded-img">
                                    </div>

                                    <div class="mt-3 mb-2">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            <i class="fas fa-upload"></i> {{ __('Upload Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Name') }}*</label>
                                    <input type="text" placeholder="{{ __('Enter Name ') }}" name="name"
                                        value="{{ $user->name }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Username') }}*</label>
                                    <input type="text" placeholder="{{ __('Enter Username') }}" name="username"
                                        value="{{ $user->username }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Email') }}*</label>
                                    <input type="email" placeholder="{{ __('Enter Email') }}" name="email"
                                        value="{{ $user->email }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Status') }}*</label>
                                    <select name="status" class="form-select">
                                        <option selected disabled>{{ __('Select Status') }}</option>
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Dactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Phone') }}</label>
                                    <input type="number" placeholder="{{ __('Enter Phone') }}" name="phone"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" placeholder="{{ __('Enter Address') }}" name="address"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" placeholder="{{ __('Enter City') }}" name="city"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" placeholder="{{ __('Enter State') }}" name="state"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" placeholder="{{ __('Enter Country') }}" name="country"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text" placeholder="{{ __('Enter Zip Code') }}" name="zip_code"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-3 d-flex justify-content-center">
                <button class="btn btn-success" id="submitBtn2" type="button"><i
                        class="icon-save"></i>{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
@endsection
