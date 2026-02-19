<div class="offcanvas offcanvas-end menu-builder-offcanvas" id="customMenuModal" tabindex="-1"
    aria-labelledby="customMenuModalLabel">
    <div class="offcanvas-header bg-success text-white">
        <h5 class="offcanvas-title" id="customMenuModalLabel">Add Custom Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body menu-builder-offcanvas-body">
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

    <div class="offcanvas-footer menu-builder-offcanvas-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
        <button type="button" class="btn btn-success" id="addCustomMenu">
            {{ __('Add') }}
        </button>
    </div>
</div>
