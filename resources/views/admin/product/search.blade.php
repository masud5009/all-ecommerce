    <div class="offcanvas offcanvas-end" tabindex="-1" id="productFilterOffcanvas"
        aria-labelledby="productFilterOffcanvasLabel" style="width: 380px; max-width: 92vw;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="productFilterOffcanvasLabel">{{ __('Filter Products') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.product') }}" method="get" class="h-100 d-flex flex-column">
            <input type="hidden" name="language" value="{{ request('language', app('defaultLang')->code) }}">

            <div class="offcanvas-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Search') }}</label>
                    <input type="search" name="search" class="form-control"
                        placeholder="{{ __('Search by title or category') }}" value="{{ $search ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="1" @selected(request('status') === '1')>{{ __('Active') }}</option>
                        <option value="0" @selected(request('status') === '0')>{{ __('Inactive') }}</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('Stock') }}</label>
                    <select name="stock" class="form-control">
                        <option value="">{{ __('All Stock') }}</option>
                        <option value="in_stock" @selected(request('stock') === 'in_stock')>{{ __('In Stock') }}</option>
                        <option value="out_of_stock" @selected(request('stock') === 'out_of_stock')>
                            {{ __('Out of Stock') }}
                        </option>
                        <option value="low_stock" @selected(request('stock') === 'low_stock')>
                            {{ __('Low Stock (1-5)') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="border-top p-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-search"></i> {{ __('Apply') }}
                </button>
                <a href="{{ route('admin.product', ['language' => request('language', app('defaultLang')->code)]) }}"
                    class="btn btn-light border btn-sm flex-fill">
                    {{ __('Reset') }}
                </a>
            </div>
        </form>
    </div>
