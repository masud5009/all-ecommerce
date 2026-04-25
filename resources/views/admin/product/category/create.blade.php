<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.product.category_store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Image') }} <span class="text-danger">**</span></label>
                            <br>
                            <div class="thumb-preview">
                                <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="{{ __('Category image preview') }}"
                                    class="uploaded-img">
                            </div>
                            <input type="file" class="img-input" name="image" id="thumbnailInput"
                                accept=".jpg,.jpeg,.png,.webp,.svg,.avif">
                            <small class="d-block mt-2 text-muted">{{ __('Recommended size') }}: 300x300px</small>
                            <p id="err_image" class="text-danger em"></p>
                        </div>
                    </div>

                    @foreach ($languages as $language)
                        <x-text-input col="12" placeholder="Enter category name"
                            name="{{ $language->code }}_name" type="text"
                            label="Name ({{ $language->name }})"
                            required="{{ $language->is_default == 1 ? '*' : '' }}" action="store" />
                    @endforeach

                    @php
                        $options = ['1' => 'Active', '0' => 'Dactive'];
                    @endphp
                    <x-text-input col="12" placeholder="Select a Status" name="status" type="custom-select"
                        label="Status" required="*" :dataInfo="$options" action="store" />

                    <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="text"
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
