<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\MinifierService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function microtime;
use function sprintf;

final class MinifyHTML
{
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var Response $response
         */
        $response = $next($request);

        // if redirect response
        if($response->getStatusCode() > 300 && $response->getStatusCode() < 400) {
            return $response;
        }

        $start_time = microtime(true);

        $response->setContent(
            MinifierService::handle((string)$response->getContent()),
        );

        $duration = microtime(true) - $start_time;

        $response->headers->set(
            'X-Minify-Time',
            sprintf('%2.3f ms', $duration * 1000),
        );

        return $response;
    }
}
