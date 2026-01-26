<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Update Stock') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{route('admin.inventory_managmement.update_stock_submit')}}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <x-TextInput col="12" placeholder="Enter stock" name="stock" type="number"
                        label="Current Stock" required="*" action="edit" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
