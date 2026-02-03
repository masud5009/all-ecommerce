<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="fas fa-bars"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="fas fa-expand"></i>
            <i class="fas fa-compress"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="#">
                            <img src="{{ asset('assets/admin/img/' . Auth::guard('admin')->user()->image) }}"
                                width="38" height="38" class="rounded-circle" alt="photo">
                        </a>
                        <div class="media-body sidebar-user-media">
                            <div class="media-title font-weight-semibold">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="font-size-xs opacity-50">
                                SuperAdmin
                            </div>
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="fas fa-cog"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <!-- menu builder -->
                <li class="nav-item">
                    <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}"
                        class="nav-link
                        @if (request()->routeIs('admin.menu_builder')) active @endif
                        ">
                        <i class="fas fa-bars"></i>
                        <span>{{ __('Menu Builder') }}</span>
                    </a>
                </li>
                <!-- home section -->
                <li class="nav-item">
                    <a href="{{ route('admin.home_section', ['language' => $defaultLang->code]) }}"
                        class="nav-link
                        @if (request()->routeIs('admin.home_section')) active @endif
                        ">
                        <i class="fas fa-home"></i>
                        <span>{{ __('Home Section') }}</span>
                    </a>
                </li>
                <!-- user managment -->
                <li
                    class="nav-item nav-item-submenu
                            @if (request()->routeIs('admin.vendor.setting')) nav-item-expanded nav-item-open @endif
                            @if (request()->routeIs('admin.user')) nav-item-expanded nav-item-open @endif
                            @if (request()->routeIs('admin.user.password_change')) nav-item-expanded nav-item-open @endif
                            @if (request()->routeIs('admin.vendor.edit')) nav-item-expanded nav-item-open @endif
                            ">
                    <a href="#" class="nav-link"><i class="fas fa-users"></i>
                        <span>{{ __('User Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('User Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.vendor.setting') }}"
                                class="nav-link {{ request()->routeIs('admin.vendor.setting') ? 'active' : '' }}">
                                {{ __('Settings') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.user') }}"
                                class="nav-link
                                 @if (request()->routeIs('admin.user')) active @endif
                                 @if (request()->routeIs('admin.vendor.edit')) active @endif
                                 @if (request()->routeIs('admin.user.password_change')) active @endif
                                 ">
                                {{ __('Registered Users') }}</a>
                        </li>
                    </ul>
                </li>
                <!-- Idea Evaluation -->
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.menu_builder', ['language' => $defaultLang->code]) }}"
                        class="nav-link
                        @if (request()->routeIs('admin.menu_builder')) active @endif
                        ">
                        <i class="fas fa-lightbulb"></i>
                        <span>{{ __('Idea Evaluation') }}</span>
                    </a>
                </li> --}}
                <!-- setting managment -->
                <li class="nav-item">
                    <a href="{{ route('admin.website_setting') }}"
                        class="nav-link
                        @if (request()->routeIs('admin.website_setting')) active @endif
                        @if (request()->routeIs('admin.maintenance')) active @endif
                        @if (request()->routeIs('admin.website_setting.general_setting')) active @endif
                        @if (request()->routeIs('admin.website_setting.mail_template')) active @endif
                        @if (request()->routeIs('admin.website_setting.mail_template.edit')) active @endif
                        @if (request()->routeIs('admin.website_setting.config_email')) active @endif
                        @if (request()->routeIs('admin.gateway')) active @endif
                        @if (request()->routeIs('admin.online_gateway')) active @endif
                        @if (request()->routeIs('admin.offline_gateway')) active @endif
                        @if (request()->routeIs('admin.offline_gateway')) active @endif
                        @if (request()->routeIs('admin.plugin')) active @endif
                        ">
                        <i class="fas fa-sliders-h"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                </li>
                <!-- Shipping Charge -->
                <li class="nav-item">
                    <a href="{{ route('admin.shop.shipping_charge', ['language' => $defaultLang->code]) }}"
                        class="nav-link
                        @if (request()->routeIs('admin.shop.shipping_charge')) active @endif
                        @if (request()->routeIs('admin.shop.shipping_charge_edit')) active @endif
                        ">
                        <i class="fas fa-shipping-fast"></i>
                        <span>{{ __('Shipping Charge') }}</span>
                    </a>
                </li>

                <!-- sales managment -->
                <li
                    class="nav-item nav-item-submenu
                                       @if (request()->routeIs('admin.sales')) nav-item-expanded nav-item-open @endif
                                       @if (request()->routeIs('admin.sales.details')) nav-item-expanded nav-item-open @endif
                                       @if (request()->routeIs('admin.sales.reports')) nav-item-expanded nav-item-open @endif
                                       @if (request()->routeIs('admin.transaction')) nav-item-expanded nav-item-open @endif
                                       ">
                    <a href="#" class="nav-link"><i class="fas fa-chart-line"></i>
                        <span>{{ __('Sales Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Sales Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.sales', ['language' => $defaultLang->code]) }}"
                                class="nav-link {{ request()->routeIs('admin.sales') || request()->routeIs('admin.sales.details') ? 'active' : '' }}">
                                {{ __('All Sales') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sales.reports') }}"
                                class="nav-link {{ request()->routeIs('admin.sales.reports') ? 'active' : '' }}">
                                {{ __('Reports') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.transaction') }}"
                                class="nav-link {{ request()->routeIs('admin.transaction') ? 'active' : '' }}">
                                {{ __('Transaction') }}</a>
                        </li>
                    </ul>
                </li>


                <!-- product managment -->
                <li
                    class="nav-item nav-item-submenu
                    {{ request()->routeIs('admin.product') || request()->routeIs('admin.product.*') ? 'nav-item-expanded nav-item-open' : '' }}
                            ">
                    <a href="#" class="nav-link"><i class="fas fa-store"></i>
                        <span>{{ __('Product Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Product Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.settings') }}"
                                class="nav-link {{ request()->routeIs('admin.product.settings') ? 'active' : '' }}">
                                {{ __('Settings') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product.category', ['language' => $defaultLang->code]) }}"
                                class="nav-link {{ request()->routeIs('admin.product.category') ? 'active' : '' }}">
                                {{ __('Categories') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product.coupon') }}"
                                class="nav-link {{ request()->routeIs('admin.product.coupon') ? 'active' : '' }}">
                                {{ __('Coupons') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product', ['language' => $defaultLang->code]) }}"
                                class="nav-link
                                            @if (request()->routeIs('admin.product')) active @endif
                                            @if (request()->routeIs('admin.product.create')) active @endif
                                            @if (request()->routeIs('admin.product.edit')) active @endif
                                            ">
                                {{ __('Products') }}</a>
                        </li>
                    </ul>
                </li>

                <!-- invendory managment -->
                <li
                    class="nav-item nav-item-submenu
                {{ request()->routeIs('admin.inventory_managmement.*') ? 'nav-item-expanded nav-item-open' : '' }}
                        ">
                    <a href="#" class="nav-link"><i class="fas fa-clipboard-list"></i>
                        <span>{{ __('Inventory Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Inventory Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.inventory_managmement.stock_overview', ['language' => $defaultLang->code]) }}"
                                class="nav-link
                            @if (request()->routeIs('admin.inventory_managmement.stock_overview')) active @endif
                            ">
                                <span>{{ __('Stock Overview') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.inventory_managmement.update_stock', ['language' => $defaultLang->code]) }}"
                                class="nav-link
                            @if (request()->routeIs('admin.inventory_managmement.update_stock')) active @endif
                            ">
                                <span>{{ __('Adjust Stock') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>




                <!-- blog managment -->
                <li
                    class="nav-item nav-item-submenu
                @if (request()->routeIs('admin.blog.category')) nav-item-expanded nav-item-open @endif
                @if (request()->routeIs('admin.blog')) nav-item-expanded nav-item-open @endif
                @if (request()->routeIs('admin.blog.create')) nav-item-expanded nav-item-open @endif
                @if (request()->routeIs('admin.blog.edit')) nav-item-expanded nav-item-open @endif
                ">
                    <a href="#" class="nav-link"><i class="fas fa-blog"></i>
                        <span>{{ __('Blog Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Blog Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.category', ['language' => $defaultLang->code]) }}"
                                class="nav-link {{ request()->routeIs('admin.blog.category') ? 'active' : '' }}">
                                {{ __('Categories') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog', ['language' => $defaultLang->code]) }}"
                                class="nav-link
                                @if (request()->routeIs('admin.blog')) active @endif
                                @if (request()->routeIs('admin.blog.create')) active @endif
                                @if (request()->routeIs('admin.blog.edit')) active @endif
                                ">
                                {{ __('Posts') }}</a>
                        </li>
                    </ul>
                </li>

                <!-- role managment -->
                <li
                    class="nav-item nav-item-submenu
                            @if (request()->routeIs('admin.role_managment')) nav-item-expanded nav-item-open @endif
                            @if (request()->routeIs('admin.all_admins')) nav-item-expanded nav-item-open @endif
                            @if (request()->routeIs('admin.role_managment.permission')) nav-item-expanded nav-item-open @endif
                            ">
                    <a href="#" class="nav-link"><i class="fas fa-user-shield"></i>
                        <span>{{ __('Role Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Role Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.role_managment') }}"
                                class="nav-link {{ request()->routeIs('admin.role_managment') || request()->routeIs('admin.role_managment.permission') ? 'active' : '' }}">
                                {{ __('Role & Permission') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.all_admins') }}"
                                class="nav-link {{ request()->routeIs('admin.all_admins') ? 'active' : '' }}">
                                {{ __('All Admins') }}</a>
                        </li>
                    </ul>
                </li>

                <!-- package managment -->
                <li
                    class="nav-item nav-item-submenu
                        @if (request()->routeIs('admin.package.setting')) nav-item-expanded nav-item-open @endif
                        @if (request()->routeIs('admin.package.edit')) nav-item-expanded nav-item-open @endif
        @if (request()->routeIs('admin.package')) nav-item-expanded nav-item-open @endif
        ">
                    <a href="#" class="nav-link"><i class="fas fa-user-lock"></i>
                        <span>{{ __('Package Management') }}</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Package Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.package.setting') }}"
                                class="nav-link {{ request()->routeIs('admin.package.setting') ? 'active' : '' }}">
                                {{ __('Settings') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.package') }}"
                                class="nav-link
                               @if (request()->routeIs('admin.package')) active @endif
                               @if (request()->routeIs('admin.package.edit')) active @endif
                                 ">
                                {{ __('Packages') }}</a>
                        </li>
                    </ul>
                </li>


                <!-- language managment -->
                <li class="nav-item">
                    <a href="{{ route('admin.language') }}"
                        class="nav-link
                           @if (request()->routeIs('admin.language')) active @endif
                    @if (request()->routeIs('admin.language.edit_keyword')) active @endif
                    @if (request()->routeIs('admin.language.edit_admin_keyword')) active @endif
                        ">
                        <i class="fas fa-language"></i>
                        <span>{{ __('Language Management') }}</span>
                    </a>
                </li>

                <!-- user managment -->
                {{-- <li
                    class="nav-item nav-item-submenu
                @if (request()->routeIs('admin.user.index')) nav-item-expanded nav-item-open @endif
                @if (request()->routeIs('admin.user.edit')) nav-item-expanded nav-item-open @endif
                ">
                    <a href="#" class="nav-link"><i class="fas fa-users"></i> <span>
                            {{ __('User Management') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('User Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.index') }}"
                                class="nav-link
                                @if (request()->routeIs('admin.user.index')) active @endif
                                @if (request()->routeIs('admin.user.edit')) active @endif">
                                {{ __('Registered Users') }}</a>
                        </li>
                    </ul>
                    <ul class="nav nav-group-sub" data-submenu-title="Administrative">
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link active">Notifications</a>
                            <ul class="nav nav-group-sub">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        {{ __('Settings') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Send Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
