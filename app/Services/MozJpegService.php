<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

use function exec;
use function filesize;
use function unlink;

final class MozJpegService
{
    /**
     * @param array<string, mixed> $options
     */
    public static function manipulate(string $original, array $options = []): void
    {
        $new = $options['output_filename'] ?? $original;

        $suffix = '.TEST';

        $quality = $options['quality'] ?? '';
        $quality = $quality ? '-quality ' . $quality : '';

        $cmd = "mozjpeg -optimize -progressive {$quality} {$original} > {$new}{$suffix}";

        $output = [];

        exec($cmd, $output);

        if (filesize($new . $suffix)) {
            exec("mv {$new}{$suffix} {$new}");
        } else {
            @unlink($new . $suffix);
            Log::warning('Can not create MozJpeg image for: ' . $new . '. Exec output: ' . print_r($output));
        }
    }
}
