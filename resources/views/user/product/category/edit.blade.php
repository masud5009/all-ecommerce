<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('user.product.category_update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <x-TextInput col="12" placeholder="Enter category name" name="name" type="text"
                        label="Name" required="*" action="edit" />

                    @php
                        $options = ['1' => 'Active', '0' => 'Dactive'];
                    @endphp
                    <x-TextInput col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$options" action="edit" />

                    <x-TextInput col="12" placeholder="Serial Number" name="serial_number" type="text"
                        label="Serial Number" required="*" action="edit" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
            </div>
        </div>
    </div>
</div>
