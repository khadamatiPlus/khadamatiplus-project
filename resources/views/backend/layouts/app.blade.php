<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'HudhudIT')">
    <link rel="icon" type="image/png" href="{{ asset('img/brand/hayat-delivery-logo.png') }}">

    @yield('meta')

    @stack('before-styles')
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet">
    <livewire:styles />
    @stack('after-styles')
</head>
<body class="c-app">
@include('backend.includes.sidebar')

<div class="c-wrapper c-fixed-components">
    @include('backend.includes.header')
    @include('includes.partials.logged-in-as')

    <div class="c-body">
        <main class="c-main">
            <div class="container-fluid">
                <div class="fade-in">
                    @include('includes.partials.messages')
                    @yield('content')
                </div><!--fade-in-->
            </div><!--container-fluid-->
        </main>
    </div><!--c-body-->

    @include('backend.includes.footer')
</div><!--c-wrapper-->

@stack('before-scripts')
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/backend.js') }}"></script>
<script src="{{ asset('js/backend/real-time-dashboard.js') }}"></script>
<livewire:scripts />
@stack('after-scripts')
</body>
</html>
