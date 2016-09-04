<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'INVENTARICO' }}</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/assets/vendor/semantic/2.2.4/semantic.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.css">

    <script src="/assets/vendor/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
    {{-- Меню --}}
    <div class="ui main stackable large menu">
        <a href="/" class="header item">
            <b>INVENTARICO</b>
        </a>
        <a href="/dashboard" class="item">
            <i class="dashboard icon"></i>
            Dashboard
        </a>

        @if(!Auth::guest())
            <div class="right menu">
                <div class="ui top right dropdown item">
                    <strong>{{ Auth::user()->name }}</strong>
                    <i class="dropdown icon menu-avatar-dropdown"></i>

                    <div class="menu">
                        <a href="/logout"
                           class="item"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        >
                            Logout
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

    <script defer src="/assets/vendor/semantic/2.2.4/semantic.js"></script>
    <script defer src="/assets/js/app.js"></script>
</body>
</html>
