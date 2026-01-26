<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Package') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.package.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <!--icon-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('Select Icon') }}</label>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="iconDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i id="selectedIcon" class="fas fa-chevron-down"></i>
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
                                    placeholder="{{ __('Enter Package Title') }}">
                                <p id="err_title" class="text-danger em"></p>
                            </div>
                        </div>
                        <!--price-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Price') }}<span class="text-danger">**</span></label>
                                <input type="number" min="0" class="form-control" name="price"
                                    placeholder="{{ __('Enter Package Price') }}">
                                <p id="err_price" class="text-danger em"></p>
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
                                            id="trial_enable">
                                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" name="is_trial" value="0" class="selectgroup-input"
                                            id="trial_disable" checked>
                                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--trial days-->
                        <div class="col-lg-6" id="trial_days_container" style="display: none;">
                            <div class="form-group">
                                <label for="trial_days">{{ __('Trial Days') }} <span
                                        class="text-danger">**</span></label>
                                <input type="number" name="trial_days" id="trial_days" class="form-control"
                                    placeholder="Enter number of days">
                                <p id="err_trial_days" class="text-danger em"></p>
                            </div>
                        </div>
                        <!--term-->
                        <div class="col-lg-6" id="package_term_container" style="display: block">
                            <div class="form-group">
                                <label>{{ __('Package term') }}<span class="text-danger">**</span></label>
                                <select name="term" class="form-select">
                                    <option selected disabled>{{ __('Choose a package term') }}</option>
                                    <option value="monthly">{{ __('Monthly') }}</option>
                                    <option value="yearly">{{ __('Yearly') }}</option>
                                    <option value="lifetime">{{ __('Lifetime') }}</option>
                                </select>
                                <p id="err_term" class="text-danger em"></p>
                            </div>
                        </div>
                        <!--features-->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Features') }}</label>
                                <br>
                                <div class="selectgroup selectgroup-pills mt-2">
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Custom Domain"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Custom Domain</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Subdomain"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Subdomain</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="features[]" value="Custom Page"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Custom Page</span>
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
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Dactive') }}</option>
                                </select>
                                <p id="err_status" class="text-danger em"></p>
                            </div>
                        </div>
                        <!--recomended-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="recomended">{{ __('Recomended') }}<span
                                        class="text-danger">**</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="recomended" value="1"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" name="recomended" value="0"
                                            class="selectgroup-input" checked>
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
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">{{ __('Enable') }}</span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" name="is_featured" value="0"
                                            class="selectgroup-input" checked>
                                        <span class="selectgroup-button">{{ __('Disable') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--custom feature-->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>{{ __('Custom Feature') }}</label>
                                <textarea name="custom_feature" cols="30" rows="4" class="form-control"></textarea>
                                <small
                                    class="text-warning">{{ __('Each new line will be shown as a new feature in the pricing plan.') }}</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-success" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
