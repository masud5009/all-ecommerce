<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('admin.product.category_update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    @foreach ($languages as $language)
                        <input type="hidden" id="in_{{ $language->code }}_translation_id"
                            name="{{ $language->code }}_translation_id">
                    @endforeach

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Image') }}</label>
                            <br>
                            <div class="thumb-preview2">
                                <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="{{ __('Category image preview') }}"
                                    class="uploaded-img2 in_image">
                            </div>
                            <input type="file" class="img-input2" name="image" id="thumbnailInput2"
                                accept=".jpg,.jpeg,.png,.webp,.svg,.avif">
                            <small class="d-block mt-2 text-muted">{{ __('Recommended size') }}: 300x300px</small>
                            <p id="editErr_image" class="text-danger em"></p>
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
