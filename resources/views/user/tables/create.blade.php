<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Table') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('user.tables.store') }}" method="post">
                    @csrf
                    <x-text-input col="12" placeholder="Select a Language" name="language_id" type="select"
                        label="Language" required="*" :dataInfo="$languages" action="store" />

                    <x-text-input col="12" placeholder="Enter table number" name="table_number" type="text"
                        label="Table Number" required="*" action="store" />


                    <x-text-input col="12" placeholder="Enter capacity" name="capacity" type="number"
                        label="Capacity" required="*" action="store" />

                    <x-text-input col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$statuses" action="store" />

                    <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="text"
                        label="Serial Number" required="*" action="store" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-success" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
