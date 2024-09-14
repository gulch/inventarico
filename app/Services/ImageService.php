<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\CreateGeneralImageService;
use App\Services\MozJpegService;

use function array_merge;

final class ImageService
{
    /**
     * @param array<string, mixed> $options
     */
    public static function manipulate(string $original, string $new, array $options = []): void
    {
        $output_quality = $options['quality'] ?? 80;

        // create first 100% quality JPG
        CreateGeneralImageService::manipulate(
            $original,
            $new,
            array_merge($options, ['quality' => 100]),
        );

        // then process by MozJPEG to $output_quality
        MozJpegService::manipulate($new, ['quality' => $output_quality]);
    }
}