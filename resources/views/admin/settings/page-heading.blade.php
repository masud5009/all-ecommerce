@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('admin.website_setting') }}">{{ __('Settings Managment') }}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">{{ __('Page Heading') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Update Page Headings') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.website_setting') }}"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul></ul>
                </div>
                <div class="col-lg-12 mx-auto">
                    <form id="ajaxForm" action="{{ route('admin.website_setting.config_email_store') }}" method="post">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('404 Error Page Title') }}</label>
                                    <input type="text" name="page_not_found" class="form-control"
                                        value="{{ @$data->page_not_found }}">
                                    <p id="err_page_not_found" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('About Us Page Title') }}</label>
                                    <input type="text" name="about_us_page" class="form-control"
                                        value="{{ @$data->about_us_page }}">
                                    <p id="err_about_us_page" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('Blog Page Title') }}</label>
                                    <input type="text" name="blog_page" class="form-control"
                                        value="{{ @$data->blog_page }}">
                                    <p id="err_blog_page" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('FAQ Page Title') }}</label>
                                    <input type="text" name="faq_page" class="form-control"
                                        value="{{ @$data->faq_page }}">
                                    <p id="err_faq_page" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('Contact Page Title') }}</label>
                                    <input type="text" name="contact_page" class="form-control"
                                        value="{{ @$data->contact_page }}">
                                    <p id="err_contact_page" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>{{ __('User Login Page Title') }}</label>
                                    <input type="text" name="user_login_page" class="form-control"
                                        value="{{ @$data->user_login_page }}">
                                    <p id="err_user_login_page" class="text-danger em"></p>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer py-3 d-flex justify-content-center">
                <button class="btn btn-success submit-btn" id="submitBtn" type="button"><i
                        class="fas fa-save"></i>{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
