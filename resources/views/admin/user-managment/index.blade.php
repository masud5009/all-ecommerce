@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('User Managment') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('Registered Users') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.user.bulk_delete')" itemTextName="users" />
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Registered Users') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#createModal"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-plus"></i> {{ __('Add') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($users) == 0)
                        <h5 class="text-center">{{ __('NO USERS FOUND') . '!' }}</h5>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <th scope="col">
                                    <input type="checkbox" class="bulk-check" data-val="all">
                                </th>
                                <th scope="col">{{ __('Username') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Email Status') }}</th>
                                <th scope="col">{{ __('Account Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{ $user->id }}">
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form id="emailStatusChange"
                                                action="{{ route('admin.user.email_update_status', ['id' => $user->id]) }}"
                                                method="post">
                                                @csrf
                                                <select name="email_status"
                                                    onchange="document.getElementById('emailStatusChange').submit()"
                                                    class="form-select form-select-sm status-select
                                                @if (!is_null($user->email_verified_at)) bg-success @else bg-danger @endif text-white">
                                                    <option value="1" @selected(!is_null($user->email_verified_at))>
                                                        {{ __('Active') }}</option>
                                                    <option value="0" @selected(is_null($user->email_verified_at))>
                                                        {{ __('Dactive') }}</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form id="accountStatusChange"
                                                action="{{ route('admin.user.update_status', ['id' => $user->id]) }}"
                                                method="post">
                                                @csrf
                                                <select name="status"
                                                    onchange="document.getElementById('accountStatusChange').submit()"
                                                    class="form-select form-select-sm status-select
                                                @if ($user->status == 1) bg-success @else bg-danger @endif text-white">
                                                    <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                                                        {{ __('Active') }}</option>
                                                    <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                                                        {{ __('Dactive') }}</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.user.password_change', ['id' => $user->id]) }}">
                                                        {{ __('Change Password') }}</a>

                                                    <form class="d-block" target="_blank" action="{{ route('admin.secretAdminLogin') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <button class="dropdown-item" role="button">
                                                            {{ __('Secret Login') }}</button>
                                                    </form>

                                                    <form class="deleteForm" action="{{ route('admin.user.delete') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $user->id }}" name="user_id">
                                                        <a class="dropdown-item deleteBtn" type="button">
                                                            {{ __('Delete') }}
                                                        </a>
                                                    </form>

                                                </div>
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
    @includeIf('admin.user-managment.create')
@endsection
