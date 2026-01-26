@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Blog Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Categories') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.blog.category.bulk_delete')" itemTextName="__('categories')"/>
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Categories') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
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
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th scope="col">
                                    <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Serial Number') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
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
                                            data-url="{{ route('admin.blog.category_status_change') }}">{{ __('Active') }}</span>
                                            @else
                                            <span class="badge bg-danger changeStatusBtn"
                                            data-id="{{ $category->id }}" data-value="{{ $category->status }}"
                                            data-url="{{ route('admin.blog.category_status_change') }}">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->serial_number }}</td>
                                        <td class="action-buttons">
                                            <a href="" class="btn btn-sm editBtn edit-button" data-bs-toggle="modal"
                                                data-bs-target="#editModal" data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-serial_number="{{ $category->serial_number }}"
                                                data-status="{{ $category->status }}">
                                                <span class="fas fa-edit"></span>
                                            </a>
                                            <form class="deleteForm d-inline-block"
                                                action="{{ route('admin.blog.category.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $category->id }}" name="category_id">
                                                <button class="btn btn-sm deleteBtn delete-button" type="button">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </form>
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
    @includeIf('admin.blog.category.create')
    @includeIf('admin.blog.category.edit')
@endsection
