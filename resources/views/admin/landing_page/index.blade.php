@extends('admin.layout')

@section('style')
    <style>
        .landing-template-card {
            width: 100%;
            padding: 0;
            overflow: hidden;
            text-align: left;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .landing-template-card.active {
            border-color: #0d6efd;
            box-shadow: 0 0 0 1px rgba(13, 110, 253, .15);
        }

        .landing-template-card:hover {
            border-color: #0d6efd;
        }

        .landing-template-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .landing-template-content {
            padding: 14px;
        }

        .landing-template-title {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .landing-template-title h6,
        .landing-fields-title h6 {
            margin-bottom: 0;
        }

        .landing-template-description {
            min-height: 40px;
            margin-bottom: 0;
            color: #6c757d;
            font-size: 13px;
            line-height: 1.5;
        }

        .landing-check-icon {
            display: none;
            color: #0d6efd;
            font-size: 15px;
        }

        .landing-template-card.active .landing-check-icon {
            display: inline-block;
        }

        .landing-fields-panel {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
        }

        .landing-fields-title {
            padding-bottom: 14px;
            margin-bottom: 18px;
            border-bottom: 1px solid #edf0f2;
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

                <form id="landingPageForm" method="POST" action="{{ route('admin.landing_page.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="template" id="landingTemplateInput" value="{{ $landingCards[0]['key'] ?? '' }}">

                    <div class="container-fluid mt-4 px-0">
                        <div class="row g-3">
                            @foreach ($landingCards as $card)
                                <div class="col-lg-4 col-md-6">
                                    <button type="button"
                                        class="landing-template-card {{ $loop->first ? 'active' : '' }}"
                                        data-template-key="{{ $card['key'] }}"
                                        aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ asset($card['image']) }}" alt="{{ $card['title'] }}">
                                        <div class="landing-template-content">
                                            <div class="landing-template-title">
                                                <h6>{{ $card['title'] }}</h6>
                                                <i class="fas fa-check-circle landing-check-icon"></i>
                                            </div>
                                            <div class="landing-template-description">
                                                {{ $card['description'] }}
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            @foreach ($landingCards as $card)
                                @php($isActiveTemplate = $loop->first)

                                <div class="landing-template-fields {{ $isActiveTemplate ? '' : 'd-none' }}"
                                    data-template-fields="{{ $card['key'] }}">
                                    <div class="landing-fields-panel">
                                        <div class="landing-fields-title">
                                            <h6>{{ $card['title'] }} {{ __('Fields') }}</h6>
                                        </div>

                                        <div class="row">
                                            @foreach ($card['fields'] as $field)
                                                <div class="col-lg-{{ $field['col'] ?? 6 }}">
                                                    <div class="form-group">
                                                        <label>{{ $field['label'] }}</label>

                                                        @if ($field['type'] === 'textarea')
                                                            <textarea name="{{ $card['key'] }}[{{ $field['name'] }}]" rows="{{ $field['rows'] ?? 3 }}"
                                                                class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}" @if (!$isActiveTemplate) disabled @endif></textarea>
                                                        @elseif ($field['type'] === 'file')
                                                            <input type="file" name="{{ $card['key'] }}[{{ $field['name'] }}]"
                                                                class="form-control"
                                                                accept=".jpg,.jpeg,.png,.webp,.svg,.avif"
                                                                @if (!$isActiveTemplate) disabled @endif>
                                                        @else
                                                            <input type="{{ $field['type'] }}"
                                                                name="{{ $card['key'] }}[{{ $field['name'] }}]"
                                                                class="form-control"
                                                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                @if (!$isActiveTemplate) disabled @endif>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
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
                    </div>
                </form>

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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">{{ __('No landing page generated yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const templateInput = document.getElementById('landingTemplateInput');
            const cards = document.querySelectorAll('.landing-template-card');
            const fieldPanels = document.querySelectorAll('.landing-template-fields');

            function togglePanelInputs(panel, enabled) {
                panel.querySelectorAll('input, textarea, select').forEach(function (field) {
                    field.disabled = !enabled;
                });
            }

            cards.forEach(function (card) {
                card.addEventListener('click', function () {
                    const selectedKey = card.dataset.templateKey;
                    templateInput.value = selectedKey;

                    cards.forEach(function (item) {
                        const isActive = item.dataset.templateKey === selectedKey;
                        item.classList.toggle('active', isActive);
                        item.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });

                    fieldPanels.forEach(function (panel) {
                        const isActive = panel.dataset.templateFields === selectedKey;
                        panel.classList.toggle('d-none', !isActive);
                        togglePanelInputs(panel, isActive);
                    });
                });
            });
        });
    </script>
@endsection
