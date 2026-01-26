<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Table') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('user.tables.update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <x-text-input col="12" placeholder="Enter table number" name="table_number" type="text"
                        label="Table Number" required="*" action="edit" />


                    <x-text-input col="12" placeholder="Enter capacity" name="capacity" type="number"
                        label="Capacity" required="*" action="edit" />

                    <x-text-input col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$statuses" action="edit" />

                    <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="text"
                        label="Serial Number" required="*" action="edit" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-success" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
