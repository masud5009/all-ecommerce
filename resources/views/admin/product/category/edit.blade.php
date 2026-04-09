<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('admin.product.category_update') }}" method="post">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    @foreach ($languages as $language)
                        <input type="hidden" id="in_{{ $language->code }}_translation_id"
                            name="{{ $language->code }}_translation_id">
                    @endforeach

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
                                    <div class="d-flex flex-wrap" data-iconpicker-list>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="in_icon" data-iconpicker-input name="icon" value="fas fa-seedling">
                            <p id="editErr_icon" class="text-danger em"></p>
                        </div>
                    </div>

                    @foreach ($languages as $language)
                        <x-TextInput col="12" placeholder="Enter category name"
                            name="{{ $language->code }}_name" type="text"
                            label="Name ({{ $language->name }})"
                            required="{{ $language->is_default == 1 ? '*' : '' }}" action="edit" />
                    @endforeach

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
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
            </div>
        </div>
    </div>
</div>
