<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Edit Home Slider') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" action="{{ route('admin.home.slider.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="in_id" name="id">
                    <div class="row">
                        <!-- Image Upload -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="">{{ __('Image') }}</label>
                                <br>
                                <div class="thumb-preview2">
                                    <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                        class="uploaded-img2 in_image">
                                </div>

                            </div>
                        </div>
                        <input type="file" class="img-input2" name="image" id="thumbnailInput2"
                            accept=".jpg,.jpeg,.png,.webp,.svg">
                        <p id="editErr_image" class="text-danger em mt-2"></p>

                        <!-- Main Content -->
                        <x-text-input col="12" placeholder="Enter Title" name="title" type="text"
                            label="Title" action="edit" required="**" />

                        <x-text-input col="12" placeholder="Enter Sub Title" name="sub_title" type="text"
                            label="Sub Title" action="edit" required="**" />

                        <!-- Description (Textarea) -->
                        <x-text-input col="12" placeholder="Enter Description" name="description" type="textarea"
                            label="Description" action="edit" required="**" />

                        <!-- Button 1 Section -->
                        <x-text-input col="6" placeholder="Enter Button Text 1" name="button_text_1"
                            type="text" label="Button Text 1" action="edit" required="**" />

                        <x-text-input col="6" placeholder="Enter Button URL 1" name="button_url_1" type="text"
                            label="Button URL 1" action="edit" required="**" />

                        <!-- Button 2 Section -->
                        <x-text-input col="6" placeholder="Enter Button Text 2" name="button_text_2"
                            type="text" label="Button Text 2" action="edit" required="**" />

                        <x-text-input col="6" placeholder="Enter Button URL 2" name="button_url_2" type="text"
                            label="Button URL 2" action="edit" required="**" />


                        <!-- Left Badge Section -->
                        <x-text-input col="6" placeholder="Enter image left badge title"
                            name="image_left_badge_title" type="text" label="Image Left Badge Title"
                            action="edit" />

                        <x-text-input col="6" placeholder="Enter image left badge sub title"
                            name="image_left_badge_sub_title" type="text" label="Image Left Badge Sub Title"
                            action="edit" />

                        <!-- Right Badge Section -->
                        <x-text-input col="6" placeholder="Enter image right badge title"
                            name="image_right_badge_title" type="text" label="Image Right Badge Title"
                            action="edit" />

                        <x-text-input col="6" placeholder="Enter image right badge sub title"
                            name="image_right_badge_sub_title" type="text" label="Image Right Badge Sub Title"
                            action="edit" />

                        <!-- Status and Serial -->
                        @php
                            $options = ['1' => 'Active', '0' => 'Inactive'];
                        @endphp

                        <x-text-input col="12" placeholder="Select a Status" name="status"
                            type="custom-select" label="Status" :dataInfo="$options" action="edit" required="**" />

                        <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="number"
                            label="Serial Number" action="edit" required="**" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="updateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
