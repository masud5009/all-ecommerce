@extends('admin.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/menu-builder.css') }}">
@endsection
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">{{ __('Menu Builder') }}</li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Menu Builder') }}</h5>
            </div>
            <div class="card-body">
                <div class="container-fluid mt-4">
                    <div class="row g-4">
                        <!-- Static Menu -->
                        @include('admin.menu-builder.pre-build-menu')

                        <!-- Menu Builder (larger col now) -->
                        <div class="col-md-9">
                            <div class="card shadow-sm h-100 d-flex flex-column">
                                <div
                                    class="card-header bg-primary text-white  d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Menu Builder</h5>
                                    <div>
                                        <button class="btn btn-secondary me-2 btn-sm" data-bs-toggle="offcanvas"
                                            data-bs-target="#customMenuModal">
                                            <i class="fa fa-plus me-1"></i> Add Custom Menu
                                        </button>
                                        <button class="btn btn-success btn-sm" id="saveMenu" title="Save Menu">
                                            <i class="fa fa-save me-1"></i> Save Menu
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body menu-list overflow-auto p-3" style="min-height: 400px;">
                                    <ul id="menuBuilder" class="sortable list-group">
                                        @if (!empty($menus))
                                            @php renderMenu($menus); @endphp
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Custom Menu Offcanvas -->
                @include('admin.menu-builder.modal.custom-menu')

                <!-- Edit Menu Offcanvas -->
                @include('admin.menu-builder.modal.edit-menu')
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const updateUrl = "{{ route('admin.menu_builder.update') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="{{ asset('assets/admin/js/menu-builder.js') }}"></script>
@endsection
