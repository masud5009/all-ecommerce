@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Role Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('All Admins') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('All Admins') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="info-header-content">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                            data-href="{{ route('admin.all_admins.bulkdelete') }}">
                            <i class="icon-bin2"></i> {{ __('Delete') }}
                        </button>
                        <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                            data-bs-target="#createModal">
                            <i class="fas fa-plus"></i> {{ __('Add Admin') }}
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($admins) == 0)
                        <h5 class="text-center">{{ __('NO ADMIN FOUND') . '!' }}</h5>
                    @else
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th scope="col">
                                    <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Profile') }}</th>
                                <th scope="col">{{ __('Username') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Role') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($admins as $key => $admin)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $admin->id }}">
                                        </td>
                                        <td>
                                            <img src="{{ asset('assets/img/admins/' . $admin->image) }}" alt=""
                                                style="width: 50px">
                                        </td>
                                        <td>{{ $admin->username }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->adminRole->name }}</td>
                                        <td>{{ $admin->status }}</td>
                                        <td>
                                            <div class="action-buttons product-list-actions">
                                                <a href="#" class="btn btn-sm editBtn edit-button product-action-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $admin->id }}" data-email="{{ $admin->email }}"
                                                    data-first_name="{{ $admin->first_name }}"
                                                    data-last_name="{{ $admin->last_name }}"
                                                    data-image="{{ asset('assets/img/admins/' . $admin->image) }}"
                                                    data-role="{{ $admin->role }}" data-username="{{ $admin->username }}">
                                                    <span class="fas fa-edit"></span>
                                                    <span class="product-action-label">{{ __('Edit') }}</span>
                                                </a>
                                                <form class="deleteForm d-inline-block"
                                                    action="{{ route('admin.all_admins.delete') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $admin->id }}" name="admin_id">
                                                    <button class="btn btn-sm deleteBtn delete-button product-action-btn"
                                                        type="button">
                                                        <span class="fas fa-trash"></span>
                                                        <span class="product-action-label">{{ __('Delete') }}</span>
                                                    </button>
                                                </form>
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
    @includeIf('admin.admin-managment.add-admins.create')
    @includeIf('admin.admin-managment.add-admins.edit')
@endsection
