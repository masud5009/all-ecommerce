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
                <a href="#">{{ __('Subcategories') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.product.subcategory_bulk_delete')" itemTextName="subcategories" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Subcategories') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        @include('admin.partials.languages')
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add Subcategory') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($subcategories) == 0)
                        <h5 class="text-center">{{ __('NO SUBCATEGORY FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Category') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Serial Number') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($subcategories as $subcategory)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $subcategory->id }}">
                                            </td>
                                            <td>{{ $subcategory->category->name ?? __('N/A') }}</td>
                                            <td>{{ $subcategory->name }}</td>
                                            <td>
                                                @if ($subcategory->status == 1)
                                                    <span class="badge bg-success changeStatusBtn"
                                                        data-id="{{ $subcategory->id }}" data-value="{{ $subcategory->status }}"
                                                        data-url="{{ route('admin.product.subcategory_status_change') }}">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger changeStatusBtn"
                                                        data-id="{{ $subcategory->id }}" data-value="{{ $subcategory->status }}"
                                                        data-url="{{ route('admin.product.subcategory_status_change') }}">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $subcategory->serial_number }}</td>
                                            <td>
                                                <div class="action-buttons product-list-actions">
                                                    <a href="" class="btn btn-sm editBtn edit-button product-action-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                                        data-id="{{ $subcategory->id }}"
                                                        data-category_id="{{ $subcategory->category_id }}"
                                                        data-name="{{ $subcategory->name }}"
                                                        data-serial_number="{{ $subcategory->serial_number }}"
                                                        data-status="{{ $subcategory->status }}">
                                                        <span class="fas fa-edit"></span>
                                                        <span class="product-action-label">{{ __('Edit') }}</span>
                                                    </a>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.product.subcategory_delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $subcategory->id }}" name="subcategory_id">
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

    @includeIf('admin.product.subcategory.create')
    @includeIf('admin.product.subcategory.edit')
@endsection

@section('script')
    <script>
        (function () {
            const selectedLanguageId = '{{ $selectedLanguage->id }}';
            const categoryOptionsByLanguage = @json($categoryOptionsByLanguage);
            const selectCategoryPlaceholder = @json(__('Select a category'));

            function renderCategoryOptions(languageId) {
                const $languageSelect = $('#ajaxForm select[name="language_id"]');
                const $categorySelect = $('#subcategoryCategorySelect');

                if ($languageSelect.length === 0 || $categorySelect.length === 0) {
                    return;
                }

                const categories = categoryOptionsByLanguage[languageId] || [];
                const currentValue = $categorySelect.val();
                let options = '<option value="">' + selectCategoryPlaceholder + '</option>';

                categories.forEach(function (category) {
                    const isSelected = String(category.id) === String(currentValue) ? ' selected' : '';
                    options += '<option value="' + category.id + '"' + isSelected + '>' + category.name + '</option>';
                });

                $categorySelect.html(options);
            }

            $(document).ready(function () {
                const $languageSelect = $('#ajaxForm select[name="language_id"]');

                if ($languageSelect.length === 0) {
                    return;
                }

                $languageSelect.val(selectedLanguageId);
                renderCategoryOptions(selectedLanguageId);

                $languageSelect.on('change', function () {
                    renderCategoryOptions($(this).val());
                });
            });
        })();
    </script>
@endsection
