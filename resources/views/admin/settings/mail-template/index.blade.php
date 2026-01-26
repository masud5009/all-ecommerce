@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Email Templates') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <div class="col-lg-4">
                                <div class="card-title">
                                    <h5>{{ __('Email Templates') }}</h5>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <a href="{{ route('admin.website_setting') }}"
                                    class="btn btn-primary btn-sm float-lg-end float-left">
                                    <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Template') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $template)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @php $mailType = str_replace('_', ' ', $template->type); @endphp
                                            {{ $mailType }}</td>
                                        <td>{{ $template->subject }}</td>
                                        <td>
                                            <a href="{{ route('admin.website_setting.mail_template.edit', ['type' => $template->type]) }}"
                                                class="btn btn-sm edit-button">
                                                <span class="fas fa-edit"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
