@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Change Password') }}</li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Change Password') }}</h5>
            </div>
            <div class="card-body">
                <div class="col-lg-6 offset-lg-3">
                    <form id="ajaxEditForm" action="{{ route('admin.update_password') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Current Password*</label>
                            <input type="password" class="form-control" name="current_password">
                            <p id="editErr_current_password" class="mt-1 mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label>New Password*</label>
                            <input type="password" class="form-control" name="new_password">
                            <p id="editErr_new_password" class="mt-1 mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label>Confirm New Password*</label>
                            <input type="password" class="form-control" name="new_password_confirmation">
                            <p id="editErr_new_password_confirmation" class="mt-1 mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <button type="submit" id="updateBtn" class="btn btn-success py-2">{{__('Update')}}</button>
            </div>
        </div>
    </div>
@endsection
