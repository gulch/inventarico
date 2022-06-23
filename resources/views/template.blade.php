<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>{{ isset($title) ? $title . ' :: ' : '' }}{{ config('app.name') }}</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="/assets/vendor/semantic/2.4.2/semantic.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/fonts.css?v={{ config('app.version') }}">

    @if(isset($styles))
        @foreach($styles as $style)
            <link rel="stylesheet" type="text/css" href="{{ $style }}">
        @endforeach
    @endif

    <link rel="stylesheet" type="text/css" href="/assets/css/app.css?v={{ config('app.version') }}">

    {{-- jQuery --}}
    <script src="/assets/vendor/jquery/3.6.0/jquery.min.js"></script>

    {{-- Favicon --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/favicon/app.webmanifest">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
{{-- Menu --}}
<div class="ui main stackable large menu">
    <a href="/" class="header item">
        <b>INVENTARICO</b>
    </a>
    @if(!auth()->guest())
        <a href="/dashboard" class="item">
            <i class="dashboard icon"></i>
            {{ trans('app.dashboard') }}
        </a>

        <a href="/categories" class="item">
            <i class="folder outline icon"></i>
            {{ trans('app.categories') }}
        </a>

        <a href="/items" class="item">
            <i class="gift icon"></i>
            {{ trans('app.items') }}
        </a>

        <a href="/operations" class="item">
            <i class="file text outline icon"></i>
            {{ trans('app.operations') }}
        </a>

        <a href="/operation-types" class="item">
            <i class="cubes icon"></i>
            {{ trans('app.operation_types') }}
        </a>

        <a href="/photos" class="item">
            <i class="photo icon"></i>
            {{ trans('app.photos') }}
        </a>

        <div class="right menu">
            <div class="ui top right dropdown item">
                <strong>{{ auth()->user()->name }}</strong>
                <i class="dropdown icon menu-avatar-dropdown"></i>

                <div class="menu">
                    <a href="/logout"
                       class="item"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                        {{ trans('app.logout') }}
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="ui hidden divider"></div>

<div class="ui container">
    @yield('content')
</div>

<div class="ui hidden divider"></div>
<div class="ui hidden divider"></div>

@if(isset($scripts))
    @foreach($scripts as $js)
        @if(is_array($js))
            <script {{ $js['load'] }} src="{{ $js['src'] }}"></script>
        @else
            <script src="{{ $js }}"></script>
        @endif
    @endforeach
@endif

<script defer src="/assets/vendor/semantic/2.4.2/semantic.min.js"></script>
<script defer src="/assets/js/app.js?v={{ config('app.version') }}"></script>
</body>
</html>
