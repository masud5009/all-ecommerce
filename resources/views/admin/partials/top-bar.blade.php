<div class="navbar navbar-expand-md bg-nav">
    <div class="navbar-logo">
        <a href="{{ route('frontend.index') }}" class="logo" target="_blank">
            <img src="{{ asset('assets/front/img/' . $websiteInfo->website_logo) }}" alt="" class="navbar-brand"
                width="120px">
        </a>
    </div>

    <div class="d-md-none topbar-mobile-actions">
        <a href="#" class="navbar-toggler sidebar-search-trigger sidebar-search-trigger-mobile" data-bs-toggle="modal"
            data-bs-target="#sidebarSearchModal" aria-label="{{ __('Search sidebar menu') }}">
            <i class="fas fa-search"></i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-mobile">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav nav-toggler-button d-none d-md-flex">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav mobile-top-links d-md-none">
            <li class="nav-item">
                <a href="{{ route('admin.editProfile') }}" class="nav-link">
                    <i class="fas fa-user"></i> {{ __('My profile') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.chnage_password') }}" class="nav-link">
                    <i class="fas fa-key"></i> {{ __('Change Password') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#minorchangeModal"
                    aria-controls="minorchangeModal">
                    <i class="fas fa-cog"></i> {{ __('Website Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.logout') }}" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                </a>
            </li>
        </ul>

        <div class="navbar-search-slot d-none d-md-flex">
            <a href="#" class="navbar-nav-link sidebar-search-trigger sidebar-search-trigger-lg" data-bs-toggle="modal"
                data-bs-target="#sidebarSearchModal" aria-label="{{ __('Search sidebar menu') }}">
                <i class="fas fa-search"></i>
                <span class="sidebar-search-trigger-label">{{ __('Search sidebar menu...') }}</span>
                <span class="sidebar-search-trigger-shortcut">/</span>
            </a>
        </div>
        <ul class="navbar-nav left-nav d-none d-md-flex">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img style="width: 38px; height:38px;"
                        src="{{ asset('assets/admin/img/' . Auth::guard('admin')->user()->image) }}"
                        class="rounded-circle" alt="photo">
                </a>

                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                    <a href="{{ route('admin.editProfile') }}" class="dropdown-item"><i class="icon-user-plus"></i> My
                        profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.chnage_password') }}" class="dropdown-item"><i class="icon-cogs5"></i>
                        Change Password</a>
                    <a href="{{ route('admin.logout') }}" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="navbar-nav-link website-settings" data-bs-toggle="offcanvas"
                    data-bs-target="#minorchangeModal" aria-controls="minorchangeModal">
                    <i class="fas fa-cog fa-spin"></i>
                </a>
            </li>
        </ul>

    </div>
</div>

<div class="modal fade sidebar-search-modal" id="sidebarSearchModal" tabindex="-1"
    aria-labelledby="sidebarSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h2 id="sidebarSearchModalLabel" class="visually-hidden">{{ __('Search sidebar menu') }}</h2>
                <label for="sidebarSearchInput" class="visually-hidden">{{ __('Search sidebar menu') }}</label>
                <div class="sidebar-search-input-wrap">
                    <i class="fas fa-search sidebar-search-input-icon" aria-hidden="true"></i>
                    <input id="sidebarSearchInput" class="form-control sidebar-search-input" type="search"
                        placeholder="{{ __('Type to search sidebar menu...') }}" autocomplete="off">
                </div>
                <div class="sidebar-search-hint">{{ __('Sidebar menus') }}</div>
                <div class="sidebar-search-results" id="sidebarSearchResults"
                    data-empty-default="{{ __('No sidebar menu found.') }}"
                    data-empty-noresult="{{ __('No sidebar menu matched your search.') }}"></div>
            </div>
        </div>
    </div>
</div>




<div class="offcanvas {{ $defaultLang->direction == 'RTL' ? 'offcanvas-start' : 'offcanvas-end' }}" tabindex="-1"
    id="minorchangeModal" aria-labelledby="minorchangeModalLabel">
    <div class="offcanvas-header">
        <h5 id="minorchangeModalLabel">{{ __('Website Settings') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <span class="text-warning mb-3">{{ __('You can make minor changes here. For all changes, please') }}
            <a href="{{ route('admin.website_setting') }}">{{ __('click here') }}</a></span>
        <form action="{{ route('admin.website_setting.minor_update') }}" id="minorForm" method="post" class="py-4">
            @csrf
            @if (session()->has('minorUpdate'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('minorUpdate') }}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="theme">{{ __('Theme') }}</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="theme" value="light" class="selectgroup-input"
                                    @checked(session()->get('themeColor', 'light') === 'light')>
                                <span class="selectgroup-button">{{ __('Light') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="theme" value="dark" class="selectgroup-input"
                                    @checked(session()->get('themeColor') === 'dark')>
                                <span class="selectgroup-button">{{ __('Dark') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="">{{ __('Language') }}*</label>
                        <select name="language_id" class="select2 form-control err_language_id">
                            <option disabled>{{ __('Select a Language') }}</option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}" @selected($defaultLang->id == $language->id)>{{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                        <p id="err_language_id" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Timezone') }}*</label>
                        <select name="timezone" id="" class="form-control select2 err_timezone">
                            @foreach ($timeZones as $timeZone)
                                <option value="{{ $timeZone['timezone'] }}">{{ $timeZone['timezone'] }}
                                </option>
                            @endforeach
                        </select>
                        <p id="err_timezone" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Currency Symbol') }}*</label>
                        <input type="text" name="currency_symbol" class="form-control err_currency_symbol"
                            value="{{ @$websiteInfo->currency_symbol }}">
                        <p id="err_currency_symbol" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Currency Text') }}*</label>
                        <input type="text" name="currency_text" class="form-control err_currency_text"
                            value="{{ @$websiteInfo->currency_text }}">
                        <p id="err_currency_text" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Currency Rate') }}*</label>
                        <div class="input-group">
                            <span class="input-group-text">1 USD =</span>
                            <input type="text" name="currency_rate" class="form-control err_currency_rate"
                                value="{{ @$websiteInfo->currency_rate }}"
                                aria-label="Amount (to the nearest dollar)" placeholder="{{ __('Enter amount') }}"
                                pattern="^\d+(\.\d{1,2})?$" required
                                title="Please enter a valid amount, e.g., 10 or 10.50">
                            <span class="input-group-text">{{ @$websiteInfo->currency_text }}</span>
                        </div>
                        <p id="err_currency_rate" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Websit Color') }}*</label>
                        <input type="text" name="website_color" value="{{ @$websiteInfo->website_color }}"
                            class="form-control err_website_color" data-jscolor="{}">
                        <p id="err_website_color" class="text-danger em"></p>
                    </div>
                </div>

                <div class="text-end">
                    <div class="form-group">
                        <button class="btn btn-success" id="minorBtn">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
