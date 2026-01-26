@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Email Settings') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Config Email Setting') }}</h5>
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
            <div class="card-body">
                <div class="col-lg-6 mx-auto">
                    <form id="ajaxForm" action="{{ route('admin.website_setting.config_email_store') }}" method="post">
                        @csrf
                        <div class="row">

                            <x-text-input col="12" type="radio" name="smtp_status" label="Mail Status"
                                required="**" value="{{ @$data->smtp_status }}" action="store" />

                            <x-text-input col="12" name="smtp_host" label="SMTP Host" placeholder="ex: smtp.gmail.com"
                                required="**" value="{{ @$data->smtp_host }}" action="store" />

                            <x-text-input col="12" name="smtp_port" label="SMTP Port" placeholder="ex: 587"
                                required="**" value="{{ @$data->smtp_port }}" action="store" />

                            <x-text-input col="12" name="smtp_username" label="SMTP Username"
                                placeholder="Username from Mail Server" required="**" value="{{ @$data->smtp_username }}"
                                action="store" />

                            <x-text-input col="12" name="smtp_password" label="SMTP Password" type="password"
                                placeholder="Password from Mail Server" required="**" value="{{ @$data->smtp_password }}"
                                action="store" />

                            <x-text-input col="12" name="encryption" label="Encryption" placeholder="ex: ssl or tls"
                                required="**" value="{{ @$data->encryption }}" action="store" />

                            <x-text-input col="12" name="sender_mail" label="Sender Mail" type="email"
                                placeholder="admin@example.com" value="{{ @$data->sender_mail }}" action="store" />

                            <x-text-input col="12" name="sender_name" label="Sender Name"
                                placeholder="ex: your website name" value="{{ @$data->sender_name }}" action="store" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-3 d-flex justify-content-center">
                <button class="btn btn-success submit-btn" id="submitBtn" type="button">
                    {{ __('Save & Changes') }}
                </button>
            </div>
        </div>
    </div>
@endsection
