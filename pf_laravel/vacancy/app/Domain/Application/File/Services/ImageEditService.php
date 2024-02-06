<?php
/**
 * Created date 23.02.2021
 *
 * @author Sergey Tyrgola <ts@goldcarrot.ru>
 */

namespace App\Domain\Application\File\Services;


use Intervention\Image\Image;
use InvalidArgumentException;


class ImageEditService
{
    public function fit(Image $image, int $width, int $height): Image
    {
        $this->check($width, $height);

        ini_set('memory_limit', -1);

        if ($this->imageFits($image, $width, $height)) {
            return $image;
        }

        return $image->fit($width, $height);
    }

    public function crop(Image $image, int $width, int $height, int $x, int $y): Image
    {
        $this->check($width, $height);

        ini_set('memory_limit', -1);

        if ($this->imageFits($image, $width, $height)) {
            return $image;
        }

        return $image->crop($width, $height, $x, $y);
    }

    public function resize(Image $image, int $width, int $height): Image
    {
        $this->check($width, $height);

        ini_set('memory_limit', -1);

        return $image->resize($width, $height);
    }

    public function widen(Image $image, int $width): Image
    {
        $this->check($width, null);

        ini_set('memory_limit', -1);

        return $image->widen($width);
    }

    public function encode(Image $image, $extension): Image
    {
        ini_set('memory_limit', -1);

        return $image->encode($extension);
    }

    private function check(int $width, ?int $height)
    {
        if ($width <= 0) {
            throw new InvalidArgumentException('Image width must be greater than 0');
        }

        if ($height !== null && $height <= 0) {
            throw new InvalidArgumentException('Image height must be greater than 0');
        }
    }

    private function imageFits(Image $image, int $width, ?int $height = null): bool
    {
        return $image->getWidth() <= $width && (!$height || $image->getHeight() <= $height);
    }
}
