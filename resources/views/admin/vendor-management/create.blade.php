<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Vendor') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.vendor.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Username') }}*</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Username">
                                <p id="err_username" class="text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Email') . '*' }}</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email">
                                <p id="err_email" class="text-danger em"></p>
                            </div>
                        </div>
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
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Enter Confirm Password">
                                <p id="err_password_confirmation" class="text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Select Package') }}*</label>
                                <select name="package_id" class="form-select">
                                    <option selected disabled>{{ __('Select a Package') }}</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->title }}({{ $package->term }})
                                        </option>
                                    @endforeach
                                </select>
                                <p id="err_package_id" class="text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Payment Gateway') }}*</label>
                                <select name="gateway" class="form-select">
                                    <option selected disabled>{{ __('Select a Gateway') }}</option>
                                    @foreach ($paymentMethod as $method)
                                        <option value="{{ $method->keyword }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <p id="err_gateway" class="text-danger em"></p>
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
