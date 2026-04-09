<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Subcategory') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.product.subcategory_store') }}" method="post">
                    @csrf

                    <x-text-input col="12" placeholder="Select a Language" name="language_id" type="select"
                        label="Language" required="*" :dataInfo="$languages" action="store" />

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>{{ __('Category') }} <span class="text-danger">*</span></label>
                            <select id="subcategoryCategorySelect" name="category_id" class="form-control">
                                <option value="">{{ __('Select a category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p id="err_category_id" class="mb-0 text-danger em"></p>
                        </div>
                    </div>

                    <x-text-input col="12" placeholder="Enter subcategory name" name="name" type="text"
                        label="Name" required="*" action="store" />

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
