@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Blog Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Blogs') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Create') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Create Blog') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.blog') }}" class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul></ul>
                </div>
                <div class="col-lg-10 mx-auto">
                    <form id="blogForm" action="{{ route('admin.blog.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-3">
                                    <div class="card upload-card">
                                        <div class="card-body">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="">{{ __('Featured Image') }}*</label>
                                                    <br>
                                                    <div class="thumb-preview">
                                                        <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                                            class="uploaded-img">
                                                    </div>
                                                    <input type="file" class="img-input" name="image"
                                                        id="thumbnailInput">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Serial Number*</label>
                                    <input type="number" placeholder="Serial Number" name="serial_number"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Status*</label>
                                    <select name="status" class="form-select">
                                        <option selected disabled>Select Status</option>
                                        <option value="1">Show</option>
                                        <option value="0">Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row language-div">
                            @include('admin.include.languages')
                            @foreach ($languages as $lang)
                                @php
                                    $code = $lang->code;
                                    $categories = $lang->categories;
                                @endphp
                                <div class="row language-content {{ $lang->id == $defaultLang->id ? '' : 'd-none' }}"
                                    id="language_{{ $lang->id }}">

                                    <x-TextInput col="12" placeholder="Enter blog title"
                                        name="{{ $lang->code }}_title" type="text" label="Title" required="*"
                                        language="{{ $lang->code }}" />

                                    <x-TextInput col="6" placeholder="Select a Category"
                                        name="{{ $lang->code }}_category_id" type="select" label="Category"
                                        required="*" language="{{ $lang->code }}" :dataInfo="$categories" />

                                    <x-TextInput col="6" placeholder="Enter author name"
                                        name="{{ $lang->code }}_author" type="text" label="Author" required="*"
                                        language="{{ $lang->code }}" />

                                    <x-TextInput col="12" placeholder="Enter blog text"
                                        name="{{ $lang->code }}_text" type="editor" label="Text" required="*"
                                        language="{{ $lang->code }}" />

                                    <x-TextInput col="12" placeholder="Enter meta keyword"
                                        name="{{ $lang->code }}_meta_keyword" type="tagsinput" label="Meta Keyword"
                                        language="{{ $lang->code }}" />

                                    <x-TextInput col="12" placeholder="Enter meta description"
                                        name="{{ $lang->code }}_meta_description" type="textarea"
                                        label="Meta Description" language="{{ $lang->code }}" />
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" id="blogSubmit" type="button">Submit</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
