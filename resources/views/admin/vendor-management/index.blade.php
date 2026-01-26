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
                <a href="#">{{ __('Vendors') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.vendor.bulk_delete')" itemTextName="__('vendors')" />
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Vendors') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">

                        <a href="#" data-bs-toggle="modal" data-bs-target="#createModal"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-plus"></i> {{ __('Add') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($vendors) == 0)
                        <h5 class="text-center">{{ __('NO VENDORS FOUND') . '!' }}</h5>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <th scope="col">
                                    <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Username') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Email Status') }}</th>
                                <th scope="col">{{ __('Account Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $key => $vendor)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $vendor->id }}">
                                        </td>
                                        <td>{{ $vendor->username }}</td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>
                                            @if ($vendor->is_verified == 1)
                                                <span class="badge bg-success changeStatusBtn"
                                                    data-id="{{ $vendor->id }}" data-value="{{ $vendor->is_verified }}"
                                                    data-url="{{ route('admin.vendor.email_status_change') }}">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger changeStatusBtn" data-id="{{ $vendor->id }}"
                                                    data-value="{{ $vendor->is_verified }}"
                                                    data-url="{{ route('admin.vendor.email_status_change') }}">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($vendor->is_active == 1)
                                                <span class="badge bg-success changeStatusBtn"
                                                    data-id="{{ $vendor->id }}" data-value="{{ $vendor->is_active }}"
                                                    data-url="{{ route('admin.vendor.account_status_change') }}">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger changeStatusBtn" data-id="{{ $vendor->id }}"
                                                    data-value="{{ $vendor->is_active }}"
                                                    data-url="{{ route('admin.vendor.account_status_change') }}">{{ __('Inactive') }}</span>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.vendor.edit', ['username' => $vendor->username]) }}">
                                                        {{ __('Edit') }}</a>

                                                    <a class="dropdown-item" href="#">{{ __('Change Password') }}</a>

                                                    <form class="deleteForm" action="{{ route('admin.vendor.delete') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $vendor->id }}" name="vendor_id">
                                                        <a class="dropdown-item deleteBtn" type="button">
                                                            {{ __('Delete') }}
                                                        </a>
                                                    </form>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="card-footer py-2">
            </div>
        </div>
    </div>
    @includeIf('admin.vendor-management.create')
@endsection
