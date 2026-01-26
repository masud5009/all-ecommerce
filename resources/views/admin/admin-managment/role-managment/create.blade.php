<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('Add Role')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.role_managment.create') }}" method="post">
                    @csrf
                    <div class="col-lg-12">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>{{ __('Name') }}*</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Role Name">
                                    <p id="err_name" class="text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitBtn">Save</button>
            </div>
        </div>
    </div>
</div>
