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
                <a href="#">{{ __('Variant Details') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Variant Details') }}</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.product.variant.restock', ['variant_id' => $variant->id]) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> {{ __('Restock') }}
                        </a>
                        <a href="{{ route('admin.product.edit', ['id' => $variant->product_id]) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <ul class="list-unstyled mb-0">
                            <li><strong>{{ __('Product') }}:</strong> {{ $productTitle }}</li>
                            <li><strong>{{ __('Variant') }}:</strong> {{ $variantLabel }}</li>
                            <li><strong>{{ __('SKU') }}:</strong> {{ $variant->sku ?? 'N/A' }}</li>
                            <li><strong>{{ __('Image') }}:</strong>
                                @if ($variant->image)
                                    <img src="{{ asset('assets/img/product/variant/' . $variant->image) }}"
                                        alt="Variant" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </li>
                            <li><strong>{{ __('Status') }}:</strong>
                                @if ((int) $variant->status === 1)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                @endif
                            </li>
                            <li><strong>{{ __('Track Serial') }}:</strong>
                                {{ (int) $variant->track_serial === 1 ? __('Yes') : __('No') }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <div class="alert alert-info mb-0">
                            <strong>{{ __('Available Stock') }}:</strong> {{ $availableStock }}
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="mb-2">{{ __('Batches') }}</h6>
                <div class="table-responsive">
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
                            @forelse ($variant->serialBatches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_no }}</td>
                                    <td>{{ $batch->serial_start }}</td>
                                    <td>{{ $batch->serial_end }}</td>
                                    <td>{{ $batch->qty }}</td>
                                    <td>{{ $batch->sold_qty }}</td>
                                    <td>{{ (int) $batch->qty - (int) $batch->sold_qty }}</td>
                                    <td>{{ $batch->created_at ? $batch->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No batches found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
