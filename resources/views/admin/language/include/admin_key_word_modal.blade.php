<div class="offcanvas offcanvas-end language-management-offcanvas" tabindex="-1" id="AdminKeywordModal"
    aria-labelledby="adminKeywordModalLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="adminKeywordModalLabel">{{ __('Add Admin Keyword') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
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

            <div class="language-offcanvas-actions">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="adminKeywordBtn">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
