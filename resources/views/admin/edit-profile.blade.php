@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Edit Profile') }}</li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Edit Profile') }}</h5>
            </div>
            <div class="card-body">
                <div class="col-lg-10 mx-auto">
                    <form id="editProfileForm" action="{{ route('admin.update_profile') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if (!empty($adminInfo->image))
                                            <img src="{{ asset('assets/admin/img/' . $adminInfo->image) }}" alt="image"
                                                class="uploaded-img">
                                        @else
                                            <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                                class="uploaded-img">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn ">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    @if ($errors->has('image'))
                                        <p class="mb-0 text-danger">{{ $errors->first('image') }}</p>
                                    @endif
                                    <p class="text-warning mt-2 mb-2">{{ __('Upload squre size image for best quality.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="username">{{ __('Username') }}*</label>
                                    <input type="text" value="{{ $adminInfo->username }}"
                                        class="form-control {{ customValid('username', $errors) }}" name="username"
                                        id="username">
                                    @if ($errors->has('username'))
                                        <p class="mb-0 text-danger">{{ $errors->first('username') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}*</label>
                                    <input type="text" value="{{ $adminInfo->email }}"
                                        class="form-control {{ customValid('email', $errors) }}" name="email"
                                        id="email">
                                    @if ($errors->has('email'))
                                        <p class="mb-0 text-danger">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Full Name') }}*</label>
                                    <input type="text" value="{{ $adminInfo->name }}" class="form-control"
                                        name="name" id="name" {{ customValid('name', $errors) }}>
                                    @if ($errors->has('name'))
                                        <p class="mb-0 text-danger">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="address">{{ __('Address') }}</label>
                                    <input type="text" value="{{ $adminInfo->address }}"
                                        class="form-control {{ customValid('address', $errors) }}" name="address"
                                        id="address">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="details">{{ __('Details') }}</label>
                                    <textarea name="details" id="" cols="30" rows="6"
                                        class="form-control {{ customValid('details', $errors) }}">{{ $adminInfo->detials }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <button type="submit" form="editProfileForm" class="btn btn-success py-2">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
