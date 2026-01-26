<div class="modal fade" id="AdminKeywordModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Admin Keyword') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adminKeyword" action="{{ route('admin.language.add_admin_keyword') }}" method="post">
                    @csrf
                    <div class="col-lg-12">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Keyword') . '*' }}</label>
                                    <input type="text" class="form-control" name="keyword"
                                        placeholder="{{ __('Enter Keyword') }}">
                                    <p id="err_keyword" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="adminKeywordBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
