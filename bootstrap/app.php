<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        /* health: '/up', */
    )
    ->withMiddleware(function (Middleware $middleware) {
        // OVERRIDING GLOBAL MIDDLEWARES
        $middleware->use([
            \App\Http\Middleware\ServerTiming::class,
            \App\Http\Middleware\MinifyHTML::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
