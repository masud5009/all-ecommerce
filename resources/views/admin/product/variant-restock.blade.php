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

                @if ($selectedVariant)
                    <div class="alert alert-info">
                        <div><strong>{{ __('Selected Variant') }}:</strong> {{ $selectedVariantLabel ?? __('Default') }}</div>
                        @if ($lastSerialEnd)
                            <div><strong>{{ __('Last Serial End') }}:</strong> {{ $lastSerialEnd }}</div>
                        @endif
                        @if ($suggestedStart)
                            <div class="mt-1">
                                <strong>{{ __('Suggested Start') }}:</strong>
                                <span id="suggestedStartText">{{ $suggestedStart }}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                    id="useSuggestedStart" data-value="{{ $suggestedStart }}">
                                    {{ __('Use Suggested Start') }}
                                </button>
                            </div>
                        @endif
                    </div>

                    @if ($recentBatches->isNotEmpty())
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Batch No') }}</th>
                                        <th>{{ __('Serial Start') }}</th>
                                        <th>{{ __('Serial End') }}</th>
                                        <th>{{ __('Qty') }}</th>
                                        <th>{{ __('Sold Qty') }}</th>
                                        <th>{{ __('Available') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentBatches as $batch)
                                        <tr>
                                            <td>{{ $batch->batch_no }}</td>
                                            <td>{{ $batch->serial_start }}</td>
                                            <td>{{ $batch->serial_end }}</td>
                                            <td>{{ $batch->qty }}</td>
                                            <td>{{ $batch->sold_qty }}</td>
                                            <td>{{ (int) $batch->qty - (int) $batch->sold_qty }}</td>
                                            <td>{{ $batch->created_at ? $batch->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
                                    <select id="variantSelect" name="variant_id" class="form-select select2" required>
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

                        <div class="row mt-2">
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="autoEnd" checked>
                                    <label class="form-check-label" for="autoEnd">
                                        {{ __('Auto-calculate Serial End from Qty') }}
                                    </label>
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

@section('script')
    <script>
        (function () {
            var qtyInput = document.querySelector('input[name="qty"]');
            var startInput = document.querySelector('input[name="serial_start"]');
            var endInput = document.querySelector('input[name="serial_end"]');
            var autoEnd = document.getElementById('autoEnd');
            var suggestedBtn = document.getElementById('useSuggestedStart');
            var variantSelect = document.getElementById('variantSelect');

            function addNumericString(base, offset) {
                if (!base || !/^\d+$/.test(base)) {
                    return '';
                }
                var width = base.length;
                try {
                    var sum = (BigInt(base) + BigInt(offset)).toString();
                    return sum.padStart(width, '0');
                } catch (e) {
                    return '';
                }
            }

            function updateEnd() {
                if (!autoEnd || !autoEnd.checked) {
                    return;
                }
                if (!qtyInput || !startInput || !endInput) {
                    return;
                }
                var qty = parseInt(qtyInput.value, 10);
                var start = startInput.value.trim();
                if (!qty || qty < 1 || !start) {
                    return;
                }
                var end = addNumericString(start, qty - 1);
                if (end) {
                    endInput.value = end;
                }
            }

            if (qtyInput) {
                qtyInput.addEventListener('input', updateEnd);
            }
            if (startInput) {
                startInput.addEventListener('input', updateEnd);
            }
            if (autoEnd) {
                autoEnd.addEventListener('change', updateEnd);
            }
            if (suggestedBtn && startInput) {
                suggestedBtn.addEventListener('click', function () {
                    var value = suggestedBtn.getAttribute('data-value');
                    if (value) {
                        startInput.value = value;
                        updateEnd();
                    }
                });
            }
            if (variantSelect) {
                variantSelect.addEventListener('change', function () {
                    var params = new URLSearchParams(window.location.search);
                    params.set('variant_id', this.value);
                    window.location.search = params.toString();
                });
            }
        })();
    </script>
@endsection
