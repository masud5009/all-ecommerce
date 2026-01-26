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
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <form action="{{ route('admin.vendor.setting_update') }}" method="post">
            @csrf
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col-lg-4">
                            <div class="card-title">
                                <h5>{{ __('Settings') }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-8">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-lg-6 mx-auto">

                        <div class="form-group">
                            <div class="checkbox-div">
                                <label>
                                    <input type="checkbox" name="admin_approval"
                                        @checked(@$websiteInfo->admin_approval == 1)>{{ __('Needs Admin Approval') }}
                                </label>
                                <label>
                                    <input type="checkbox" name="email_verification_approval"
                                        @checked($websiteInfo->email_verification_approval == 1)>{{ __('Needs Email Verificaiton') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __(' Admin Approval Notice ') }}</label>
                            <textarea name="admin_approval_notice" class="form-control" id="" rows="4">{{ @$websiteInfo->admin_approval_notice }}</textarea>
                            <p class="text-warning">{{ __('This text will be visible in Vendor Dashboard') }}</p>
                        </div>

                    </div>
                </div>
                <div class="card-footer py-2">
                    <button class="btn btn-success">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
