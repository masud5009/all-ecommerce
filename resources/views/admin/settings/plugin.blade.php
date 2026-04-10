@extends('admin.layout')

@section('style')
   <link rel="stylesheet" href="{{ asset('assets/admin/css/plugin.css') }}">
@endsection

@section('content')
    <div class="plugin-settings-page">
        <nav aria-label="breadcrumb" class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#">{{ __('Plugins') }}</a>
                </li>
            </ol>
        </nav>

        @php($activeTab = request('active_plugin', old('active_plugin', session('active_plugin', 'pusher'))))

        <div class="plugin-settings-hero">
            <div class="plugin-hero-copy">
                <p class="plugin-hero-kicker">{{ __('System Integrations') }}</p>
                <h1 class="plugin-hero-title">{{ __('Plugin Controls') }}</h1>
            </div>
        </div>

        <div class="row gx-4 plugin-settings-grid payment-container">
            <div class="col-lg-3">
                <div class="card plugin-sidebar-card">
                    <div class="card-header">
                        <p class="plugin-card-eyebrow">{{ __('Configuration Menu') }}</p>
                        <h5 class="plugin-sidebar-title">{{ __('Update Plugins') }}</h5>
                        <p class="plugin-nav-copy">
                            {{ __('Choose a plugin to update its status, keys, and related credentials.') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link {{ $activeTab === 'pusher' ? 'active' : '' }}" data-bs-toggle="pill"
                                data-bs-target="#pusher" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'pusher' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fas fa-broadcast-tower"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('Pusher') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Realtime notifications') }}</span>
                                </span>
                            </button>
                            <button class="nav-link {{ $activeTab === 'facebook_pixel' ? 'active' : '' }}"
                                data-bs-toggle="pill" data-bs-target="#facebook_pixel" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'facebook_pixel' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fab fa-facebook-f"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('Facebook Pixel') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Meta conversion tracking') }}</span>
                                </span>
                            </button>
                            <button class="nav-link {{ $activeTab === 'google_recaptcha' ? 'active' : '' }}"
                                data-bs-toggle="pill" data-bs-target="#google_recaptcha" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'google_recaptcha' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fas fa-shield-alt"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('Google Recaptcha') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Form protection') }}</span>
                                </span>
                            </button>
                            <button class="nav-link {{ $activeTab === 'google_analytics' ? 'active' : '' }}"
                                data-bs-toggle="pill" data-bs-target="#google_analytics" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'google_analytics' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fas fa-chart-line"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('Google Analytics') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Traffic measurement') }}</span>
                                </span>
                            </button>
                            <button class="nav-link {{ $activeTab === 'stedfast' ? 'active' : '' }}" data-bs-toggle="pill"
                                data-bs-target="#stedfast" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'stedfast' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fas fa-truck"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('Stedfast') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Delivery API') }}</span>
                                </span>
                            </button>
                            <button class="nav-link {{ $activeTab === 'gemini' ? 'active' : '' }}" data-bs-toggle="pill"
                                data-bs-target="#AI_Configuration" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'gemini' ? 'true' : 'false' }}">
                                <span class="plugin-nav-icon"><i class="fas fa-sparkles"></i></span>
                                <span>
                                    <span class="plugin-nav-name">{{ __('AI Configuration') }}</span>
                                    <span class="plugin-nav-meta">{{ __('Gemini setup') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content">
                    <div class="tab-pane fade {{ $activeTab === 'pusher' ? 'show active' : '' }}" id="pusher"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('Realtime Service') }}</p>
                                <h5 class="plugin-card-title">{{ __('Pusher') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Configure the credentials required for realtime notifications, events, and live updates.') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.pusher_update') }}" method="post" id="pusherForm"
                                    class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="pusher">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label class="plugin-field-label">{{ __('Pusher Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="pusher_status" value="1"
                                                        class="selectgroup-input" @checked(old('pusher_status', (string) ($data->pusher_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>

                                                <label class="selectgroup-item">
                                                    <input type="radio" name="pusher_status" value="0"
                                                        class="selectgroup-input" @checked(old('pusher_status', (string) ($data->pusher_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter pusher app id') }}"
                                                name="pusher_app_id"
                                                value="{{ old('pusher_app_id', $data->pusher_app_id ?? '') }}"
                                                class="form-control {{ customValid('pusher_app_id', $errors) }}">
                                            @if ($errors->has('pusher_app_id'))
                                                <p class="mb-0 text-danger">{{ $errors->first('pusher_app_id') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter pusher app key') }}"
                                                name="pusher_app_key"
                                                value="{{ old('pusher_app_key', $data->pusher_app_key ?? '') }}"
                                                class="form-control {{ customValid('pusher_app_key', $errors) }}">
                                            @if ($errors->has('pusher_app_key'))
                                                <p class="mb-0 text-danger">{{ $errors->first('pusher_app_key') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter pusher app secret') }}"
                                                name="pusher_app_secret"
                                                value="{{ old('pusher_app_secret', $data->pusher_app_secret ?? '') }}"
                                                class="form-control {{ customValid('pusher_app_secret', $errors) }}">
                                            @if ($errors->has('pusher_app_secret'))
                                                <p class="mb-0 text-danger">{{ $errors->first('pusher_app_secret') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter pusher app cluster') }}"
                                                name="pusher_app_cluster"
                                                value="{{ old('pusher_app_cluster', $data->pusher_app_cluster ?? '') }}"
                                                class="form-control {{ customValid('pusher_app_cluster', $errors) }}">
                                            @if ($errors->has('pusher_app_cluster'))
                                                <p class="mb-0 text-danger">{{ $errors->first('pusher_app_cluster') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" form="pusherForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'facebook_pixel' ? 'show active' : '' }}"
                        id="facebook_pixel" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('Tracking') }}</p>
                                <h5 class="plugin-card-title">{{ __('Facebook Pixel') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Use Pixel events to monitor visitors, build audiences, and measure conversion activity.') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.facebook_pixel_update') }}" method="post"
                                    id="facebookPixelForm" class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="facebook_pixel">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label>{{ __('Facebook Pixel Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="facebook_pixel_status" value="1"
                                                        class="selectgroup-input" @checked(old('facebook_pixel_status', (string) ($data->facebook_pixel_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="facebook_pixel_status" value="0"
                                                        class="selectgroup-input" @checked(old('facebook_pixel_status', (string) ($data->facebook_pixel_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Inactive') }}</span>
                                                </label>
                                            </div>
                                            @if ($errors->has('facebook_pixel_status'))
                                                <p class="mb-0 text-danger">{{ $errors->first('facebook_pixel_status') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 plugin-span-full">
                                        <p class="mb-3 text-warning">
                                            {{ __('Hint: Facebook Pixel ID can be found in Facebook Events Manager.') }}
                                        </p>
                                    </div>

                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label>{{ __('Facebook Pixel ID') }}</label>
                                            <input type="text" name="facebook_pixel_id"
                                                value="{{ old('facebook_pixel_id', $data->facebook_pixel_id ?? '') }}"
                                                class="form-control {{ customValid('facebook_pixel_id', $errors) }}">
                                            @if ($errors->has('facebook_pixel_id'))
                                                <p class="mb-0 text-danger">{{ $errors->first('facebook_pixel_id') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success"
                                    form="facebookPixelForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'google_recaptcha' ? 'show active' : '' }}"
                        id="google_recaptcha" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('Security') }}</p>
                                <h5 class="plugin-card-title">{{ __('Google Recaptcha') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Protect public forms using Google Recaptcha site and secret keys.') }}</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.google_recaptcha_update') }}" method="post"
                                    id="googleRecaptchaForm" class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="google_recaptcha">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label>{{ __('Google Recaptcha Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="google_recaptcha_status" value="1"
                                                        class="selectgroup-input" @checked(old('google_recaptcha_status', (string) ($data->google_recaptcha_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="google_recaptcha_status" value="0"
                                                        class="selectgroup-input" @checked(old('google_recaptcha_status', (string) ($data->google_recaptcha_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Inactive') }}</span>
                                                </label>
                                            </div>
                                            @if ($errors->has('google_recaptcha_status'))
                                                <p class="mb-0 text-danger">
                                                    {{ $errors->first('google_recaptcha_status') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Google Recaptcha Site key') }}</label>
                                            <input type="text" name="google_recaptcha_site_key"
                                                value="{{ old('google_recaptcha_site_key', $data->google_recaptcha_site_key ?? '') }}"
                                                class="form-control {{ customValid('google_recaptcha_site_key', $errors) }}">
                                            @if ($errors->has('google_recaptcha_site_key'))
                                                <p class="mb-0 text-danger">
                                                    {{ $errors->first('google_recaptcha_site_key') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Google Recaptcha Secret key') }}</label>
                                            <input type="text" name="google_recaptcha_secret_key"
                                                value="{{ old('google_recaptcha_secret_key', $data->google_recaptcha_secret_key ?? '') }}"
                                                class="form-control {{ customValid('google_recaptcha_secret_key', $errors) }}">
                                            @if ($errors->has('google_recaptcha_secret_key'))
                                                <p class="mb-0 text-danger">
                                                    {{ $errors->first('google_recaptcha_secret_key') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success"
                                    form="googleRecaptchaForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'google_analytics' ? 'show active' : '' }}"
                        id="google_analytics" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('Analytics') }}</p>
                                <h5 class="plugin-card-title">{{ __('Google Analytics') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Connect your GA4 property and keep measurement tracking controlled from the admin panel.') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.google_analytics_update') }}" method="post"
                                    id="googleAnalyticsForm" class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="google_analytics">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label>{{ __('Google Analytics Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="google_analytics_status" value="1"
                                                        class="selectgroup-input" @checked(old('google_analytics_status', (string) ($data->google_analytics_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="google_analytics_status" value="0"
                                                        class="selectgroup-input" @checked(old('google_analytics_status', (string) ($data->google_analytics_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Inactive') }}</span>
                                                </label>
                                            </div>
                                            @if ($errors->has('google_analytics_status'))
                                                <p class="mb-0 text-danger">
                                                    {{ $errors->first('google_analytics_status') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label>{{ __('Measurement ID') }}</label>
                                            <input type="text" name="google_analytics_measurement_id"
                                                value="{{ old('google_analytics_measurement_id', $data->google_analytics_measurement_id ?? '') }}"
                                                class="form-control {{ customValid('google_analytics_measurement_id', $errors) }}">
                                            @if ($errors->has('google_analytics_measurement_id'))
                                                <p class="mb-0 text-danger">
                                                    {{ $errors->first('google_analytics_measurement_id') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success"
                                    form="googleAnalyticsForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'stedfast' ? 'show active' : '' }}" id="stedfast"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('Delivery') }}</p>
                                <h5 class="plugin-card-title">{{ __('Stedfast') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Manage courier credentials used for delivery order creation and external sync.') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.stedfast_update') }}" method="post"
                                    id="stedfastForm" class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="stedfast">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label class="plugin-field-label">{{ __('Stedfast Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="stedfast_status" value="1"
                                                        class="selectgroup-input" @checked(old('stedfast_status', (string) ($data->stedfast_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>

                                                <label class="selectgroup-item">
                                                    <input type="radio" name="stedfast_status" value="0"
                                                        class="selectgroup-input" @checked(old('stedfast_status', (string) ($data->stedfast_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter stedfast api key') }}"
                                                name="stedfast_api_key"
                                                value="{{ old('stedfast_api_key', $data->stedfast_api_key ?? '') }}"
                                                class="form-control {{ customValid('stedfast_api_key', $errors) }}">
                                            @if ($errors->has('stedfast_api_key'))
                                                <p class="mb-0 text-danger">{{ $errors->first('stedfast_api_key') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="{{ __('Enter stedfast secret key') }}"
                                                name="stedfast_secret_key"
                                                value="{{ old('stedfast_secret_key', $data->stedfast_secret_key ?? '') }}"
                                                class="form-control {{ customValid('stedfast_secret_key', $errors) }}">
                                            @if ($errors->has('stedfast_secret_key'))
                                                <p class="mb-0 text-danger">{{ $errors->first('stedfast_secret_key') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" form="stedfastForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'gemini' ? 'show active' : '' }}" id="AI_Configuration"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <p class="plugin-card-eyebrow">{{ __('AI Tools') }}</p>
                                <h5 class="plugin-card-title">{{ __('Gemini API Settings') }}</h5>
                                <p class="plugin-card-copy">
                                    {{ __('Choose your preferred models and connect Gemini with the API key used by the system.') }}
                                </p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.plugin.gemini_update') }}" method="post" id="geminiForm"
                                    class="plugin-grid-form">
                                    @csrf
                                    <input type="hidden" name="active_plugin" value="gemini">
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label class="plugin-field-label">{{ __('Gemini Status') }}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="gemini_status" value="1"
                                                        class="selectgroup-input" @checked(old('gemini_status', (string) ($data->gemini_status ?? 0)) == '1')>
                                                    <span class="selectgroup-button">{{ __('Enable') }}</span>
                                                </label>

                                                <label class="selectgroup-item">
                                                    <input type="radio" name="gemini_status" value="0"
                                                        class="selectgroup-input" @checked(old('gemini_status', (string) ($data->gemini_status ?? 0)) == '0')>
                                                    <span class="selectgroup-button">{{ __('Disable') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Gemini Text Model') }}</label>
                                            <input type="text" placeholder="{{ __('Enter gemini text model name') }}"
                                                name="gemini_text_model"
                                                value="{{ old('gemini_text_model', $data->gemini_text_model ?? '') }}"
                                                class="form-control {{ customValid('gemini_text_model', $errors) }}">
                                            @if ($errors->has('gemini_text_model'))
                                                <p class="mb-0 text-danger">{{ $errors->first('gemini_text_model') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Gemini Image Model') }}</label>
                                            <input type="text" placeholder="{{ __('Enter gemini image model') }}"
                                                name="gemini_image_model"
                                                value="{{ old('gemini_image_model', $data->gemini_image_model ?? '') }}"
                                                class="form-control {{ customValid('gemini_image_model', $errors) }}">
                                            @if ($errors->has('gemini_image_model'))
                                                <p class="mb-0 text-danger">{{ $errors->first('gemini_image_model') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 plugin-span-full">
                                        <div class="form-group">
                                            <label for="">{{ __('Gemini API Key') }} <span
                                                    class="text-danger">**</span></label>
                                            <input type="text" placeholder="{{ __('Enter gemini api key') }}"
                                                name="gemini_api_key"
                                                value="{{ old('gemini_api_key', $data->gemini_api_key ?? '') }}"
                                                class="form-control {{ customValid('gemini_api_key', $errors) }}">
                                            @if ($errors->has('gemini_api_key'))
                                                <p class="mb-0 text-danger">{{ $errors->first('gemini_api_key') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success btn-lg" form="geminiForm">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
