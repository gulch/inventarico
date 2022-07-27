<?php

namespace App\Services;

use gulch\Minify\Minifier;
use gulch\Minify\Processor\AttributesSimplifier;
use gulch\Minify\Processor\HtmlCommentsRemover;
use gulch\Minify\Processor\QuotesRemover;
use gulch\Minify\Processor\WhitespacesRemover;

use function app;

class MinifierService
{
    public static function handle(string $html)
    {
        return self::getMinifier()->process($html);
    }

    protected static function getMinifier()
    {
        if (!app()->has('html-minifier')) {
            app()->singleton(
                'html-minifier',
                function () {
                    return new Minifier(
                        new HtmlCommentsRemover,
                        new WhitespacesRemover,
                    );
                }
            );
        }

        return app()->get('html-minifier');
    }
}
