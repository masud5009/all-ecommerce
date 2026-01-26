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
                            <img src="{{ asset('assets/user/img/' . Auth::guard('web')->user()?->image) }}"
                                width="38" height="38" class="rounded-circle" alt="photo">

                        </a>
                        <div class="media-body sidebar-user-media">
                            <div class="media-title font-weight-semibold">
                                @if (Auth::guard('web')->user()->fullname)
                                    {{ Auth::guard('web')->user()->fullname }}
                                @else
                                    {{ Auth::guard('web')->user()->username }}
                                @endif
                            </div>
                            <div class="font-size-xs opacity-50">
                                Admin
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

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}"
                        class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- product managment -->
                <li class="nav-item nav-item-submenu @sidebarMenu('user.product*')">
                    <a href="#" class="nav-link">
                        <i class="fas fa-store"></i>
                        <span>{{ __('Product Management') }}</span>
                    </a>

                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Product Management') }}">
                        <li class="nav-item">
                            <a href="{{ route('user.product.category', ['language' => $defaultLang->code]) }}"
                                class="nav-link @sidebar('user.product.category')">
                                {{ __('Categories') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.product', ['language' => $defaultLang->code]) }}"
                                class="nav-link @sidebar(['user.product', 'user.product.create', 'user.product.edit', 'user.product.variant'])">
                                {{ __('Products') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- tables -->
                <li class="nav-item">
                    <a href="{{ route('user.tables', ['language' => $defaultLang->code]) }}"
                        class="nav-link @sidebar(['user.tables', 'user.tables.qr_code'])">
                        <i class="fas fa-table"></i>
                        <span>{{ __('Tables') }}</span>
                    </a>
                </li>
                <!-- pos -->
                <li class="nav-item">
                    <a href="{{ route('user.pos',['language' => $defaultLang->code]) }}" class="nav-link @sidebar('user.pos')">
                        <i class="fas fa-utensils"></i>
                        <span>{{ __('POS') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
