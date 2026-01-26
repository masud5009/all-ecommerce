@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><span class="fas fa-home"></span></a></li>
            <li class="breadcrumb-item active" aria-current="page">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">SEO Informations</li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>Update SEO Informations</h5>
            </div>
            <div class="card-body">
                <div class="col-lg-12 p-3">
                    <form id="editProfileForm" action="{{ route('admin.footer_logo.update') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-conrol">
                                    <label>Meta Keywords For Home Page</label>
                                    <input type="text" data-role="tagsinput" class="form-control" name="website_title">
                                </div>
                                <div class="form-conrol">
                                    <label>Meta Description For Home Page</label>
                                    <textarea name="meta_description_home_page" placeholder="Enter Meta Description" cols="30" rows="6" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-conrol">
                                    <label>Meta Keywords For Contact Page</label>
                                    <input type="text" data-role="tagsinput" class="form-control" name="website_title">
                                </div>
                                <div class="form-conrol">
                                    <label>Meta Description For Contact Page</label>
                                    <textarea name="meta_description_contact_page" placeholder="Enter Meta Description" cols="30" rows="6" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <button type="submit" form="editProfileForm" class="btn btn-success py-2">Update</button>
            </div>
        </div>
    </div>
@endsection
