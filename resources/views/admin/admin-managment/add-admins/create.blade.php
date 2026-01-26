<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Admin') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.all_admins.create') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ __('Profile Image')}}*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                            class="uploaded-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn ">
                                            <i class="fas fa-upload"></i> {{ __('Upload Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    <p id="err_image" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Role') }}*</label>
                                    <select name="role" class="form-select">
                                        <option selected disabled>{{ __('Select a role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="err_role" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Username') }}*</label>
                                    <input type="text" class="form-control" name="username"
                                        placeholder="Enter Username">
                                    <p id="err_username" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Email') . '*' }}</label>
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Enter Email">
                                    <p id="err_email" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('First Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="first_name"
                                        placeholder="Enter First Name">
                                    <p id="err_first_name" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Last Name') }}</label>
                                    <input type="text" class="form-control" name="last_name"
                                        placeholder="Enter Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Password') . '*' }}</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Enter Password">
                                    <p id="err_password" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Cofirm Password') . '*' }}</label>
                                    <input type="password" class="form-control"
                                        name="password_confirmation" placeholder="Enter Confirm Password">
                                    <p id="err_password_confirmation" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
