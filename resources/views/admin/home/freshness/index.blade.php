@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Home Page Content') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Freshness Section') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="accordion" id="freshnessAccordion">
            <div class="accordion-item mb-3 border-0 shadow rounded">
                <h2 class="accordion-header" id="freshnessSectionHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#freshnessSectionCollapse" aria-expanded="false"
                        aria-controls="freshnessSectionCollapse">
                        {{ __('Freshness Section Content') }}
                    </button>
                </h2>
                <div id="freshnessSectionCollapse" class="accordion-collapse collapse"
                    aria-labelledby="freshnessSectionHeading" data-bs-parent="#freshnessAccordion">
                    <div class="accordion-body">
                        <form action="{{ route('admin.home.freshness.section_update') }}" method="POST" id="ajaxForm3"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="language_id" value="{{ $selectedLanguage->id }}">

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>{{ __('Language') }}</label>
                                    <select class="form-select"
                                        onchange="if(this.value){window.location='{{ route('admin.home.freshness') }}?language='+this.value}">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->code }}"
                                                {{ $selectedLanguage->id == $language->id ? 'selected' : '' }}>
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Section Title') }}</label>
                                        <input type="text" class="form-control" name="features_title"
                                            value="{{ old('features_title', $section->features_title ?? '') }}"
                                            placeholder="{{ __('Enter section title') }}">
                                        <p id="err_features_title" class="text-danger em"></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Section Subtitle') }}</label>
                                        <input type="text" class="form-control" name="features_subtitle"
                                            value="{{ old('features_subtitle', $section->features_subtitle ?? '') }}"
                                            placeholder="{{ __('Enter section subtitle') }}">
                                        <p id="err_features_subtitle" class="text-danger em"></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Section Description') }}</label>
                                        <textarea class="form-control" name="features_text" rows="4" placeholder="{{ __('Enter section description') }}">{{ old('features_text', $section->features_text ?? '') }}</textarea>
                                        <p id="err_features_text" class="text-danger em"></p>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="">{{ __('Image') }} <span
                                                class="text-danger">**</span></label>
                                        <br>
                                        <div class="thumb-preview">
                                            <img src="{{ $section->features_image ? asset('assets/img/home_section/' . $section->features_image) : asset('assets/admin/noimage.jpg') }}"
                                                alt="..." class="uploaded-img">
                                        </div>

                                    </div>
                                </div>
                                <input type="file" class="img-input" name="image" id="thumbnailInput">
                                <p id="err_image" class="text-danger em mt-2"></p>
                            </div>
                        </form>
                    </div>
                    <div class="accordion-footer p-3 text-end border-top">
                        <button type="button" class="btn btn-primary" id="submitBtn3">{{ __('Update Section') }}</button>
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 shadow rounded">
                <h2 class="accordion-header" id="freshnessItemsHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#freshnessItemsCollapse" aria-expanded="false"
                        aria-controls="freshnessItemsCollapse">
                        {{ __('Freshness Items') }}
                    </button>
                </h2>
                <div id="freshnessItemsCollapse" class="accordion-collapse collapse" aria-labelledby="freshnessItemsHeading"
                    data-bs-parent="#freshnessAccordion">
                    <div class="accordion-body">
                        <x-bulk-delete :url="route('admin.home.freshness.bulk_delete')" itemTextName="items" />
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add Item') }}
                            </a>
                        </div>

                        <div class="col-lg-12 mx-auto">
                            @if (count($items) == 0)
                                <h5 class="text-center">{{ __('NO ITEM FOUND') . '!' }}</h5>
                            @else
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-striped">
                                        <thead>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>
                                            <th scope="col">{{ __('Icon') }}</th>
                                            <th scope="col">{{ __('Title') }}</th>
                                            <th scope="col">{{ __('Position') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">{{ __('Serial Number') }}</th>
                                            <th scope="col">{{ __('Actions') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        @if (!empty($item->icon))
                                                            <i class="{{ $item->icon }}"></i>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->title }}</td>
                                                    <td>{{ ucfirst($item->position) }}</td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <span class="badge bg-success changeStatusBtn"
                                                                data-id="{{ $item->id }}"
                                                                data-value="{{ $item->status }}"
                                                                data-url="{{ route('admin.home.freshness.status_change') }}">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge bg-danger changeStatusBtn"
                                                                data-id="{{ $item->id }}"
                                                                data-value="{{ $item->status }}"
                                                                data-url="{{ route('admin.home.freshness.status_change') }}">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->serial_number }}</td>
                                                    <td>
                                                        <div class="action-buttons product-list-actions">
                                                            <a href="#"
                                                                class="btn btn-sm editBtn edit-button product-action-btn"
                                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                                data-id="{{ $item->id }}"
                                                                data-title="{{ $item->title }}"
                                                                data-text="{{ $item->text }}"
                                                                data-icon="{{ $item->icon }}"
                                                                data-position="{{ $item->position }}"
                                                                data-serial_number="{{ $item->serial_number }}"
                                                                data-status="{{ $item->status }}">
                                                                <span class="fas fa-edit"></span>
                                                                <span
                                                                    class="product-action-label">{{ __('Edit') }}</span>
                                                            </a>
                                                            <form class="deleteForm d-inline-block"
                                                                action="{{ route('admin.home.freshness.delete') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" value="{{ $item->id }}"
                                                                    name="item_id">
                                                                <button
                                                                    class="btn btn-sm deleteBtn delete-button product-action-btn"
                                                                    type="button">
                                                                    <span class="fas fa-trash"></span>
                                                                    <span
                                                                        class="product-action-label">{{ __('Delete') }}</span>
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
                </div>
            </div>
        </div>
    </div>

    @includeIf('admin.home.freshness.create')
    @includeIf('admin.home.freshness.edit')
@endsection
