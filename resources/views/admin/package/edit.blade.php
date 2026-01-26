@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Package Managment') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.package') }}">{{ __('Packages') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ $package->title }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Edit Package') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Edit Package') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="info-header-content">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                                data-href="{{ route('admin.package.bulk_delete') }}">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </button>
                            <a href="{{ route('admin.package') }}" class="btn btn-primary btn-sm float-lg-end float-left">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-8 mx-auto">
                    <form id="ajaxEditForm" action="{{ route('admin.package.update', ['id' => $package->id]) }}"
                        method="post">
                        @csrf
                        <div class="row">
                            <!--icon-->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ __('Select Icon') }}</label>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="iconDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i id="selectedIcon" class="{{ $package->icon }}"></i>
                                        </button>
                                        <div class="dropdown-menu p-3"
                                            style="width: 300px; max-height: 300px; overflow-y: auto;">
                                            <input type="text" class="form-control mb-2" id="iconSearch"
                                                placeholder="Search icons...">
                                            <div class="d-flex flex-wrap" id="iconList">
                                                <!-- Icons will be loaded here -->
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="inputIcon" name="icon">
                                </div>
                            </div>
                            <!--title-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Title') }}<span class="text-danger">**</span></label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter Package Title') }}" value="{{ @$package->title }}">
                                    <p id="editErr_title" class="text-danger em"></p>
                                </div>
                            </div>
                            <!--price-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Price') }}<span class="text-danger">**</span></label>
                                    <input type="number" min="0" class="form-control" name="price"
                                        placeholder="{{ __('Enter Package Price') }}" value="{{ @$package->price }}">
                                    <p id="editErr_price" class="text-danger em"></p>
                                    <small class="text-warning">
                                        {{ __('If price is 0 , than it will appear as free') }}
                                    </small>
                                </div>
                            </div>
                            <!--trial-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="is_trial">{{ __('Trial ') }} <span class="text-danger">**</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_trial" value="1" class="selectgroup-input"
                                                id="trial_enable" @checked($package->is_trial == 1)>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_trial" value="0" class="selectgroup-input"
                                                id="trial_disable" @checked($package->is_trial == 0)>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--trial days-->
                            <div class="col-lg-6" id="trial_days_container"
                                style="{{ @$package->is_trial == 0 ? 'display: none;' : '' }}">
                                <div class="form-group">
                                    <label for="trial_days">{{ __('Trial Days') }} <span
                                            class="text-danger">**</span></label>
                                    <input type="number" name="trial_days" id="trial_days" class="form-control"
                                        placeholder="Enter number of days" value="{{ @$package->trial_days }}">
                                    <p id="err_trial_days" class="text-danger em"></p>
                                </div>
                            </div>
                            <!--term-->
                            <div class="col-lg-6" id="package_term_container"
                                style="display:{{ @$package->is_trial == 0 ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label>{{ __('Package term') }}<span class="text-danger">**</span></label>
                                    <select name="term" class="form-select">
                                        <option selected disabled>{{ __('Choose a package term') }}</option>
                                        <option {{ $package->term == 'monthly' ? 'selected' : '' }} value="monthly">
                                            {{ __('Monthly') }}
                                        </option>
                                        <option {{ $package->term == 'yearly' ? 'selected' : '' }} value="yearly">
                                            {{ __('Yearly') }}
                                        </option>
                                        <option {{ $package->term == 'lifetime' ? 'selected' : '' }} value="lifetime">
                                            {{ __('Lifetime') }}
                                        </option>
                                    </select>

                                    <p id="editErr_term" class="text-danger em"></p>
                                </div>
                            </div>
                            <!--features-->
                            <div class="col-lg-12">
                                @php
                                    $permissions = $package->features;
                                    if (!empty($package->features)) {
                                        $permissions = json_decode($permissions, true);
                                    }
                                @endphp
                                <div class="form-group">
                                    <label>{{ __('Features') }}</label>
                                    <br>
                                    <div class="selectgroup selectgroup-pills mt-2">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Hostel Management"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Hostel Management', $permissions)) checked @endif>
                                            <span class="selectgroup-button">Hostel Management</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Course Management"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Course Management', $permissions)) checked @endif>
                                            <span class="selectgroup-button">Course Management</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="features[]" value="Student Management"
                                                class="selectgroup-input" @if (is_array($permissions) && in_array('Student Management', $permissions)) checked @endif>
                                            <span class="selectgroup-button">Student Management</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--status-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('Status') }}<span class="text-danger">**</span></label>
                                    <select name="status" class="form-select">
                                        <option selected disabled>{{ __('Select Status') }}</option>
                                        <option {{ $package->status == 1 ? 'selected' : '' }} value="1">
                                            {{ __('Active') }}</option>
                                        <option {{ $package->status == 0 ? 'selected' : '' }} value="0">
                                            {{ __('Dactive') }}</option>
                                    </select>
                                    <p id="editErr_status" class="text-danger em"></p>
                                </div>
                            </div>
                            <!--recomended-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="recomended">{{ __('Recomended') }}</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="recomended" value="1"
                                                class="selectgroup-input"
                                                {{ $package->recomended == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="recomended"
                                                {{ $package->recomended == 0 ? 'checked' : '' }} value="0"
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--featured-->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="is_featured">{{ __('Featured') }}<span
                                            class="text-danger">**</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_featured" value="1"
                                                class="selectgroup-input" @checked($package->is_featured == 1)>
                                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                                        </label>

                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_featured" value="0"
                                                class="selectgroup-input" @checked($package->is_featured == 0)>
                                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--custom feature-->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Custom Feature') }}</label>
                                    <textarea name="custom_feature" cols="30" rows="4" class="form-control">{{ @$package->custom_feature }}</textarea>
                                    <small
                                        class="text-warning">{{ __('Each new line will be shown as a new feature in the pricing plan.') }}</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const enableRadio = document.getElementById('trial_enable');
        const disableRadio = document.getElementById('trial_disable');
        const daysContainer = document.getElementById('trial_days_container');
        const termContainer = document.getElementById('package_term_container');

        enableRadio.addEventListener('change', function() {
            if (this.checked) {
                daysContainer.style.display = 'block';
                termContainer.style.display = 'none';
            }
        });

        disableRadio.addEventListener('change', function() {
            if (this.checked) {
                daysContainer.style.display = 'none';
                termContainer.style.display = 'block';
            }
        });
    </script>
@endsection
