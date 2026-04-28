@extends('admin.layout')

@section('style')
    <style>
        .landing-section-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            background: #fff;
        }

        .landing-section-head {
            padding: 16px 18px;
            border-bottom: 1px solid #edf0f2;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }

        .landing-section-body {
            padding: 18px;
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.landing_page') }}">{{ __('Landing Page') }}</a></li>
            <li class="breadcrumb-item active">{{ $landingTemplate['title'] }}</li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="mb-1">{{ __('Create Landing Page') }} - {{ $landingTemplate['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $landingTemplate['description'] }}</p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <a href="{{ route('admin.landing_page') }}" class="btn btn-sm btn-outline-secondary">
                            {{ __('Change Theme') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('generated_url'))
                    <div class="alert alert-info d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <strong>{{ __('Generated URL:') }}</strong>
                            <a href="{{ session('generated_url') }}" target="_blank" class="ms-1">{{ session('generated_url') }}</a>
                        </div>
                        <a href="{{ session('generated_url') }}" target="_blank" class="btn btn-sm btn-primary">{{ __('Open Landing Page') }}</a>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.landing_page.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="template" value="{{ $landingTemplate['key'] }}">

                    <div class="row g-4">
                        @foreach ($landingTemplate['sections'] as $section)
                            <div class="col-12">
                                <div class="landing-section-card">
                                    <div class="landing-section-head">
                                        <h6 class="mb-1">{{ $section['title'] }}</h6>
                                        @if (!empty($section['description']))
                                            <p class="text-muted mb-0">{{ $section['description'] }}</p>
                                        @endif
                                    </div>
                                    <div class="landing-section-body">
                                        <div class="row">
                                            @foreach ($section['fields'] as $field)
                                                @php
                                                    $fieldName = $landingTemplate['key'] . '[' . $field['name'] . ']';
                                                    $oldKey = $landingTemplate['key'] . '.' . $field['name'];
                                                    $defaultValue = $field['placeholder'] ?? '';
                                                    $oldValue = old($oldKey, $defaultValue);
                                                @endphp
                                                <div class="col-lg-{{ $field['col'] ?? 6 }}">
                                                    <div class="form-group">
                                                        <label>{{ $field['label'] }}</label>

                                                        @if (($field['type'] ?? 'text') === 'textarea')
                                                            <textarea name="{{ $fieldName }}" rows="{{ $field['rows'] ?? 3 }}"
                                                                class="form-control" placeholder="{{ $defaultValue }}">{{ $oldValue }}</textarea>
                                                        @elseif (($field['type'] ?? 'text') === 'file')
                                                            <input type="file" name="{{ $fieldName }}"
                                                                class="form-control"
                                                                accept=".jpg,.jpeg,.png,.webp,.svg,.avif">
                                                        @else
                                                            <input type="{{ $field['type'] ?? 'text' }}"
                                                                name="{{ $fieldName }}"
                                                                class="form-control"
                                                                value="{{ $oldValue }}"
                                                                placeholder="{{ $defaultValue }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            {{ __('Generate Landing Page') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
