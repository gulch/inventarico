<?php

declare(strict_types=1);

namespace App\Services;

use function app;

use gulch\Minify\Minifier;
use gulch\Minify\Processor\HtmlCommentsRemover;
use gulch\Minify\Processor\WhitespacesRemover;

final class MinifierService
{
    public static function handle(string $html): string
    {
        return self::getMinifier()->process($html);
    }

    private static function getMinifier(): Minifier
    {
        if ( ! app()->has('html-minifier')) {
            app()->singleton(
                'html-minifier',
                function () {
                    return new Minifier(
                        new HtmlCommentsRemover(),
                        new WhitespacesRemover(),
                    );
                },
            );
        }

        return app()->get('html-minifier');
    }
}
