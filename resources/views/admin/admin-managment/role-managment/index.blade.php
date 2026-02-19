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
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Roles') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                            data-bs-target="#createModal">
                            <i class="fas fa-plus"></i> {{ __('Add Role') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($data) == 0)
                        <h5 class="text-center">{{ __('NO ROLE FOUND') . '!' }}</h5>
                    @else
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th scope="col">{{ __('#') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Permission') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $role)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <a href="{{route('admin.role_managment.permission')}}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                                {{ __('Permission') }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="action-buttons product-list-actions">
                                                <a href="" class="btn btn-sm editBtn edit-button product-action-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}">
                                                    <span class="fas fa-edit"></span>
                                                    <span class="product-action-label">{{ __('Edit') }}</span>
                                                </a>
                                                <form class="deleteForm d-inline-block"
                                                    action="{{ route('admin.role_managment.delete') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $role->id }}" name="role_id">
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
    @includeIf('admin.admin-managment.role-managment.create')
    @includeIf('admin.admin-managment.role-managment.edit')
@endsection
