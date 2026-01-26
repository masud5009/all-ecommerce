<div class="modal fade" id="customMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Custom Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" id="menuTitle" placeholder="Title" class="form-control" />
                    <p class="text-danger d-none m-0" id="err_menuTitle"> </p>
                </div>
                <div class="form-group">
                    <input type="text" id="menuUrl" placeholder="URL" class="form-control" />
                    <p class="text-danger d-none m-0" id="err_menuUrl"> </p>
                </div>

                <div class="form-group">
                    <select id="menuTarget" class="form-select">
                        <option value="_self">Same Tab</option>
                        <option value="_blank">New Tab</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="addCustomMenu">
                    <i class="fa fa-plus me-1"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>
