<div class="offcanvas offcanvas-end language-management-offcanvas" tabindex="-1" id="langEditModal"
    aria-labelledby="langEditModalLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="langEditModalLabel">{{ __('Edit Language') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="ajaxEditForm" action="{{ route('admin.language.update') }}" method="post">
            @csrf
            <input type="hidden" id="in_id" name="id">
            <div class="col-lg-12">
                <div class="row no-gutters">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Name') . '*' }}</label>
                            <input type="text" class="form-control" id="in_name" name="name"
                                placeholder="{{ __('Enter Language Name') }}">
                            <p id="editErr_name" class="text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Code') . '*' }}</label>
                            <input type="text" class="form-control err_code" id="in_code" name="code"
                                placeholder="{{ __('Enter a language code (e.g., en)') }}">
                            <p id="editErr_code" class="text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Direction') . '*' }}</label>
                            <select name="direction" id="in_direction" class="form-select">
                                <option selected disabled>{{ __('Select a Direction') }}</option>
                                <option value="LTR">{{ __('LTR') }} ({{ __('Left To Right') }})</option>
                                <option value="RTL">{{ __('RTL') }} ({{ __('Right To Left') }})
                                </option>
                            </select>
                            <p id="editErr_direction" class="text-danger em"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="language-offcanvas-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
