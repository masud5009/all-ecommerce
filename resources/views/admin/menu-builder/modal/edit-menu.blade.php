<div class="offcanvas offcanvas-end menu-builder-offcanvas" id="editMenuModal" tabindex="-1"
    aria-labelledby="editMenuModalLabel">
    <div class="offcanvas-header bg-secondary text-white">
        <h5 class="offcanvas-title" id="editMenuModalLabel">Edit Menu Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body menu-builder-offcanvas-body">
        <input type="hidden" id="editItemId" />
        <div class="form-group">
            <input type="text" id="editTitle" placeholder="Title" class="form-control" />
            <p class="text-danger d-none m-0" id="eerr_editTitle"> </p>
        </div>
        <div class="form-group">
            <input type="text" id="editUrl" placeholder="URL" class="form-control" />
            <p class="text-danger d-none m-0" id="eerr_editUrl"> </p>
        </div>

        <div class="form-group">
            <select id="editTarget" class="form-select">
                <option value="_self">Same Tab</option>
                <option value="_blank">New Tab</option>
            </select>
        </div>
    </div>

    <div class="offcanvas-footer menu-builder-offcanvas-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
        <button type="button" class="btn btn-primary" id="updateMenuItem">
        {{ __('Update') }}
        </button>
    </div>
</div>
