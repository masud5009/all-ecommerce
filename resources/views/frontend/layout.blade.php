<!DOCTYPE html>
<html lang="en" dir="{{ $currentLang->direction == 1 ? 'rtl' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="MasudRana">
    <!-- Meta Keword -->
    <meta name="keywords" content="@yield('metaKeywords')">
    <!-- Meta Description -->
    <meta name="description" content="@yield('metaDescription')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Website Title-->
    <title>@yield('pageHeading') {{ '-' . $websiteInfo->website_title }}</title>
    <!-- Website Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
    @includeIf('frontend.partials.styles')
</head>

<body>

    @if (!request()->routeIs('user.login'))
        @include('frontend.include.header')
    @endif

    @yield('content')

    @if (!request()->routeIs('user.login'))
        @include('frontend.include.footer')
    @endif

    @include('frontend.partials.scripts')
</body>

</html>
