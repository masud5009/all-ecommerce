<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $defaultLang->direction == 'RTL' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Admin') }} - {{ $websiteInfo->website_title }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    {{-- include all styles --}}
    @includeIf('admin.partials.styles')
    @yield('style')
</head>

<body data-background-color="{{ session()->has('themeColor') ? session()->get('themeColor') : 'light' }}"
    class="{{ request()->routeIs('admin.pos_mangment') ? 'sidebar-xs' : '' }}">
    <div class="request-loader">
        <img src="{{ asset('assets/admin/loader.gif') }}" alt="">
    </div>
    <!-- top menu start -->
    @includeIf('admin.partials.top-bar')
    <!-- top menu end -->

    <div class="page-content">
        <!-- side-bar menu start -->
        @includeIf('admin.partials.side-bar')
        <!-- side-bar menu end -->

        <div class="content-wrapper">
            {{-- @includeIf('admin.partials.breadcrumb') --}}

            <div class="content">
                @yield('content')
            </div>

        </div>
    </div>

    @includeIf('admin.partials.scripts')
    @yield('script')
</body>

</html>
