@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('admin.website_setting.mail_template')}}">{{ __('Email Templates') }}</a>
            </li>
            <li class="breadcrumb-item">
                @php $mailType = str_replace('_', ' ', $data->type); @endphp
                <a href="{{request()->url()}}">{{ $mailType }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Edit Email Template') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12 mb-5">
        <form id="mailForm" action="{{ route('admin.website_setting.mail_template.update', ['type' => $data->type]) }}"
            method="post">
            @csrf
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="col-lg-4">
                            <div class="card-title">
                                <h5>{{ __('Edit Email Template') }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <a href="{{ route('admin.website_setting.mail_template') }}"
                                class="btn btn-primary btn-sm float-lg-end float-left">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-7 mx-auto">

                            <div class="form-group">
                                <label>{{ __('Type') }}</label>
                                <input type="text" class="form-control" readonly value="{{ $mailType }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Subject') }}*</label>
                                <input type="text" class="form-control {{ customValid('subject', $errors) }}" value="{{ old('subject',$data->subject) }}" name="subject">
                                @if ($errors->has('subject'))
                                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('subject') }}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ __('Body') }}*</label>
                                <textarea name="body" rows="6" class="form-control editor summernote">
                                    {{ replaceBaseUrl($data->body, 'summernote') }}
                                </textarea>
                                @if ($errors->has('body'))
                                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('body') }}</p>
                                @endif
                            </div>

                        </div>
                        @includeIf('admin.settings.mail-template.variables')
                    </div>
                </div>
                <div class="card-footer py-3 d-flex justify-content-center">
                    <button class="btn btn-success submit-btn" form="mailForm" type="submit">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
