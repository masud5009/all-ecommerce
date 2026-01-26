<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> {{ __('Edit Admin') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('admin.all_admins.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    <div class="col-lg-12">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ __('Profile Image') }}*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="" alt="..." class="in_image uploaded-img">
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn ">
                                            <i class="fas fa-upload"></i> {{ __('Upload Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    <p id="editErr_image" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Role') }}*</label>
                                    <select name="role" id="in_role" class="form-select">
                                        <option selected disabled>{{ __('Select a role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="editErr_role" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Username') }}*</label>
                                    <input type="text" id="in_username" class="form-control" name="username"
                                        placeholder="Enter Username">
                                    <p id="editErr_username" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Email') }}*</label>
                                    <input type="email" id="in_email" class="form-control" name="email"
                                        placeholder="Enter Email">
                                    <p id="editErr_email" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('First Name') }}*</label>
                                    <input type="text" id="in_first_name" class="form-control" name="first_name"
                                        placeholder="Enter First Name">
                                    <p id="editErr_first_name" class="text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Last Name') }}</label>
                                    <input type="text" id="in_last_name" class="form-control" name="last_name"
                                        placeholder="Enter Last Name">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
