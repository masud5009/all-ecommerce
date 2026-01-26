<div class="modal fade" id="langModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Language') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
