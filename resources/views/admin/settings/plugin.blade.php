@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Plugins') }}</a>
            </li>
        </ol>
    </nav>

    <div class="row px-3 payment-container">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Update Plugins') }}</h5>
                </div>
                <div class="card-body">
                    <!-- pusher-->
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pusher" type="button"
                            role="tab" aria-selected="true">
                            {{ __('Pusher') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content Column -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- pusher Tab Content -->
                <div class="tab-pane fade show active" id="pusher" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Pusher') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.plugin.pusher_update') }}" method="post" id="pusherForm">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="pusher_status" value="1"
                                                    class="selectgroup-input" @checked($data->pusher_status == 1)>
                                                <span class="selectgroup-button">{{ __('Enable') }}</span>
                                            </label>

                                            <label class="selectgroup-item">
                                                <input type="radio" name="pusher_status" value="0"
                                                    class="selectgroup-input" @checked($data->pusher_status == 0)>
                                                <span class="selectgroup-button">{{ __('Disable') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter pusher app id') }}"
                                            name="pusher_app_id" value="{{ @$data->pusher_app_id }}"
                                            class="form-control {{ customValid('pusher_app_id', $errors) }}">
                                        @if ($errors->has('pusher_app_id'))
                                            <p class="mb-0 text-danger">{{ $errors->first('pusher_app_id') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter pusher app key') }}"
                                            name="pusher_app_key" value="{{ @$data->pusher_app_key }}"
                                            class="form-control {{ customValid('pusher_app_key', $errors) }}">
                                        @if ($errors->has('pusher_app_key'))
                                            <p class="mb-0 text-danger">{{ $errors->first('pusher_app_key') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter pusher app secret') }}"
                                            name="pusher_app_secret" value="{{ @$data->pusher_app_secret }}"
                                            class="form-control {{ customValid('pusher_app_secret', $errors) }}">
                                        @if ($errors->has('pusher_app_secret'))
                                            <p class="mb-0 text-danger">{{ $errors->first('pusher_app_secret') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __('Enter pusher app cluster') }}"
                                            name="pusher_app_cluster" value="{{ @$data->pusher_app_cluster }}"
                                            class="form-control {{ customValid('pusher_app_cluster', $errors) }}">
                                        @if ($errors->has('pusher_app_cluster'))
                                            <p class="mb-0 text-danger">{{ $errors->first('pusher_app_cluster') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" form="pusherForm">{{ __('Save & Changes') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
