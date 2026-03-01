<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.product.category_store') }}" method="post">
                    @csrf

                    <x-text-input col="12" placeholder="Select a Language" name="language_id" type="select"
                        label="Language" required="*" :dataInfo="$languages" action="store" />

                    <x-text-input col="12" placeholder="Enter category name" name="name" type="text"
                        label="Name" required="*" action="store" />

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Icon') }}</label>
                            <div class="input-group">
                                <input type="text" name="icon"
                                    class="form-control icp-dd err_icon"
                                    placeholder="{{ __('Pick an icon') }}" value="fas fa-seedling"
                                    data-placement="bottomRight" data-input-search="true"
                                    data-hide-on-select="true">
                                <span class="input-group-text iconpicker-component"><i class="fas fa-seedling"></i></span>
                            </div>
                            <p id="err_icon" class="text-danger em"></p>
                        </div>
                    </div>

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
