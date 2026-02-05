@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Product Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Restock Variant') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Restock Variant') }}</h5>
                    <a href="{{ route('admin.product', ['language' => app('defaultLang')->code]) }}"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($variants->isEmpty())
                    <div class="alert alert-warning mb-0">
                        {{ __('No serial-tracked variants found.') }}
                    </div>
                @else
                    <form action="{{ route('admin.product.variant.restock_store') }}" method="post">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Variant') }} <span class="text-danger">**</span></label>
                                    <select name="variant_id" class="form-select select2" required>
                                        <option value="" disabled
                                            @selected(!old('variant_id', $selectedVariantId))>
                                            {{ __('Select Variant') }}
                                        </option>
                                        @foreach ($variants as $variant)
                                            <option value="{{ $variant->id }}"
                                                @selected(old('variant_id', $selectedVariantId) == $variant->id)>
                                                {{ $variant->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>{{ __('Qty') }} <span class="text-danger">**</span></label>
                                    <input type="number" class="form-control" name="qty" min="1"
                                        value="{{ old('qty') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>{{ __('Serial Start') }} <span class="text-danger">**</span></label>
                                    <input type="text" class="form-control" name="serial_start"
                                        value="{{ old('serial_start') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>{{ __('Serial End') }} <span class="text-danger">**</span></label>
                                    <input type="text" class="form-control" name="serial_end"
                                        value="{{ old('serial_end') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus"></i> {{ __('Restock') }}
                            </button>
                            <span class="text-muted">
                                {{ __('Serial start/end must be numeric and non-overlapping per variant.') }}
                            </span>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
