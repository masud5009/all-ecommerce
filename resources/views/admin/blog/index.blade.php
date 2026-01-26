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
                <a href="#">{{ __('Blog') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Blog') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="info-header-content">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                                data-href="{{ route('admin.blog.bulk_delete') }}">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.blog.create') }}"
                                class="btn btn-primary btn-sm float-lg-end float-left">
                                <i class="fas fa-plus"></i> Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($blogs) == 0)
                        <h5 class="text-center">{{ __('NO BLOGS FOUND') }} !</h5>
                    @else
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <th scope="col">
                                    <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Category') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($blogs as $key => $blog)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $blog->id }}">
                                        </td>
                                        <td>{{ $blog->title }}</td>
                                        <td>{{ $blog->categoryName }}</td>
                                        <td>
                                            @if ($blog->status == 1)
                                                <span class="badge bg-success changeStatusBtn"
                                                    data-id="{{ $blog->id }}" data-value="{{ $blog->status }}"
                                                    data-url="{{ route('admin.blog.status_change') }}">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger changeStatusBtn" data-id="{{ $blog->id }}"
                                                    data-value="{{ $blog->status }}"
                                                    data-url="{{ route('admin.blog.status_change') }}">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <a href="{{ route('admin.blog.edit', ['id' => $blog->id]) }}"
                                                class="btn btn-sm edit-button">
                                                <span class="fas fa-edit"></span>
                                            </a>
                                            <form class="deleteForm d-inline-block"
                                                action="{{ route('admin.blog.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $blog->id }}" name="blog_id">
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
@endsection
