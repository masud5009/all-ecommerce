<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Feature Item') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.home.freshness.store') }}" method="post">
                    @csrf

                    <x-text-input col="12" placeholder="Select a Language" name="language_id" type="select"
                        label="Language" required="*" :dataInfo="$languages" action="store" />

                    <x-text-input col="12" placeholder="Enter title" name="title" type="text"
                        label="Title" required="*" action="store" />

                    <x-text-input col="12" placeholder="Enter description" name="text" type="textarea"
                        label="Description" action="store" />

                    @php
                        $positionOptions = ['left' => 'Left Side', 'right' => 'Right Side'];
                    @endphp
                    <x-text-input col="12" placeholder="Select a Position" name="position" type="custom-select"
                        label="Position" required="*" :dataInfo="$positionOptions" action="store" />

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <div class="dropdown js-iconpicker">
                                <button class="btn btn-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-iconpicker-selected class="fas fa-seedling"></i>
                                </button>
                                <div class="dropdown-menu p-3" style="width: 300px; max-height: 300px; overflow-y: auto;">
                                    <input type="text" class="form-control mb-2" data-iconpicker-search
                                        placeholder="{{ __('Search icons...') }}">
                                    <div class="d-flex flex-wrap" data-iconpicker-list></div>
                                </div>
                            </div>
                            <input type="hidden" data-iconpicker-input name="icon" value="fas fa-seedling">
                            <p id="err_icon" class="text-danger em"></p>
                        </div>
                    </div>

                    @php
                        $statusOptions = ['1' => 'Active', '0' => 'Inactive'];
                    @endphp
                    <x-text-input col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$statusOptions" action="store" />

                    <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="number"
                        label="Serial Number" required="*" action="store" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
