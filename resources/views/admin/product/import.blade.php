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
                <a href="{{ route('admin.product', ['language' => app('defaultLang')->code]) }}">{{ __('Products') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Import Products') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Import Products') }}</h5>
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

                @if (session('import_errors'))
                    <div class="alert alert-warning">
                        <strong>{{ __('Import Errors') }}</strong>
                        <ul class="mb-0 mt-2">
                            @foreach (session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.product.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Import File (CSV / Excel)') }} <span class="text-danger">**</span></label>
                                <input type="file" name="import_file" class="form-control" required>
                                <small class="text-muted">{{ __('Allowed: csv, xlsx, xls') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-import"></i> {{ __('Import') }}
                        </button>
                        <a href="{{ route('admin.product.import_template') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-download"></i> {{ __('Download CSV Template') }}
                        </a>
                        <a href="{{ route('admin.product.import_template_excel') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-download"></i> {{ __('Download Excel Template') }}
                        </a>
                    </div>
                </form>

                <hr>

                <div class="alert alert-info mb-0">
                    <strong>{{ __('Quick Guide') }}</strong>
                    <div class="mt-2">{{ __('Use either category_id (preferred) or category_name from default language.') }}</div>
                    <div>{{ __('row_type must be product or variant.') }}</div>
                    <div>{{ __('Type must be physical or digital.') }}</div>
                    <div>{{ __('For digital products, download_link is required (product row or any variant row).') }}</div>
                    <div>{{ __('SKU with leading zeros should be saved as text in Excel.') }}</div>

                    <div class="mt-3"><strong>{{ __('How To Add Variants') }}</strong></div>
                    <div>{{ __('Step 1: Add one product row and set a unique group_key.') }}</div>
                    <div>{{ __('Step 2: Add one variant row per variant using the same group_key.') }}</div>
                    <div>{{ __('Step 3: Fill variant_map using Option=Value format separated by |') }}</div>
                    <div>{{ __('Example: Size=L|Color=Red') }}</div>
                    <div>{{ __('All variant rows must use the same option keys (Size, Color, etc.).') }}</div>
                    <div>{{ __('If product has no variants, leave group_key empty and do not add any variant rows.') }}</div>
                    <div>{{ __('Variant rows required fields: group_key, variant_map, variant_sku, variant_stock, variant_status.') }}</div>
                    <div>{{ __('Variant price is optional (blank will keep product price).') }}</div>
                </div>

                <div class="mt-4">
                    <h6 class="mb-2">{{ __('Example Data (Simple + Variants)') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>row_type</th>
                                    <th>group_key</th>
                                    <th>product_sku</th>
                                    <th>title</th>
                                    <th>category_id</th>
                                    <th>category_name</th>
                                    <th>type</th>
                                    <th>status</th>
                                    <th>stock</th>
                                    <th>current_price</th>
                                    <th>previous_price</th>
                                    <th>summary</th>
                                    <th>description</th>
                                    <th>meta_keyword</th>
                                    <th>meta_description</th>
                                    <th>download_link</th>
                                    <th>variant_map</th>
                                    <th>variant_sku</th>
                                    <th>variant_price</th>
                                    <th>variant_stock</th>
                                    <th>variant_status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>product</td>
                                    <td></td>
                                    <td>000001</td>
                                    <td>Mens T-Shirt</td>
                                    <td>1</td>
                                    <td></td>
                                    <td>physical</td>
                                    <td>1</td>
                                    <td>25</td>
                                    <td>499</td>
                                    <td>699</td>
                                    <td>Soft cotton tee</td>
                                    <td>Premium cotton t-shirt with round neck.</td>
                                    <td>tshirt, men</td>
                                    <td>Mens cotton t-shirt</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>product</td>
                                    <td>VAR-GROUP-1</td>
                                    <td>TSHIRT-BASE</td>
                                    <td>T-Shirt Variants</td>
                                    <td>1</td>
                                    <td></td>
                                    <td>physical</td>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Soft cotton tee</td>
                                    <td>Premium cotton t-shirt</td>
                                    <td>tshirt, men</td>
                                    <td>Mens cotton t-shirt</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>variant</td>
                                    <td>VAR-GROUP-1</td>
                                    <td>TSHIRT-BASE</td>
                                    <td>T-Shirt Variants</td>
                                    <td>1</td>
                                    <td></td>
                                    <td>physical</td>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Soft cotton tee</td>
                                    <td>Premium cotton t-shirt</td>
                                    <td>tshirt, men</td>
                                    <td>Mens cotton t-shirt</td>
                                    <td></td>
                                    <td>Size=S|Color=Red</td>
                                    <td>TSHIRT-S-RED</td>
                                    <td>499</td>
                                    <td>10</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>variant</td>
                                    <td>VAR-GROUP-1</td>
                                    <td>TSHIRT-BASE</td>
                                    <td>T-Shirt Variants</td>
                                    <td>1</td>
                                    <td></td>
                                    <td>physical</td>
                                    <td>1</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Soft cotton tee</td>
                                    <td>Premium cotton t-shirt</td>
                                    <td>tshirt, men</td>
                                    <td>Mens cotton t-shirt</td>
                                    <td></td>
                                    <td>Size=S|Color=White</td>
                                    <td>TSHIRT-S-WHT</td>
                                    <td>499</td>
                                    <td>8</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted d-block mt-2">
                        {{ __('You can copy this format into your CSV/Excel. Keep headers exactly the same.') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
