@extends('user.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Common') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row p-3">

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon"><i class="fas fa-cog"></i></div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.website_setting.general_setting') }}"
                                        class="text-primary">{{ __('General Settings') }}</a>
                                    <span>{{ __('View and update your general settings and activate license') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon"><i class="fas fa-envelope"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.website_setting.config_email') }}"
                                        class="text-primary">{{ __('Email Settings') }}</a>
                                    <span>{{ __('View and update your email settings and email templates') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.website_setting.mail_template') }}" class="text-primary">
                                        {{ __('Email Templates') }}</a>
                                    <span>{{ __('Email templates using HTML & system variables') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon">
                                    <i class="fas fa-plug"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.plugin') }}" class="text-primary">{{ __('Plugins') }}</a>
                                    <span>{{ __('View and update plugin setting') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.gateway') }}"
                                        class="text-primary">{{ __('Payment Gateway') }}</a>
                                    <span>{{ __('Manage and configure payment gateway settings') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon">
                                    <i class="fas fa-plug"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.website_setting.page_heading') }}" class="text-primary">
                                        {{ __('Page Heading') }}</a>
                                    </a>
                                    <span> {{ __('View and update plugin setting') }} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="d-flex">
                                <div class="settings-icon">
                                    <i class="fas fa-wrench"></i>
                                </div>
                                <div class="settings-text">
                                    <a href="{{ route('admin.maintenance') }}" class="text-primary">
                                        {{ __('Maintenance Mode') }}
                                    </a>
                                    <span>{{ __('Enable or disable maintenance mode and configure settings for site updates') }}</span>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
