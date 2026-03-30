@php
    $currentRoute = Route::currentRouteName();

    $menuItems = [
        [
            'name' => 'Dashboard',
            'route' => 'user.dashboard',
            'icon' => 'M3 12a9 9 0 1 1 18 0 9 9 0 0 1-18 0M12 6v6m0 0v.01M12 12h.01',
        ],
        [
            'name' => 'My Orders',
            'route' => 'user.orders',
            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
        ],
        [
            'name' => 'Wishlist',
            'route' => 'user.wishlist',
            'icon' => 'M20.924 7.924a6 6 0 0 0-8.832-8.832L7.071 5.171A6 6 0 1 0 19 12a1 1 0 1 1 2 0 8 8 0 1 1-2.343-5.657l1.268-1.268a4 4 0 1 0-5.657 5.657l4.243 4.243a2 2 0 1 1-2.828 2.829l-4.243-4.243a1 1 0 0 1 1.414-1.414l4.243 4.243a2 2 0 0 0 2.829-2.828L20.413 12a6 6 0 0 0 .511-4.076z',
        ],
        [
            'name' => 'Edit Profile',
            'route' => 'user.edit_profile',
            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        ],
        [
            'name' => 'Change Password',
            'route' => 'user.change_password',
            'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        ],
    ];
@endphp

<div class="flex h-full flex-col border-r border-slate-200 bg-white">
    <!-- Sidebar Header -->
    <div class="border-b border-slate-200 px-6 py-4">
        <h2 class="text-lg font-bold text-slate-900">{{ $user->name ?? $user->username }}</h2>
        <p class="text-xs text-slate-500"> {{ __('Manage your account') }}</p>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6">
        <ul class="space-y-2">
            @foreach ($menuItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                        class="group relative flex items-center gap-3 rounded-lg px-4 py-3 font-medium transition {{ $currentRoute === $item['route'] ? 'border-l-4 border-green-600 bg-green-50 text-green-700' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">

                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"></path>
                        </svg>

                        <span>{{ $item['name'] }}</span>

                        @if ($currentRoute === $item['route'])
                            <span class="absolute right-3 h-2 w-2 rounded-full bg-green-600"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="border-t border-slate-200 px-4 py-4">
        <a href="{{ route('user.logout') }}"
            class="flex items-center gap-3 rounded-lg px-4 py-3 font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700">

            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>

            <span>Logout</span>
        </a>
    </div>
</div>
