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

        .landing-repeater-item {
            padding: 10px 8px;
            margin-bottom: 12px;
            border: 1px solid #edf0f2;
            border-radius: 8px;
            background: #fbfcfd;
        }
    </style>
@endsection

@section('content')
    @php
        $landingPage = $landingPage ?? null;
        $isEdit = !empty($landingPage);
        $savedData = $isEdit && is_array($landingPage->content) ? $landingPage->content : [];
        $productOptions = $productOptions ?? collect();
        $formAction = $isEdit
            ? route('admin.landing_page.update', $landingPage->id)
            : route('admin.landing_page.store');
        $generatedUrl =
            session('generated_url') ?: ($isEdit ? route('frontend.landing_page.show', $landingPage->slug) : null);
    @endphp

    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.landing_page') }}">{{ __('Landing Page') }}</a></li>
            <li class="breadcrumb-item active">{{ $isEdit ? __('Edit') : $landingTemplate['title'] }}</li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="mb-1">
                            {{ $isEdit ? __('Edit Landing Page') : __('Create Landing Page') }} -
                            {{ $landingTemplate['title'] }}
                        </h5>
                    </div>
                    <div class="col-lg-4 text-end">
                        <a href="{{ route('admin.landing_page') }}" class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left" aria-hidden="true"></i> {{ $isEdit ? __('Back') : __('Change Theme') }}
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

                @if ($generatedUrl)
                    <div
                        class="alert alert-info d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <strong>{{ __('Landing URL:') }}</strong>
                            <a href="{{ $generatedUrl }}" target="_blank" class="ms-1">{{ $generatedUrl }}</a>
                        </div>
                        <a href="{{ $generatedUrl }}" target="_blank"
                            class="btn btn-sm btn-success">{{ __('Open Landing Page') }}</a>
                    </div>
                @endif

                <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
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
                                                    $fieldType = $field['type'] ?? 'text';
                                                    $fieldName = $landingTemplate['key'] . '[' . $field['name'] . ']';
                                                    $oldKey = $landingTemplate['key'] . '.' . $field['name'];
                                                    $defaultValue =
                                                        ($landingTemplate['key'] ?? '') === 'theme_one'
                                                            ? ''
                                                            : $field['placeholder'] ?? '';
                                                    $savedValue = $savedData[$field['name']] ?? $defaultValue;

                                                    if ($fieldType === 'repeater') {
                                                        $savedValue = $savedData[$field['name']] ?? [];

                                                        if (
                                                            empty($savedValue) &&
                                                            $isEdit &&
                                                            $landingTemplate['key'] === 'theme_one'
                                                        ) {
                                                            if ($field['name'] === 'feature_items') {
                                                                $legacyFeatureItems = [];

                                                                for (
                                                                    $legacyIndex = 1;
                                                                    $legacyIndex <= 4;
                                                                    $legacyIndex++
                                                                ) {
                                                                    $legacyFeatureItem = [
                                                                        'icon' =>
                                                                            $savedData[
                                                                                'feature_' . $legacyIndex . '_icon'
                                                                            ] ?? '',
                                                                        'title' =>
                                                                            $savedData[
                                                                                'feature_' . $legacyIndex . '_title'
                                                                            ] ?? '',
                                                                        'description' =>
                                                                            $savedData[
                                                                                'feature_' .
                                                                                    $legacyIndex .
                                                                                    '_description'
                                                                            ] ?? '',
                                                                    ];

                                                                    if (
                                                                        filled($legacyFeatureItem['icon']) ||
                                                                        filled($legacyFeatureItem['title']) ||
                                                                        filled($legacyFeatureItem['description'])
                                                                    ) {
                                                                        $legacyFeatureItems[] = $legacyFeatureItem;
                                                                    }
                                                                }

                                                                $savedValue = $legacyFeatureItems;
                                                            } elseif ($field['name'] === 'lifestyle_bullets') {
                                                                $legacyLifestyleBullets = [];

                                                                for (
                                                                    $legacyIndex = 1;
                                                                    $legacyIndex <= 4;
                                                                    $legacyIndex++
                                                                ) {
                                                                    $legacyLifestyleBullet = [
                                                                        'text' =>
                                                                            $savedData[
                                                                                'lifestyle_bullet_' . $legacyIndex
                                                                            ] ?? '',
                                                                    ];

                                                                    if (filled($legacyLifestyleBullet['text'])) {
                                                                        $legacyLifestyleBullets[] = $legacyLifestyleBullet;
                                                                    }
                                                                }

                                                                $savedValue = $legacyLifestyleBullets;
                                                            } elseif ($field['name'] === 'review_items') {
                                                                $legacyReviewItems = [];

                                                                for (
                                                                    $legacyIndex = 1;
                                                                    $legacyIndex <= 3;
                                                                    $legacyIndex++
                                                                ) {
                                                                    $legacyReviewItem = [
                                                                        'rating' =>
                                                                            $savedData[
                                                                                'review_' . $legacyIndex . '_rating'
                                                                            ] ?? '',
                                                                        'text' =>
                                                                            $savedData[
                                                                                'review_' . $legacyIndex . '_text'
                                                                            ] ?? '',
                                                                        'author' =>
                                                                            $savedData[
                                                                                'review_' . $legacyIndex . '_author'
                                                                            ] ?? '',
                                                                    ];

                                                                    if (
                                                                        filled($legacyReviewItem['rating']) ||
                                                                        filled($legacyReviewItem['text']) ||
                                                                        filled($legacyReviewItem['author'])
                                                                    ) {
                                                                        $legacyReviewItems[] = $legacyReviewItem;
                                                                    }
                                                                }

                                                                $savedValue = $legacyReviewItems;
                                                            } elseif ($field['name'] === 'faq_items') {
                                                                $legacyFaqItems = [];

                                                                for (
                                                                    $legacyIndex = 1;
                                                                    $legacyIndex <= 3;
                                                                    $legacyIndex++
                                                                ) {
                                                                    $legacyFaqItem = [
                                                                        'question' =>
                                                                            $savedData[
                                                                                'faq_' . $legacyIndex . '_question'
                                                                            ] ?? '',
                                                                        'answer' =>
                                                                            $savedData[
                                                                                'faq_' . $legacyIndex . '_answer'
                                                                            ] ?? '',
                                                                    ];

                                                                    if (
                                                                        filled($legacyFaqItem['question']) ||
                                                                        filled($legacyFaqItem['answer'])
                                                                    ) {
                                                                        $legacyFaqItems[] = $legacyFaqItem;
                                                                    }
                                                                }

                                                                $savedValue = $legacyFaqItems;
                                                            }
                                                        }
                                                    }

                                                    $oldValue = old($oldKey, $savedValue);
                                                    if ($fieldType === 'repeater') {
                                                        $oldValue = is_array($oldValue) ? $oldValue : [];
                                                        $oldValue = count($oldValue) > 0 ? $oldValue : [[]];
                                                    }
                                                    $currentImage = $savedData[$field['name']] ?? null;
                                                @endphp
                                                <div class="col-lg-{{ $field['col'] ?? 6 }}">
                                                    @if ($fieldType === 'repeater')
                                                        <div class="form-group landing-repeater"
                                                            data-repeater="{{ $field['name'] }}">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-2">
                                                                <label class="mb-0">{{ $field['label'] }}</label>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-primary landing-repeater-add">
                                                                    <i class="fas fa-plus"></i> {{ __('Add Feature') }}
                                                                </button>
                                                            </div>

                                                            <div class="landing-repeater-items">
                                                                @foreach ($oldValue as $itemIndex => $item)
                                                                    @php
                                                                        $item = is_array($item) ? $item : [];
                                                                    @endphp
                                                                    <div class="landing-repeater-item" data-repeater-item>
                                                                        <div class="row align-items-end">
                                                                            @foreach ($field['fields'] as $subField)
                                                                                @php
                                                                                    $subFieldName =
                                                                                        $landingTemplate['key'] .
                                                                                        '[' .
                                                                                        $field['name'] .
                                                                                        '][' .
                                                                                        $itemIndex .
                                                                                        '][' .
                                                                                        $subField['name'] .
                                                                                        ']';
                                                                                    $subValue =
                                                                                        $item[$subField['name']] ?? '';
                                                                                @endphp
                                                                                <div
                                                                                    class="col-lg-{{ $subField['col'] ?? 6 }}">
                                                                                    <div class="form-group">
                                                                                        <label>{{ $subField['label'] }}</label>
                                                                                        @if (($subField['type'] ?? 'text') === 'textarea')
                                                                                            <textarea name="{{ $subFieldName }}" rows="{{ $subField['rows'] ?? 3 }}" class="form-control">{{ $subValue }}</textarea>
                                                                                        @else
                                                                                            <input
                                                                                                type="{{ $subField['type'] ?? 'text' }}"
                                                                                                name="{{ $subFieldName }}"
                                                                                                class="form-control"
                                                                                                value="{{ $subValue }}">
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                            <div class="col-lg-12 text-end">
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-danger landing-repeater-remove">
                                                                                    <i class="fas fa-trash"></i>
                                                                                    {{ __('Remove') }}
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <template data-repeater-template>
                                                                <div class="landing-repeater-item" data-repeater-item>
                                                                    <div class="row align-items-end">
                                                                        @foreach ($field['fields'] as $subField)
                                                                            @php
                                                                                $subFieldName =
                                                                                    $landingTemplate['key'] .
                                                                                    '[' .
                                                                                    $field['name'] .
                                                                                    '][__INDEX__][' .
                                                                                    $subField['name'] .
                                                                                    ']';
                                                                            @endphp
                                                                            <div
                                                                                class="col-lg-{{ $subField['col'] ?? 6 }}">
                                                                                <div class="form-group">
                                                                                    <label>{{ $subField['label'] }}</label>
                                                                                    @if (($subField['type'] ?? 'text') === 'textarea')
                                                                                        <textarea name="{{ $subFieldName }}" rows="{{ $subField['rows'] ?? 3 }}" class="form-control"></textarea>
                                                                                    @else
                                                                                        <input
                                                                                            type="{{ $subField['type'] ?? 'text' }}"
                                                                                            name="{{ $subFieldName }}"
                                                                                            class="form-control">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                        <div class="col-lg-12 text-end">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-danger landing-repeater-remove">
                                                                                <i class="fas fa-trash"></i>
                                                                                {{ __('Remove') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label>{{ $field['label'] }}</label>

                                                            @if ($fieldType === 'textarea')
                                                                <textarea name="{{ $fieldName }}" rows="{{ $field['rows'] ?? 3 }}" class="form-control"
                                                                    placeholder="{{ $defaultValue }}">{{ $oldValue }}</textarea>
                                                            @elseif ($fieldType === 'product_select')
                                                                <select name="{{ $fieldName }}" class="form-select select2">
                                                                    <option value="">{{ __('Select Product') }}</option>
                                                                    @foreach ($productOptions as $productOption)
                                                                        <option value="{{ $productOption['id'] }}"
                                                                            @selected((string) $oldValue === (string) $productOption['id'])>
                                                                            {{ $productOption['title'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif ($fieldType === 'file')
                                                                <input type="file" name="{{ $fieldName }}"
                                                                    class="form-control"
                                                                    accept=".jpg,.jpeg,.png,.webp,.svg,.avif">
                                                                @if ($isEdit && !empty($currentImage))
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset($currentImage) }}"
                                                                            alt="{{ $field['label'] }}" width="120"
                                                                            class="rounded border">
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <input type="{{ $fieldType }}"
                                                                    name="{{ $fieldName }}" class="form-control"
                                                                    value="{{ $oldValue }}"
                                                                    placeholder="{{ $defaultValue }}">
                                                            @endif
                                                        </div>
                                                    @endif
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
                            {{ $isEdit ? __('Update Landing Page') : __('Generate Landing Page') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.landing-repeater').forEach(function(repeater) {
            var addButton = repeater.querySelector('.landing-repeater-add');
            var itemWrapper = repeater.querySelector('.landing-repeater-items');
            var template = repeater.querySelector('template[data-repeater-template]');
            var nextIndex = itemWrapper.querySelectorAll('[data-repeater-item]').length;

            if (addButton && itemWrapper && template) {
                addButton.addEventListener('click', function() {
                    var html = template.innerHTML.replace(/__INDEX__/g, nextIndex);
                    nextIndex += 1;
                    itemWrapper.insertAdjacentHTML('beforeend', html);
                });
            }

            repeater.addEventListener('click', function(event) {
                var removeButton = event.target.closest('.landing-repeater-remove');

                if (!removeButton) {
                    return;
                }

                var item = removeButton.closest('[data-repeater-item]');

                if (item) {
                    item.remove();
                }
            });
        });
    </script>
@endsection
