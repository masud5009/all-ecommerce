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
    @includeIf('user.partials.styles')
    @yield('style')
</head>
@php
    $username = Auth::guard('web')->user()->username;
    $themeColor = session()->get($username . '_themeColor', 'light');
@endphp

<body data-background-color="{{ $themeColor }}"
    class="{{ request()->routeIs('user.pos') ? 'sidebar-xs' : '' }}">
    <div class="request-loader">
        <img src="{{ asset('assets/admin/loader.gif') }}" alt="">
    </div>
    <!-- top menu start -->
    @includeIf('user.partials.top-bar', ['themeColor' => $themeColor])
    <!-- top menu end -->

    <div class="page-content">
        <!-- side-bar menu start -->
        @includeIf('user.partials.side-bar')
        <!-- side-bar menu end -->

        <div class="content-wrapper">

            <div class="content">
                @yield('content')
            </div>

        </div>
    </div>

    @includeIf('user.partials.scripts')
    @yield('script')
</body>

</html>
