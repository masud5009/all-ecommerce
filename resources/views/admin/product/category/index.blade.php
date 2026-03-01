@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Product Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Categories') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.product.category_bulk_delete')" itemTextName="categories" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Categories') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add Category') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($categories) == 0)
                        <h5 class="text-center">{{ __('NO CATEGORY FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Serial Number') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $category->id }}">
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                @if ($category->status == 1)
                                                    <span class="badge bg-success changeStatusBtn"
                                                        data-id="{{ $category->id }}" data-value="{{ $category->status }}"
                                                        data-url="{{ route('admin.product.category_status_change') }}">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger changeStatusBtn"
                                                        data-id="{{ $category->id }}" data-value="{{ $category->status }}"
                                                        data-url="{{ route('admin.product.category_status_change') }}">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $category->serial_number }}</td>
                                            <td>
                                                <div class="action-buttons product-list-actions">
                                                    <a href="" class="btn btn-sm editBtn edit-button product-action-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                        data-icon="{{ $category->icon }}"
                                                        data-serial_number="{{ $category->serial_number }}"
                                                        data-status="{{ $category->status }}">
                                                        <span class="fas fa-edit"></span>
                                                        <span class="product-action-label">{{ __('Edit') }}</span>
                                                    </a>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.product.category_delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $category->id }}" name="category_id">
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
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer py-2">
            </div>
        </div>
    </div>
    @includeIf('admin.product.category.create')
    @includeIf('admin.product.category.edit')
@endsection
