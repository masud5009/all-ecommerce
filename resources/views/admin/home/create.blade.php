<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Add Home Slider') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" action="{{ route('admin.home.slider.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Language Selection -->
                        <x-text-input col="12" placeholder="Select a Language" name="language_id" type="select"
                            label="Language" :dataInfo="$languages" action="store" required="**" />

                        <!-- Image Upload -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="">{{ __('Slider Image') }} <span class="text-danger">**</span></label>
                                <br>
                                <div class="thumb-preview" data-image-input="#thumbnailInput">
                                    <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                        class="uploaded-img">
                                </div>
                                <input type="file" class="img-input" name="image" id="thumbnailInput"
                                    accept=".jpg,.jpeg,.png,.webp,.svg,.avif" data-preview-target=".uploaded-img">
                                <p id="err_image" class="text-danger em mt-2"></p>
                            </div>
                        </div>

                        <!-- Background Image Upload -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="">{{ __('Background Image') }}</label>
                                <br>
                                <div class="thumb-preview" data-image-input="#backgroundImageInput">
                                    <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                        class="uploaded-bg-img">
                                </div>
                                <input type="file" class="img-input" name="background_image" id="backgroundImageInput"
                                    accept=".jpg,.jpeg,.png,.webp,.svg,.avif" data-preview-target=".uploaded-bg-img">
                                <small class="d-block mt-2 text-muted">{{ __('Recommended size') }}: 1920x900px</small>
                                <p id="err_background_image" class="text-danger em mt-2"></p>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <x-text-input col="12" placeholder="Enter Title" name="title" type="text"
                            label="Title" action="store" required="**" />

                        <x-text-input col="12" placeholder="Enter Sub Title" name="sub_title" type="text"
                            label="Sub Title" action="store" required="**" />

                        <!-- Description (Textarea) -->
                        <x-text-input col="12" placeholder="Enter Description" name="description" type="textarea"
                            label="Description" action="store" required="**" />

                        <!-- Button 1 Section -->
                        <x-text-input col="6" placeholder="Enter Button Text 1" name="button_text_1"
                            type="text" label="Button Text 1" action="store" required="**" />

                        <x-text-input col="6" placeholder="Enter Button URL 1" name="button_url_1" type="text"
                            label="Button URL 1" action="store" required="**" />

                        <!-- Button 2 Section -->
                        <x-text-input col="6" placeholder="Enter Button Text 2" name="button_text_2"
                            type="text" label="Button Text 2" action="store" required="**" />

                        <x-text-input col="6" placeholder="Enter Button URL 2" name="button_url_2" type="text"
                            label="Button URL 2" action="store" required="**" />


                        <!-- Left Badge Section -->
                        <x-text-input col="6" placeholder="Enter image left badge title"
                            name="image_left_badge_title" type="text" label="Image Left Badge Title"
                            action="store" />

                        <x-text-input col="6" placeholder="Enter image left badge sub title"
                            name="image_left_badge_sub_title" type="text" label="Image Left Badge Sub Title"
                            action="store" />

                        <!-- Right Badge Section -->
                        <x-text-input col="6" placeholder="Enter image right badge title"
                            name="image_right_badge_title" type="text" label="Image Right Badge Title"
                            action="store" />

                        <x-text-input col="6" placeholder="Enter image right badge sub title"
                            name="image_right_badge_sub_title" type="text" label="Image Right Badge Sub Title"
                            action="store" />

                        <!-- Status and Serial -->
                        @php
                            $options = ['1' => 'Active', '0' => 'Inactive'];
                        @endphp

                        <x-text-input col="12" placeholder="Select a Status" name="status"
                            type="custom-select" label="Status" :dataInfo="$options" action="store" required="**" />

                        <x-text-input col="12" placeholder="Serial Number" name="serial_number" type="number"
                            label="Serial Number" action="store" required="**" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="submitBtn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
