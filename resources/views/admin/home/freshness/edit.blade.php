<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Feature Item') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('admin.home.freshness.update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">

                    <x-TextInput col="12" placeholder="Enter title" name="title" type="text"
                        label="Title" required="*" action="edit" />

                    <x-TextInput col="12" placeholder="Enter description" name="text" type="textarea"
                        label="Description" action="edit" />

                    @php
                        $positionOptions = ['left' => 'Left Side', 'right' => 'Right Side'];
                    @endphp
                    <x-TextInput col="12" placeholder="Select a Position" name="position" type="custom-select"
                        label="Position" required="*" :dataInfo="$positionOptions" action="edit" />

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <div class="dropdown js-iconpicker">
                                <button class="btn btn-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i id="in_selectedIcon" data-iconpicker-selected class="fas fa-seedling"></i>
                                </button>
                                <div class="dropdown-menu p-3" style="width: 300px; max-height: 300px; overflow-y: auto;">
                                    <input type="text" class="form-control mb-2" data-iconpicker-search
                                        placeholder="{{ __('Search icons...') }}">
                                    <div class="d-flex flex-wrap" data-iconpicker-list></div>
                                </div>
                            </div>
                            <input type="hidden" id="in_icon" data-iconpicker-input name="icon" value="fas fa-seedling">
                            <p id="editErr_icon" class="text-danger em"></p>
                        </div>
                    </div>

                    @php
                        $statusOptions = ['1' => 'Active', '0' => 'Inactive'];
                    @endphp
                    <x-TextInput col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$statusOptions" action="edit" />

                    <x-TextInput col="12" placeholder="Serial Number" name="serial_number" type="number"
                        label="Serial Number" required="*" action="edit" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
