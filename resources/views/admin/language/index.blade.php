@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><span class="fas fa-home"></span></a></li>
            <li class="breadcrumb-item active">
                <a href="#">{{ __('Language Management') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header language-management-header">
                <div class="card-title mb-0">
                    <h5 class="mb-0">{{ __('Language Management') }}</h5>
                </div>
                <div class="language-management-actions">
                    <a href="#" class="btn btn-secondary btn-sm header-btn language-action-btn" data-bs-toggle="modal"
                        data-bs-target="#keywordModal">
                        <i class="fas fa-plus"></i> {{ __('Add Frontend Keyword') }}
                    </a>
                    <a href="#" class="btn btn-secondary btn-sm header-btn language-action-btn" data-bs-toggle="modal"
                        data-bs-target="#AdminKeywordModal">
                        <i class="fas fa-plus"></i> {{ __('Add Admin Keyword') }}
                    </a>
                    <a href="#" class="btn btn-primary btn-sm header-btn language-action-btn" data-bs-toggle="modal"
                        data-bs-target="#langModal">
                        <i class="fas fa-plus"></i> {{ __('Add Language') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($data) == 0)
                        <h5 class="text-center">{{ __('NO LANGUAGE FOUND') . '!' }}</h5>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Code') }}</th>
                                    <th scope="col">{{ __('Default in Website') }}</th>
                                    <th scope="col">{{ __('Default in Dashboard') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $lang)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $lang->name }}</td>
                                            <td>{{ $lang->code }}</td>
                                            <td>
                                                @if ($lang->is_default == 1)
                                                    <span class="badge bg-success">{{ __('Default') }}</span>
                                                @else
                                                    <form
                                                        action="{{ route('admin.language.make_default', ['id' => $lang->id]) }}"
                                                        method="post" id="searchForm_{{ $lang->id }}">
                                                        @csrf
                                                        <input type="hidden" name="is_default"
                                                            value="{{ $lang->is_default }}">
                                                        <div onclick="document.getElementById('searchForm_'+{{ $lang->id }}).submit()"
                                                            class="is_default">
                                                            <button
                                                                class="btn btn-primary btn-sm">{{ __('Make Default') }}</button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($lang->dashboard_default == 1)
                                                    <span class="badge bg-success">{{ __('Default') }}</span>
                                                @else
                                                    <form
                                                        action="{{ route('admin.language.dashboardDefault', ['id' => $lang->id]) }}"
                                                        method="post" id="dashboard_default_{{ $lang->id }}">
                                                        @csrf
                                                        <input type="hidden" name="dashboard_default"
                                                            value="{{ $lang->dashboard_default }}">
                                                        <div onclick="document.getElementById('dashboard_default_'+{{ $lang->id }}).submit()"
                                                            class="dashboard_default">
                                                            <button
                                                                class="btn btn-primary btn-sm">{{ __('Make Default') }}</button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="javascript::void()" data-bs-toggle="modal"
                                                            data-bs-target="#langEditModal" data-name="{{ $lang->name }}"
                                                            data-code="{{ $lang->code }}"
                                                            data-direction="{{ $lang->direction }}"
                                                            data-id="{{ $lang->id }}"
                                                            class="btn btn-sm editBtn dropdown-item">
                                                            Edit
                                                        </a>
                                                        <a href="{{ route('admin.language.edit_admin_keyword', $lang->id) }}"
                                                            class="dropdown-item">
                                                            {{ __('Edit Admin Keyword') }}
                                                        </a>
                                                        <a href="{{ route('admin.language.edit_keyword', $lang->id) }}"
                                                            class="dropdown-item">
                                                            {{ __('Edit Frontend Keyword') }}
                                                        </a>
                                                        <form class="deleteForm" action="{{ route('admin.language.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" value="{{ $lang->id }}" name="lang_id">
                                                            <button class="btn btn-sm deleteBtn" type="button">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
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
    @includeIf('admin.language.create')
    @includeIf('admin.language.edit')
    @includeIf('admin.language.include.key_word_modal')
    @includeIf('admin.language.include.admin_key_word_modal')
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/language_keyword.js') }}"></script>
@endsection
