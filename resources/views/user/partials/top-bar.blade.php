<div class="navbar navbar-expand-md bg-nav">
    <div class="navbar-logo">
        <a href="{{ route('frontend.index') }}" class="logo" target="_blank">
            <img src="{{ asset('assets/front/img/' . $authUser->settings->website_logo) }}" alt="" class="navbar-brand"
                width="120px">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-mobile">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav nav-toggler-button">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3 mr-md-auto"></span>
        <ul class="navbar-nav left-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img style="width: 38px; height:38px;"
                        src="{{ asset('assets/user/img/' . Auth::guard('web')->user()?->image) }}"
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





<div class="offcanvas {{ $defaultLang->direction == 'RTL' ? 'offcanvas-start' : 'offcanvas-end' }}" tabindex="-1"
    id="minorchangeModal" aria-labelledby="minorchangeModalLabel">
    <div class="offcanvas-header">
        <h5 id="minorchangeModalLabel">{{ __('Website Settings') }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <span class="text-warning mb-3">{{ __('You can make minor changes here. For all changes, please') }}
            <a href="{{ route('user.website_setting') }}">{{ __('click here') }}</a></span>
        <form action="{{ route('user.settings.minor_update') }}" id="minorForm" method="post" class="py-4">
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
                                    @checked($themeColor === 'light')>
                                <span class="selectgroup-button">{{ __('Light') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="theme" value="dark" class="selectgroup-input"
                                    @checked($themeColor === 'dark')>
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
                            @foreach ($authUser->languages as $language)
                                <option value="{{ $language->id }}" @selected($authUser->currentLanguage->id == $language->id)>
                                    {{ $language->name }}
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
                            value="{{ @$authUser->settings->currency_symbol }}">
                        <p id="err_currency_symbol" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Currency Text') }}*</label>
                        <input type="text" name="currency_text" class="form-control err_currency_text"
                            value="{{ @$authUser->settings->currency_text }}">
                        <p id="err_currency_text" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Currency Rate') }}*</label>
                        <div class="input-group">
                            <span class="input-group-text">1 USD =</span>
                            <input type="text" name="currency_rate" class="form-control err_currency_rate"
                                value="{{ @$authUser->settings->currency_rate }}"
                                aria-label="Amount (to the nearest dollar)" placeholder="{{ __('Enter amount') }}"
                                pattern="^\d+(\.\d{1,2})?$" required
                                title="Please enter a valid amount, e.g., 10 or 10.50">
                            <span class="input-group-text">{{ @$authUser->settings->currency_text }}</span>
                        </div>
                        <p id="err_currency_rate" class="text-danger em"></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label>{{ __('Websit Color') }}*</label>
                        <input type="text" name="website_color" value="{{ @$authUser->settings->website_color }}"
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
