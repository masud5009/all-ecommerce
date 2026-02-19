<div class="offcanvas offcanvas-end language-management-offcanvas" tabindex="-1" id="langModal"
    aria-labelledby="langModalLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="langModalLabel">{{ __('Add Language') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="ajaxForm" action="{{ route('admin.language.store') }}" method="post">
            @csrf
            <div class="col-lg-12">
                <div class="row no-gutters">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Name') }}*</label>
                            <input type="text" class="form-control err_name" name="name"
                                placeholder="Enter Language Name">
                            <p id="err_name" class="text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Code') }}*</label>
                            <input type="text" class="form-control err_code" name="code"
                                placeholder="{{ __('Enter a language code (e.g. en)') }}">
                            <p id="err_code" class="text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Direction') }}*</label>
                            <select name="direction" class="form-select err_direction">
                                <option selected disabled>{{ __('Select a Direction') }}</option>
                                <option value="LTR">{{ __('LTR') }} ({{ __('Left To Right') }})</option>
                                <option value="RTL">{{ __('RTL') }} ({{ __('Right To Left') }})</option>
                            </select>
                            <p id="err_direction" class="text-danger em"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="language-offcanvas-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
