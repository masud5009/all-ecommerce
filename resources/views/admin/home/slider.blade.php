@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Slider Section') }}</li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.home.slider.bulk_delete')" itemTextName="sliders" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Sliders') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add Slider') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($sliders) == 0)
                        <h5 class="text-center">{{ __('NO SLIDER FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Serial Number') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $key => $slider)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $slider->id }}">
                                            </td>
                                            <td>
                                                @if (!empty($slider->image))
                                                    <img src="{{ asset('assets/img/home_slider/' . $slider->image) }}"
                                                        alt="{{ $slider->title ?? 'Slider Image' }}" width="80">
                                                @else
                                                    <img src="{{ asset('assets/admin/noimage.jpg') }}"
                                                        alt="No image" width="80">
                                                @endif
                                            </td>
                                            <td>
                                                @if ($slider->status == 1)
                                                    <span class="badge bg-success changeStatusBtn"
                                                        data-id="{{ $slider->id }}" data-value="{{ $slider->status }}"
                                                        data-url="{{ route('admin.home.slider.status_change') }}">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger changeStatusBtn"
                                                        data-id="{{ $slider->id }}" data-value="{{ $slider->status }}"
                                                        data-url="{{ route('admin.home.slider.status_change') }}">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $slider->serial_number }}</td>
                                            <td>
                                                <div class="action-buttons product-list-actions">
                                                    <a href="#"
                                                        class="btn btn-sm editBtn edit-button product-action-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        data-id="{{ $slider->id }}"
                                                        data-image="{{ !empty($slider->image) ? asset('assets/img/home_slider/' . $slider->image) : asset('assets/admin/noimage.jpg') }}"
                                                        data-title="{{ $slider->title }}"
                                                        data-sub_title="{{ $slider->sub_title }}"
                                                        data-description="{{ $slider->description }}"
                                                        data-button_text_1="{{ $slider->button_text_1 }}"
                                                        data-button_url_1="{{ $slider->button_url_1 }}"
                                                        data-button_text_2="{{ $slider->button_text_2 }}"
                                                        data-button_url_2="{{ $slider->button_url_2 }}"
                                                        data-image_left_badge_title="{{ $slider->image_left_badge_title }}"
                                                        data-image_left_badge_sub_title="{{ $slider->image_left_badge_sub_title }}"
                                                        data-image_right_badge_title="{{ $slider->image_right_badge_title }}"
                                                        data-image_right_badge_sub_title="{{ $slider->image_right_badge_sub_title }}"
                                                        data-serial_number="{{ $slider->serial_number }}"
                                                        data-status="{{ $slider->status }}">
                                                        <span class="fas fa-edit"></span>
                                                        <span class="product-action-label">{{ __('Edit') }}</span>
                                                    </a>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.home.slider.delete') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $slider->id }}" name="slider_id">
                                                        <button
                                                            class="btn btn-sm deleteBtn delete-button product-action-btn"
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
    @includeIf('admin.home.create')
    @includeIf('admin.home.edit')
@endsection
