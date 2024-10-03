<?php

declare(strict_types=1);

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class CreateGeneralImageService
{
    public static function imageManager(): ImageManager
    {
        return new ImageManager(new ImagickDriver());
    }

    /**
     * @param array<string, mixed> $options
     */
    public static function manipulate(string $original, string $new, array $options): string
    {
        $quality = $options['quality'] ?? 80;
        $width = $options['width'] ?? null;
        $height = $options['height'] ?? null;
        $crop = $options['crop'] ?? false;
        $sharp = $options['sharp'] ?? null;

        $image = self::imageManager()->read($original);

        if ($width && $height) {
            // if crop option is true
            if ($crop) {
                $image->cover($width, $height);
            } else {
                // vertical image -> resize to right width
                if ($image->height() < $image->width()) {
                    $image->scale($width);
                } else {
                    // horizontal image -> resize to right height
                    $image->scale($height);
                }
            }
        }

        if ($sharp) {
            $image->sharpen($sharp);
        }

        /* remove metadata from image */
        $image->core()->native()->stripImage();

        $image->toJpeg(progressive: true, quality: $quality)->save($new);

        return $new;
    }
}
