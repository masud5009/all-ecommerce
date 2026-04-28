@extends('admin.layout')

@section('style')
    <style>
        .landing-template-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            color: inherit;
            text-decoration: none;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        /* .landing-template-media {
            padding: 12px 12px 0;
            background: #f8f9fa;
        } */

        .landing-template-card:hover {
            color: inherit;
            text-decoration: none;
            border-color: #0d6efd;
            box-shadow: 0 12px 28px rgba(13, 110, 253, .12);
            transform: translateY(-2px);
        }

        .landing-template-card img {
            width: 100%;
            height: auto;
            display: block;
        }

        .landing-template-content {
            padding: 16px;
            flex: 1;
        }

        .landing-template-description {
            min-height: 44px;
            color: #6c757d;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
@endsection

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Landing Page') }}</li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10">
                        <h5 class="mb-0">{{ __('Landing Page') }}</h5>
                    </div>
                    <div class="col-lg-2 text-end">
                        @include('admin.partials.languages')
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                    <div>
                        <h6 class="mb-1">{{ __('Select Landing Page Theme') }}</h6>
                        <p class="text-muted mb-0">{{ __('Choose a theme first. The section data form will open on the next page.') }}</p>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($landingCards as $card)
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.landing_page.create', $card['key']) }}" class="landing-template-card">
                                <div class="landing-template-media">
                                    <img src="{{ asset($card['image']) }}" alt="{{ $card['title'] }}">
                                </div>
                                <div class="landing-template-content">
                                    <h6 class="mb-2">{{ $card['title'] }}</h6>
                                    <span class="btn btn-sm btn-primary">{{ __('Select Theme') }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <h6 class="mb-3">{{ __('Generated Landing Pages') }}</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Template') }}</th>
                                <th>{{ __('Unique URL') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($generatedPages ?? [] as $page)
                                <tr>
                                    <td>{{ $page->title ?? '-' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $page->template ?? '')) }}</td>
                                    <td>
                                        <a href="{{ route('frontend.landing_page.show', $page->slug) }}" target="_blank">
                                            {{ route('frontend.landing_page.show', $page->slug) }}
                                        </a>
                                    </td>
                                    <td>{{ optional($page->created_at)->format('d M Y h:i A') }}</td>
                                    <td>
                                        <div class="action-buttons product-list-actions">
                                            <a href="{{ route('admin.landing_page.edit', $page->id) }}"
                                                class="btn btn-sm edit-button product-action-btn">
                                                <span class="fas fa-edit"></span>
                                                <span class="product-action-label">{{ __('Edit') }}</span>
                                            </a>
                                            <form class="deleteForm d-inline-block"
                                                action="{{ route('admin.landing_page.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{ $page->id }}" name="landing_page_id">
                                                <button class="btn btn-sm deleteBtn delete-button product-action-btn"
                                                    type="button">
                                                    <span class="fas fa-trash"></span>
                                                    <span class="product-action-label">{{ __('Delete') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('No landing page generated yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
