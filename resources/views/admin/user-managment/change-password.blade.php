@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">
                <a href="">{{ __('User Managment') }}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('admin.user') }}">
                    {{ __('Registered Users') }}
                </a>
            </li>
            <li class="breadcrumb-item active">
                <a href="">{{ __('Change Password') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Change Password') }}</h5>
            </div>
            <div class="card-body">
                <div class="col-lg-6 offset-lg-3">
                    <form id="ajaxForm" action="{{ route('admin.user.update_password', ['id' => $user_id]) }}"
                        method="post">
                        @csrf

                        <x-text-input required="**" label="{{ __('New Password') }}" name="new_password" type="password"
                            action="store" />
                        <x-text-input required="**" label="{{ __('Confirm New Password') }}"
                            name="new_password_confirmation" type="password" action="store" />
                    </form>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <button type="submit" id="submitBtn" class="btn btn-success py-2">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
