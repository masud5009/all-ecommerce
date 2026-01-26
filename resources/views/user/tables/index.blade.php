@extends('user.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Tables') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('user.tables.bulk_delete')" itemTextName="tables" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Tables') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="info-header-content">
                            <a href="#" class="btn btn-primary btn-sm float-lg-end float-left" data-bs-toggle="modal"
                                data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> {{ __('Add Table') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($tables) == 0)
                        <h5 class="text-center">{{ __('NO TABLE FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Table Number') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Serial Number') }}</th>
                                    <th scope="col">{{ __('QR Code') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($tables as $key => $table)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $table->id }}">
                                            </td>
                                            <td>{{ $table->table_number }}</td>
                                            <td>
                                                <form id="statusForm-{{ $table->id }}"
                                                    action="{{ route('user.tables.update_status') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="id" value="{{ $table->id }}">

                                                    <select name="status" class="form-select form-select-sm status-select"
                                                        onchange="document.getElementById('statusForm-{{ $table->id }}').submit()">
                                                        @foreach ($statuses as $key => $label)
                                                            <option value="{{ $key }}"
                                                                @selected($table->status == $key)>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>

                                            </td>
                                            <td>{{ $table->serial_number }}</td>
                                            <td>
                                                <a href="{{ route('user.tables.qr_code', $table->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-qrcode me-1"></i> {{ __('Generate') }}
                                                </a>
                                            </td>
                                            <td class="action-buttons">
                                                <a href="" class="btn btn-sm editBtn edit-button"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-id="{{ $table->id }}"
                                                    data-table_number="{{ $table->table_number }}"
                                                    data-serial_number="{{ $table->serial_number }}"
                                                    data-capacity="{{ $table->capacity }}"
                                                    data-status="{{ $table->status }}">
                                                    <span class="fas fa-edit"></span>
                                                </a>
                                                <form class="deleteForm d-inline-block"
                                                    action="{{ route('user.tables.delete') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" value="{{ $table->id }}" name="table_id">
                                                    <button class="btn btn-sm deleteBtn delete-button" type="button">
                                                        <span class="fas fa-trash"></span>
                                                    </button>
                                                </form>
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
    @include('user.tables.create')
    @include('user.tables.edit')
@endsection
