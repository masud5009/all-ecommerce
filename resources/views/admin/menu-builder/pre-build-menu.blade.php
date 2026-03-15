<div class="col-md-3">
    <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Static Menu</h5>
        </div>
        <ul class="list-group list-group-flush" id="staticMenuList">
            <li class="list-group-item d-flex justify-content-between align-items-center" data-title="Home" data-url="/"
                data-target="_self">
                 {{ __('Home') }}
                <button class="btn btn-sm btn-primary addToMenu" title="Add to Menu">
                    <i class="fa fa-plus me-1"></i>{{ __('Add') }}
                </button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center" data-title="Shop"
                data-url="/shop" data-target="_self">
                 {{ __('Shop') }}
                <button class="btn btn-sm btn-primary addToMenu" title="Add to Menu">
                    <i class="fa fa-plus me-1"></i>{{ __('Add') }}
                </button>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center" data-title="Contact"
                data-url="/contact" data-target="_self">
                 {{ __('Contact') }}
                <button class="btn btn-sm btn-primary addToMenu" title="Add to Menu">
                    <i class="fa fa-plus me-1"></i>{{ __('Add') }}
                </button>
            </li>
        </ul>
    </div>
</div>
