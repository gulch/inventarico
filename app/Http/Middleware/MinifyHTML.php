<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\MinifierService;
use Closure;

use function microtime;
use function sprintf;

final class MinifyHTML
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $start_time = microtime(true);

        $response->setContent(
            MinifierService::handle($response->getContent()),
        );

        $duration = microtime(true) - $start_time;

        $response->headers->set(
            'X-Minify-Time',
            sprintf('%2.3f ms', $duration * 1000),
        );

        return $response;
    }
}
