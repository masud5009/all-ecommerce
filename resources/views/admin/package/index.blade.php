@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Package Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Packages') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Packages') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-6">
                        <div class="info-header-content">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                                data-href="{{ route('admin.package.bulk_delete') }}">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </button>
                            <a href="#" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($all_packages) == 0)
                        <h5 class="text-center">{{ __('NO PACKAGE FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Term') }}</th>
                                    <th scope="col">{{ __('Price') }}({{ $websiteInfo->currency_text }})</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_packages as $package)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $package->id }}">
                                            </td>
                                            <td>{{ $package->title }}</td>
                                            <td>
                                                @if ($package->is_trial == 1)
                                                    {{ $package->trial_days }} {{ __('Days') }}
                                                @else
                                                    {{ $package->term }}
                                                @endif
                                            </td>
                                            <td>{{ $package->price }}</td>
                                            <td>
                                                @if ($package->status == 1)
                                                    <span class="badge bg-success changeStatusBtn"
                                                        data-id="{{ $package->id }}" data-value="{{ $package->status }}"
                                                        data-url="{{ route('admin.package.status_change') }}">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger changeStatusBtn"
                                                        data-id="{{ $package->id }}" data-value="{{ $package->status }}"
                                                        data-url="{{ route('admin.package.status_change') }}">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons product-list-actions">
                                                    <a href="{{ route('admin.package.edit', ['id' => $package->id]) }}"
                                                        class="btn btn-sm edit-button product-action-btn">
                                                        <span class="fas fa-edit"></span>
                                                        <span class="product-action-label">{{ __('Edit') }}</span>
                                                    </a>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.package.delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $package->id }}" name="package_id">
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
    @includeIf('admin.package.create')
@endsection
@section('script')
    <script>
        const enableRadio = document.getElementById('trial_enable');
        const disableRadio = document.getElementById('trial_disable');
        const daysContainer = document.getElementById('trial_days_container');
        const termContainer = document.getElementById('package_term_container');

        enableRadio.addEventListener('change', function() {
            if (this.checked) {
                daysContainer.style.display = 'block';
                termContainer.style.display = 'none';
            }
        });

        disableRadio.addEventListener('change', function() {
            if (this.checked) {
                daysContainer.style.display = 'none';
                termContainer.style.display = 'block';
            }
        });
    </script>
@endsection
