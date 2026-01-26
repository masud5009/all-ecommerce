@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><span class="fas fa-home"></span></a></li>
            <li class="breadcrumb-item active">
                <a href="">{{ __('Role Managment') }}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="">{{ __('Role & Permission') }}</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="">{{ __('Permissions') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Permissions') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.role_managment') }}"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills mt-2">
                            <label class="selectgroup-item">
                                <input type="hidden" name="permissions[]" value="Dashboard" class="selectgroup-input">
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Settings" class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Settings') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="POS" class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('POS') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Sales Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Sales Management') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Product Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Product Management') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Inventory Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Inventory Management') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Blog Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Blog Management') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Package Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Package Management') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="permissions[]" value="Language Management"
                                    class="selectgroup-input">
                                <span class="selectgroup-button">{{ __('Language Management') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer py-2">
                <button class="btn btn-success">{{ __('Save & Changes') }}</button>
            </div>
        </div>
    </div>
@endsection
