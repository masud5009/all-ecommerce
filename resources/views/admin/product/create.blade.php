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
                <a href="{{ route('admin.product', ['language' => $defaultLang->code]) }}">{{ __('Products') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Create Product') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul></ul>
        </div>
        <!--slider images-->
        <x-slider-image noteText="Recomanded Image size : 800x800" label="Gallery Images" />
        <form id="blogForm" action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!--prodct info-->
                <div class="col-lg-9">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div id="sliders"></div>
                                @if (request()->type == 'physical')
                                    <x-text-input col="6" placeholder="Enter stock" name="stock" type="text"
                                        label="Stock" required="*" />
                                @endif
                                <x-text-input col="6" placeholder="Enter current price" name="current_price"
                                    type="text" label="Current Price" required="*" />
                                <x-text-input col="6" placeholder="Enter previous price" name="previous_price"
                                    type="text" label="Previous Price" />


                                <x-text-input col="6" placeholder="Enter product sku" name="sku" type="text"
                                    label="SKU" required="*" />

                                @php
                                    $options = ['1' => 'Show', '0' => 'Hide'];
                                @endphp
                                <x-text-input col="6" placeholder="Select a Status" name="status"
                                    type="custom-select" label="Status" required="*" :dataInfo="$options" />

                                <x-text-input value="{{ ucfirst(request()->input('type')) }}" col="6" name="type"
                                    type="text" label="Type" required="*" attribute="readonly" />

                                {{-- File Type --}}
                                @if (request()->type == 'digital')
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Type') . ' **' }} </label>
                                            <select class="form-select" id="fileType" name="file_type">
                                                <option value="upload" selected>
                                                    {{ __('File Upload') }}</option>
                                                <option value="link">
                                                    {{ __('File Download Link') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div id="downloadFile" class="form-group">
                                            <label for="">{{ __('Downloadable File') . ' **' }} </label>
                                            <input type="file" class="form-control" id="customFile"
                                                name="download_file" />
                                            <p class="mb-0 text-warning">{{ __('Only zip file is allowed') }}</p>
                                        </div>

                                        <div id="downloadLink" class="form-group d-none">
                                            <label>{{ __('Downloadable Link') . ' **' }} </label>
                                            <input name="download_link" type="text" value="" class="form-control">
                                            <p id="errdownload_link" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- featuerd image -->
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Thumbnail*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                            class="uploaded-img">
                                    </div>
                                    <input type="file" class="img-input" name="thumbnail" id="thumbnailInput">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--prduction info language dependency-->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-lg-12 mx-auto">
                                <div class="row language-div">
                                    @include('admin.include.languages')
                                    @foreach ($languages as $lang)
                                        @php
                                            $code = $lang->code;
                                            $blogInfo = App\Models\BlogContent::where([
                                                ['language_id', $lang->id],
                                            ])->first();
                                        @endphp
                                        <div class="row language-content {{ $lang->id == $defaultLang->id ? '' : 'd-none' }}"
                                            id="language_{{ $lang->id }}">

                                            <x-text-input col="12" placeholder="Enter product title"
                                                name="{{ $lang->code }}_title" type="text" label="Title"
                                                required="*" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Select a Category"
                                                name="{{ $lang->code }}_category_id" type="select" label="Category"
                                                required="*" language="{{ $lang->code }}" :dataInfo="$lang->categories" />

                                            <x-text-input col="12" placeholder="Enter summary text"
                                                name="{{ $lang->code }}_summary" type="textarea" label="Summary"
                                                language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Enter description"
                                                name="{{ $lang->code }}_description" type="editor" label="Text"
                                                required="*" language="{{ $lang->code }}" />


                                            <x-text-input col="12" placeholder="Enter meta keyword"
                                                name="{{ $lang->code }}_meta_keyword" type="tagsinput"
                                                label="Meta Keyword" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Enter meta description"
                                                name="{{ $lang->code }}_meta_description" type="textarea"
                                                label="Meta Description" language="{{ $lang->code }}" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success" id="blogSubmit" type="button">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        const uploadSliderImage = "{{ route('admin.product.slider') }}";
        const rmvSliderImage = "{{ route('admin.product.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('admin.product.db-slider-remove') }}";
    </script>
    <script src="{{ asset('assets/admin/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
