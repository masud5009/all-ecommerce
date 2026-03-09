@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Home Section') }}</li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Home Section') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.home.section.update') }}" method="POST" id="ajaxForm">
                    @csrf
                    <input type="hidden" name="language_id" value="{{ $language_id }}">
                    @foreach (config('website') as $section => $fields)
                        <div class="border border-secondary rounded p-4 mb-5 position-relative">
                            <span
                                class="position-absolute top-0 start-3 translate-middle-y bg-white px-3 text-warning fw-bold fs-5">
                                {{ ucwords(str_replace('_', ' ', $section)) }}
                            </span>

                            <div class="row mt-3">
                                @foreach ($fields as $name => $meta)
                                    @php
                                        $type = $meta['type'] ?? 'text';
                                        $label = $meta['label'] ?? ucwords(str_replace('_', ' ', $name));
                                        $rows = $meta['rows'] ?? 3;
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ $label }}</label>

                                        @if ($type === 'textarea')
                                            <textarea name="{{ $name }}" rows="{{ $rows }}" class="form-control">{{ old($name, $data[$name] ?? '') }}</textarea>
                                        @else
                                            <input type="{{ $type }}" name="{{ $name }}"
                                                class="form-control" value="{{ old($name, $data[$name] ?? '') }}">
                                        @endif

                                        <p id="editErr_{{ $name }}" class="mt-1 mb-0 text-danger em"></p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Update') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
