<!DOCTYPE html>
<html>
    <head>
        <title>INVENTARICO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="/assets/vendor/semantic/2.5.0/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/app.css?v={{ config('inco.version') }}">
    </head>
    <body>
        <div class="ui container">
            <div class="ui hidden divider"></div>
            <div class="ui grid center aligned">
                <div class="row">
                    <div class="wide column">
                        <div class="ui raised segment">
                            <div class="ui huge header">
                                INVENTARICO
                            </div>
                            <p>
                                <a href="/login">{{ trans('app.do_login') }}</a>
                                |
                                <a href="/register">{{ trans('app.do_register') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
