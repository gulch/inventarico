<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class ServerTiming
{
    private $metrics = [];

    public function handle(Request $request, Closure $next)
    {
        $server = $request->server();

        $bootstrap_mark = \microtime(true);

        // nginx handle time
        if (isset($server['REQUEST_START_TIME_USEC'])) {
            $start_mark = $server['REQUEST_START_TIME_USEC'] / 1000; // ms
            $duration = $server['REQUEST_TIME_FLOAT'] * 1000 - $start_mark;
            $this->addMetric('00_ngx', sprintf('%2.1f', $duration));
        }

        // preload
        /* $duration = (\LARAVEL_START - $server['REQUEST_TIME_FLOAT']) * 1000;
        $this->addMetric('01_preload', sprintf('%2.1f', $duration)); */

        // bootstrap
        $duration = ($bootstrap_mark - \LARAVEL_START) * 1000;
        $this->addMetric('01_boot', \sprintf('%2.1f', $duration));

        $response = $next($request);

        // application (my code)
        $duration = (\microtime(true) - $bootstrap_mark) * 1000;
        $this->addMetric('02_app', \sprintf('%2.2f', $duration));

        // database
        if(isset($GLOBALS['db_time'])) {
            $this->addMetric('0x_db', \sprintf('%2.2f', $GLOBALS['db_time']));
        }

        // total
        $duration = (\microtime(true) - $server['REQUEST_TIME_FLOAT']) * 1000;
        $this->addMetric('total', \sprintf('%2.2f', $duration));

        $response->headers->set(
            'Server-Timing',
            $this->generateHeaderValue(),
            false
        );

        return $response;
    }

    private function addMetric(string $name, string $value): void
    {
        $this->metrics[$name] = $value;
    }

    private function generateHeaderValue(): string
    {
        $result = '';

        foreach ($this->metrics as $key => $value) {
            if ($result !== '') {
                $result .= ',';
            }
            $result .= "{$key};dur={$value}";
        }

        return $result;
    }
}
